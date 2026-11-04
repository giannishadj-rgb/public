import fs from "node:fs/promises";
import path from "node:path";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const endpoint = "http://caviarofswitzerland.local/wp-json/wp/v2/pages";
const outputDir = path.resolve("../..", "outputs/page-inventory-2026-11-03");
const outputPath = path.join(outputDir, "published-page-titles-and-urls.xlsx");
const previewPath = path.join(outputDir, "published-page-titles-and-urls-preview.png");

function decodeHtml(value) {
  return value
    .replace(/&#(\d+);/g, (_, code) => String.fromCodePoint(Number(code)))
    .replace(/&#x([0-9a-f]+);/gi, (_, code) => String.fromCodePoint(parseInt(code, 16)))
    .replace(/&amp;/g, "&")
    .replace(/&quot;/g, '"')
    .replace(/&#0*39;|&apos;/g, "'")
    .replace(/&lt;/g, "<")
    .replace(/&gt;/g, ">");
}

async function getPublishedPages() {
  const firstUrl = `${endpoint}?per_page=100&page=1&status=publish&orderby=title&order=asc&_fields=id,link,title`;
  const firstResponse = await fetch(firstUrl);
  if (!firstResponse.ok) throw new Error(`WordPress REST request failed: ${firstResponse.status}`);
  const totalPages = Number(firstResponse.headers.get("x-wp-totalpages") || 1);
  const records = await firstResponse.json();
  for (let page = 2; page <= totalPages; page += 1) {
    const response = await fetch(`${endpoint}?per_page=100&page=${page}&status=publish&orderby=title&order=asc&_fields=id,link,title`);
    if (!response.ok) throw new Error(`WordPress REST request failed on page ${page}: ${response.status}`);
    records.push(...await response.json());
  }
  return records
    .map((record) => [decodeHtml(record.title.rendered), record.link])
    .sort((a, b) => a[0].localeCompare(b[0], "en", { sensitivity: "base" }));
}

const rows = await getPublishedPages();
const workbook = Workbook.create();
const sheet = workbook.worksheets.add("Pages");
sheet.showGridLines = false;
sheet.tabColor = "#10233F";

sheet.getRange("A2:B2").merge();
sheet.getRange("A2").values = [["Published page titles and URLs"]];
sheet.getRange("A2").format = {
  font: { name: "Arial", size: 14, bold: true, color: "#10233F" },
  verticalAlignment: "center",
};
sheet.getRange("A2:B2").format.rowHeight = 26;

sheet.getRange("A3:B3").merge();
sheet.getRange("A3").values = [[`Source: WordPress REST API • ${rows.length} published pages • 3 November 2026`]];
sheet.getRange("A3").format = {
  font: { name: "Arial", size: 10, italic: true, color: "#667085" },
  verticalAlignment: "center",
  borders: { bottom: { style: "thin", color: "#C7CDD6" } },
};
sheet.getRange("A3:B3").format.rowHeight = 22;

const headerRow = 5;
const data = [["Page title", "URL"], ...rows];
const endRow = headerRow + data.length - 1;
const dataRange = sheet.getRange(`A${headerRow}:B${endRow}`);
dataRange.values = data;
dataRange.format.font = { name: "Arial", size: 10, color: "#20242B" };
dataRange.format.verticalAlignment = "center";
dataRange.format.wrapText = false;
sheet.getRange(`A${headerRow}:B${headerRow}`).format = {
  fill: "#10233F",
  font: { name: "Arial", size: 10, bold: true, color: "#FFFFFF" },
  horizontalAlignment: "center",
  verticalAlignment: "center",
  borders: { insideVertical: { style: "thin", color: "#FFFFFF" } },
};
sheet.getRange(`A${headerRow}:B${headerRow}`).format.rowHeight = 24;
sheet.getRange(`A${headerRow + 1}:B${endRow}`).format.rowHeight = 20;
sheet.getRange(`A${headerRow + 1}:A${endRow}`).format.horizontalAlignment = "left";
sheet.getRange(`B${headerRow + 1}:B${endRow}`).format.horizontalAlignment = "left";
sheet.getRange("A:A").format.columnWidth = 52;
sheet.getRange("B:B").format.columnWidth = 78;

const table = sheet.tables.add(`A${headerRow}:B${endRow}`, true, "PublishedPagesTable");
table.style = "TableStyleMedium2";
table.showBandedColumns = false;
table.showFilterButton = true;
sheet.freezePanes.freezeRows(headerRow);

workbook.recalculate();
const inspection = await workbook.inspect({
  kind: "table",
  range: `Pages!A2:B${endRow}`,
  include: "values,formulas",
  tableMaxRows: rows.length + 5,
  tableMaxCols: 2,
  maxChars: 20000,
});
console.log(inspection.ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A|#NUM!|#NULL!|#SPILL!|#CALC!",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

await fs.mkdir(outputDir, { recursive: true });
const preview = await workbook.render({ sheetName: "Pages", range: `A1:B${endRow}`, scale: 1.2, format: "png" });
await fs.writeFile(previewPath, new Uint8Array(await preview.arrayBuffer()));
const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(outputPath);
console.log(JSON.stringify({ outputPath, previewPath, rowCount: rows.length, endRow }));
