const { chromium } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const out = __dirname;
const routes = [
 ['science', '/science/'],
 ['clinical-studies', '/science/clinical-studies/'],
 ['swiss-formulation', '/science/swiss-formulation/'],
 ['heritage', '/heritage/'],
 ['craftsmanship', '/heritage/craftsmanship/'],
 ['ritual', '/the-ritual/']
];
(async () => {
 const browser = await chromium.launch({headless: true});
 const report = [];
 for (const [device, viewport] of [['desktop',{width:1440,height:1000}], ['mobile',{width:390,height:844}]]) {
  const context = await browser.newContext({viewport,deviceScaleFactor:1,reducedMotion:'reduce',isMobile:device==='mobile',hasTouch:device==='mobile'});
  for (const [name, route] of routes) {
   const page = await context.newPage();
   const response = await page.goto('http://caviarofswitzerland.local'+route,{waitUntil:'networkidle'});
   await page.evaluate(() => document.fonts.ready);
   await page.screenshot({path:path.join(out,`${name}-${device}-top.png`)});
   for (let y=0;y<await page.evaluate(()=>document.body.scrollHeight);y+=700) { await page.evaluate(y=>window.scrollTo(0,y),y); await page.waitForTimeout(100); }
   await page.evaluate(()=>window.scrollTo(0,0));
   await page.screenshot({path:path.join(out,`${name}-${device}-full.png`),fullPage:true});
   const data=await page.evaluate(()=>({title:document.title,text:document.querySelector('main').innerText,html:document.querySelector('main').innerHTML,height:document.body.scrollHeight,scrollWidth:document.documentElement.scrollWidth,images:[...document.querySelectorAll('main img')].map(i=>({src:i.currentSrc,alt:i.alt,width:i.getBoundingClientRect().width,height:i.getBoundingClientRect().height,naturalWidth:i.naturalWidth,naturalHeight:i.naturalHeight}))}));
   report.push({device,name,route,status:response.status(),...data});
   console.log(`${device} ${route} ${response.status()} ${data.height}px ${data.scrollWidth}px wide`);
   await page.close();
  }
  await context.close();
 }
 fs.writeFileSync(path.join(out,'rendered-pages.json'),JSON.stringify(report,null,2));
 await browser.close();
})();
