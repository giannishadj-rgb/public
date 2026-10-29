import { chromium } from 'playwright';
const S = process.argv[2]; const pages = process.argv.slice(3);
const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
for (const u of pages) {
  await p.goto('http://caviarofswitzerland.local/' + u, { waitUntil: 'networkidle' }).catch(()=>{});
  await p.evaluate(()=>document.querySelectorAll('img[loading=lazy]').forEach(i=>i.loading='eager'));
  await p.waitForTimeout(1500);
  await p.addStyleTag({content:'[data-hx-reveal],.hx-reveal{opacity:1!important;transform:none!important;clip-path:none!important}'});
  const h = await p.evaluate(async () => { for (let y=0;y<document.body.scrollHeight;y+=700){window.scrollTo(0,y);await new Promise(r=>setTimeout(r,80));} window.scrollTo(0,0); return document.body.scrollHeight; });
  await p.waitForTimeout(800);
  const slug = (u.replace(/\//g,'_')||'home');
  for (let y=0, i=0; y < h && i < 8; y += 1800, i++) await p.screenshot({ path: `${S}/full/${slug}-${i}.png`, fullPage: true, clip: { x:0, y, width:1440, height: Math.min(1800, h-y) } });
}
await b.close();
