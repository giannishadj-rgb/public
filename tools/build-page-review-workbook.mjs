import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, "..");
const sqlPath = path.resolve(rootDir, "..", "sql", "local.sql");
const outputDir = path.resolve(rootDir, "outputs", "page-review");
const outputPath = path.join(outputDir, "caviar-of-switzerland-page-review.xlsx");

const postColumns = [
  "ID",
  "post_author",
  "post_date",
  "post_date_gmt",
  "post_content",
  "post_title",
  "post_excerpt",
  "post_status",
  "comment_status",
  "ping_status",
  "post_password",
  "post_name",
  "to_ping",
  "pinged",
  "post_modified",
  "post_modified_gmt",
  "post_content_filtered",
  "post_parent",
  "guid",
  "menu_order",
  "post_type",
  "post_mime_type",
  "comment_count",
];

const optionColumns = ["option_id", "option_name", "option_value", "autoload"];
const postMetaColumns = ["meta_id", "post_id", "meta_key", "meta_value"];

function splitSqlRows(valuesText) {
  const rows = [];
  let row = [];
  let value = "";
  let inString = false;
  let escaped = false;
  let inRow = false;

  for (let i = 0; i < valuesText.length; i += 1) {
    const char = valuesText[i];

    if (!inRow) {
      if (char === "(") {
        inRow = true;
        row = [];
        value = "";
      }
      continue;
    }

    if (inString) {
      if (escaped) {
        value += "\\" + char;
        escaped = false;
      } else if (char === "\\") {
        escaped = true;
      } else if (char === "'") {
        inString = false;
      } else {
        value += char;
      }
      continue;
    }

    if (char === "'") {
      inString = true;
    } else if (char === ",") {
      row.push(parseSqlValue(value));
      value = "";
    } else if (char === ")") {
      row.push(parseSqlValue(value));
      rows.push(row);
      inRow = false;
      value = "";
    } else {
      value += char;
    }
  }

  return rows;
}

function parseSqlValue(raw) {
  const trimmed = raw.trim();
  if (trimmed.toUpperCase() === "NULL") return null;
  if (/^-?\d+$/.test(trimmed)) return Number(trimmed);
  return unescapeSql(raw);
}

