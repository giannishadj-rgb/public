const fs = require('fs');
const path = require('path');
const { chromium } = require('@playwright/test');
const out = __dirname;
const routes = {home:'/',shop:'/shop/',cream:'/products/24h-regeneration-cream/',science:'/science/',heritage:'/heritage/'};
(async()=>{
 const browser = await chromium.launch();
 const manifest=[];
 for(const [device,viewport] of Object.entries({desktop:{width:1440,height:950},mobile:{width:390,height:844}})){
  const context=await browser.newContext({viewport,deviceScaleFactor:1,reducedMotion:'reduce'});
  for(const [name,route] of Object.entries(routes)){
   const page=await context.newPage();
   await page.goto('http://caviarofswitzerland.local'+route,{waitUntil:'networkidle'});
   await page.evaluate(async()=>{await document.fonts.ready;});
   const height=await page.evaluate(()=>document.documentElement.scrollHeight);
   for(let y=0;y<height;y+=viewport.height-80){await page.evaluate(y=>scrollTo(0,y),y);await page.waitForTimeout(100);}
   await page.evaluate(()=>scrollTo(0,0));await page.waitForTimeout(200);
   await page.screenshot({path:path.join(out,`${device}-${name}-full.png`),fullPage:true});
   const detail=await page.evaluate(()=>({title:document.title,height:document.documentElement.scrollHeight,width:document.documentElement.scrollWidth,headings:[...document.querySelectorAll('h1,h2,h3')].map(x=>({t:x.textContent.trim(),y:Math.round(x.getBoundingClientRect().top+scrollY),size:getComputedStyle(x).fontSize,font:getComputedStyle(x).fontFamily})),sections:[...document.querySelectorAll('main section')].map(x=>({cl:x.className,y:Math.round(x.getBoundingClientRect().top+scrollY),h:Math.round(x.getBoundingClientRect().height)}))}));
   manifest.push({device,name,route,...detail});
   let n=0;
   for(let y=0;y<height;y+=viewport.height-80){await page.evaluate(y=>scrollTo(0,y),y);await page.waitForTimeout(60);await page.screenshot({path:path.join(out,`${device}-${name}-${String(n++).padStart(2,'0')}.png`)});}
   await page.close();
  }
  await context.close();
 }
 fs.writeFileSync(path.join(out,'manifest.json'),JSON.stringify(manifest,null,2));
 await browser.close();
 console.log(manifest.map(x=>`${x.device} ${x.name}: ${x.height}px; width ${x.width}px`).join('\n'));
})();