function unescapeSql(value) {
  return value
    .replace(/\\0/g, "\0")
    .replace(/\\n/g, "\n")
    .replace(/\\r/g, "\r")
    .replace(/\\t/g, "\t")
    .replace(/\\b/g, "\b")
    .replace(/\\Z/g, "\x1a")
    .replace(/\\"/g, '"')
    .replace(/\\'/g, "'")
    .replace(/\\\\/g, "\\");
}

function rowsForTable(sql, tableName) {
  // The dump writes one INSERT per physical line. Page content contains many
  // semicolons, so matching to the first semicolon truncates most post rows.
  const regex = new RegExp(String.raw`^INSERT INTO \`${tableName}\` VALUES ([^\r\n]+);\r?$`, "gm");
  const rows = [];
  let match;
  while ((match = regex.exec(sql)) !== null) {
    rows.push(...splitSqlRows(match[1]));
  }
  return rows;
}

function objectRows(rows, columns) {
  return rows.map((row) => Object.fromEntries(columns.map((column, index) => [column, row[index]])));
}

function htmlDecode(text) {
  return String(text ?? "")
    .replace(/&#038;/g, "&")
    .replace(/&#8217;/g, "'")
    .replace(/&#8211;/g, "-")
    .replace(/&#8212;/g, "-")
    .replace(/&#8220;|&#8221;/g, '"')
    .replace(/&amp;/g, "&")
    .replace(/&lt;/g, "<")
    .replace(/&gt;/g, ">")
    .trim();
}

function cleanBaseUrl(url) {
  return String(url || "").replace(/\/+$/, "");
}

function pagePath(page, pageMap, seen = new Set()) {
  if (!page || seen.has(page.ID)) return "";
  seen.add(page.ID);
  const parent = pageMap.get(Number(page.post_parent));
  const parentPath = parent ? pagePath(parent, pageMap, seen) : "";
  const slug = String(page.post_name || "").replace(/^\/+|\/+$/g, "");
  return [parentPath, slug].filter(Boolean).join("/");
}

function postUrl(post, baseUrl) {
  if (post.post_type === "post") {
    return `${baseUrl}/${String(post.post_name || "").replace(/^\/+|\/+$/g, "")}/`;
  }
  if (post.post_type === "product") {
    return `${baseUrl}/products/${String(post.post_name || "").replace(/^\/+|\/+$/g, "")}/`;
  }
  return post.guid || "";
}

function depthOf(page, pageMap) {
  let depth = 0;
  let current = pageMap.get(Number(page.post_parent));
  const seen = new Set([page.ID]);
  while (current && !seen.has(current.ID)) {
    depth += 1;
    seen.add(current.ID);
    current = pageMap.get(Number(current.post_parent));
  }
  return depth;
}

function orderPages(pages, pageMap, frontPageId) {
  const children = new Map();
  for (const page of pages) {
    const parentId = Number(page.post_parent || 0);
    if (!children.has(parentId)) children.set(parentId, []);
    children.get(parentId).push(page);
  }

  for (const group of children.values()) {
    group.sort((a, b) => {
      if (Number(a.ID) === frontPageId) return -1;
      if (Number(b.ID) === frontPageId) return 1;
      return (
        Number(a.menu_order || 0) - Number(b.menu_order || 0) ||
        htmlDecode(a.post_title).localeCompare(htmlDecode(b.post_title)) ||
        Number(a.ID) - Number(b.ID)
      );
    });
  }

  const ordered = [];
  const visit = (parentId) => {
    for (const page of children.get(parentId) || []) {
      ordered.push(page);
      visit(Number(page.ID));
    }
  };
  visit(0);

  for (const page of pages) {
    if (!ordered.includes(page)) ordered.push(page);
  }
  return ordered;
}

function dateValue(text) {
  if (!text || text === "0000-00-00 00:00:00") return null;
  return new Date(String(text).replace(" ", "T"));
}

const sql = await fs.readFile(sqlPath, "utf8");
const options = objectRows(rowsForTable(sql, "wp_options"), optionColumns);
const posts = objectRows(rowsForTable(sql, "wp_posts"), postColumns);
const postMeta = objectRows(rowsForTable(sql, "wp_postmeta"), postMetaColumns);

const optionMap = new Map(options.map((option) => [option.option_name, option.option_value]));
const homeUrl = cleanBaseUrl(optionMap.get("home") || optionMap.get("siteurl"));
const frontPageId = Number(optionMap.get("page_on_front") || 0);
const pageTemplates = new Map(
  postMeta
    .filter((meta) => meta.meta_key === "_wp_page_template")
    .map((meta) => [Number(meta.post_id), meta.meta_value])
);

const preservedByUrl = new Map();
try {
  const existingFile = await FileBlob.load(outputPath);
  const existingWorkbook = await SpreadsheetFile.importXlsx(existingFile);
  const existingSheet = existingWorkbook.worksheets.getItem("Pages");
  const existingValues = existingSheet.getUsedRange(true).values;
  const headerRow = existingValues.findIndex((row) => row.includes("URL"));
  if (headerRow >= 0) {
    const headerIndex = new Map(existingValues[headerRow].map((header, index) => [header, index]));
    for (const row of existingValues.slice(headerRow + 1)) {
      const url = row[headerIndex.get("URL")];
      if (!url) continue;
      preservedByUrl.set(String(url), {
        reviewed: row[headerIndex.get("Reviewed")] || "☐",
        priority: row[headerIndex.get("Priority")] || "",
        owner: row[headerIndex.get("Owner")] || "",
        notes: row[headerIndex.get("Review notes")] || "",
      });
    }
  }
} catch {
  // First run or unreadable prior output: start with clean review fields.
}

const pages = posts.filter((post) => post.post_type === "page" && post.post_status !== "auto-draft" && post.post_status !== "trash");
const pageMap = new Map(pages.map((page) => [Number(page.ID), page]));
const orderedPages = orderPages(pages, pageMap, frontPageId);
const otherPublicContent = posts
  .filter((post) => ["post", "product"].includes(post.post_type) && post.post_status === "publish")
  .sort((a, b) => {
    const typeOrder = { post: 1, product: 2 };
    return (
      typeOrder[a.post_type] - typeOrder[b.post_type] ||
      htmlDecode(a.post_title).localeCompare(htmlDecode(b.post_title)) ||
      Number(a.ID) - Number(b.ID)
    );
  });

const headers = [
  "Reviewed",
  "Order",
  "Page",
  "Title",
  "URL",
  "Status",
  "Type",
  "Parent",
  "Slug",
  "Menu order",
  "Template",
  "Last modified",
  "Priority",
  "Owner",
  "Review notes",
  "Source post ID",
  "Source",
];

const rows = orderedPages.map((page, index) => {
  const depth = depthOf(page, pageMap);
  const title = htmlDecode(page.post_title) || "(Untitled)";
  const parent = pageMap.get(Number(page.post_parent));
  const pathPart = pagePath(page, pageMap);
  const url = Number(page.ID) === frontPageId ? `${homeUrl}/` : `${homeUrl}/${pathPart}/`;
  const status = page.post_status;
  const saved = preservedByUrl.get(url);
  const priority = saved?.priority || (status === "publish" ? (Number(page.ID) === frontPageId ? "High" : "Normal") : "Backlog");
  return [
    saved?.reviewed || "☐",
    index + 1,
    `${"  ".repeat(depth)}${title}`,
    title,
    url,
    status,
    "Page",
    parent ? htmlDecode(parent.post_title) || `(ID ${parent.ID})` : "",
    page.post_name,
    Number(page.menu_order || 0),
    pageTemplates.get(Number(page.ID)) || "default",
    dateValue(page.post_modified),
    priority,
    saved?.owner || "",
    saved?.notes || "",
    Number(page.ID),
    "WordPress page",
  ];
});

for (const post of otherPublicContent) {
  const url = postUrl(post, homeUrl);
  const saved = preservedByUrl.get(url);
  rows.push([
    saved?.reviewed || "☐",
    rows.length + 1,
    post.post_type === "product" ? "Product" : "Post",
    htmlDecode(post.post_title) || "(Untitled)",
    url,
    post.post_status,
    post.post_type === "product" ? "Product" : "Post",
    "",
    post.post_name,
    Number(post.menu_order || 0),
    post.post_type,
    dateValue(post.post_modified),
    saved?.priority || (post.post_type === "product" ? "Normal" : "Low"),
    saved?.owner || "",
    saved?.notes || "",
    Number(post.ID),
    post.post_type === "product" ? "WooCommerce product" : "WordPress post",
  ]);
}

const workbook = Workbook.create();
const sheet = workbook.worksheets.add("Pages");
sheet.showGridLines = false;
sheet.tabColor = "#244C5A";

sheet.getRange("A1").values = [["Website page review"]];
sheet.getRange("A2").values = [[`Source: WordPress database export (${path.relative(rootDir, sqlPath).replaceAll("\\", "/")})`]];
sheet.getRange("A3").values = [[`Site URL: ${homeUrl}`]];
sheet.getRange("A1:Q1").format.font = { name: "Arial", size: 14, bold: true, color: "#1F2933" };
sheet.getRange("A2:Q3").format.font = { name: "Arial", size: 9, italic: true, color: "#5B6770" };

sheet.getRange("A5").write([headers, ...rows]);
const lastRow = rows.length + 5;
const tableRange = `A5:Q${lastRow}`;
const table = sheet.tables.add(tableRange, true, "PagesReviewTable");
table.style = "TableStyleMedium2";
table.showFilterButton = true;

sheet.getRange(tableRange).format.font = { name: "Arial", size: 10, color: "#1F2933" };
sheet.getRange("A5:Q5").format = {
  fill: "#244C5A",
  font: { name: "Arial", size: 10, bold: true, color: "#FFFFFF" },
};
sheet.getRange("A5:Q5").format.horizontalAlignment = "center";
sheet.getRange(`A6:A${lastRow}`).format.horizontalAlignment = "center";
sheet.getRange(`B6:B${lastRow}`).format.horizontalAlignment = "right";
sheet.getRange(`J6:J${lastRow}`).format.horizontalAlignment = "right";
sheet.getRange(`L6:L${lastRow}`).setNumberFormat("yyyy-mm-dd hh:mm");
sheet.getRange(`N6:O${lastRow}`).format.fill = "#FFF7CC";
sheet.getRange(`A6:A${lastRow}`).dataValidation = { rule: { type: "list", values: ["☐", "☑"] } };
sheet.getRange(`M6:M${lastRow}`).dataValidation = { rule: { type: "list", values: ["High", "Normal", "Low", "Backlog"] } };
sheet.getRange(`F6:F${lastRow}`).conditionalFormats.add("containsText", {
  text: "publish",
  format: { fill: "#E7F4EA", font: { color: "#146C2E" } },
});
sheet.getRange(`F6:F${lastRow}`).conditionalFormats.add("notContainsText", {
  text: "publish",
  format: { fill: "#FDEDED", font: { color: "#A61B1B" } },
});
sheet.getRange(`A6:A${lastRow}`).conditionalFormats.add("containsText", {
  text: "☑",
  format: { fill: "#E7F4EA", font: { color: "#146C2E", bold: true } },
});

sheet.freezePanes.freezeRows(5);
sheet.freezePanes.freezeColumns(2);
sheet.getRange("A:Q").format.verticalAlignment = "center";
sheet.getRange(`C6:E${lastRow}`).format.wrapText = true;
sheet.getRange(`A6:Q${lastRow}`).format.rowHeight = 32;
sheet.getRange("O:O").format.wrapText = true;
sheet.getRange("A:A").format.columnWidth = 10;
sheet.getRange("B:B").format.columnWidth = 8;
sheet.getRange("C:C").format.columnWidth = 34;
sheet.getRange("D:D").format.columnWidth = 32;
sheet.getRange("E:E").format.columnWidth = 58;
sheet.getRange("F:F").format.columnWidth = 12;
sheet.getRange("G:G").format.columnWidth = 13;
sheet.getRange("H:H").format.columnWidth = 26;
sheet.getRange("I:I").format.columnWidth = 28;
sheet.getRange("J:J").format.columnWidth = 12;
sheet.getRange("K:K").format.columnWidth = 22;
sheet.getRange("L:L").format.columnWidth = 19;
sheet.getRange("M:M").format.columnWidth = 12;
sheet.getRange("N:N").format.columnWidth = 18;
sheet.getRange("O:O").format.columnWidth = 40;
sheet.getRange("P:P").format.columnWidth = 13;
sheet.getRange("Q:Q").format.columnWidth = 22;
sheet.getRange(`A6:Q${lastRow}`).format.borders = {
  insideHorizontal: { style: "thin", color: "#E4E7EB" },
  insideVertical: { style: "thin", color: "#F0F2F4" },
};

workbook.recalculate();

const check = await workbook.inspect({
  kind: "table",
  range: `Pages!A5:Q${Math.min(lastRow, 15)}`,
  include: "values,formulas",
  tableMaxRows: 12,
  tableMaxCols: 17,
});
console.log(check.ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A|#NUM!|#NULL!|#SPILL!|#CALC!",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

await fs.mkdir(outputDir, { recursive: true });
const preview = await workbook.render({ sheetName: "Pages", range: `A1:Q${Math.min(lastRow, 24)}`, scale: 1, format: "png" });
await fs.writeFile(path.join(outputDir, "pages-preview.png"), new Uint8Array(await preview.arrayBuffer()));
const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(outputPath);

console.log(JSON.stringify({ outputPath, pageCount: rows.length, source: sqlPath }, null, 2));
