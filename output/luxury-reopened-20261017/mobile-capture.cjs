const { webkit, chromium, devices } = require('playwright');
const fs = require('fs');
const path = require('path');
const out = path.join(__dirname, 'mobile-artifacts');
fs.mkdirSync(out, { recursive: true });
const routes = { home: '/', shop: '/shop/', cream: '/products/24h-regeneration-cream/', ritual: '/the-ritual/' };
async function details(page) {
  return await page.evaluate(() => {
    const rect = e => { const r=e.getBoundingClientRect(); return {x:r.x,y:r.y+scrollY,w:r.width,h:r.height}; };
    const visible = e => e.getBoundingClientRect().width && getComputedStyle(e).visibility !== 'hidden' && getComputedStyle(e).display !== 'none';
    return {title:document.title, url:location.href, viewport:{w:innerWidth,h:innerHeight}, width:document.documentElement.scrollWidth,height:document.documentElement.scrollHeight,
      headings:[...document.querySelectorAll('h1,h2,h3')].filter(visible).map(e=>({tag:e.tagName,text:e.textContent.trim(),...rect(e)})),
      smallText:[...document.querySelectorAll('main p,main li,main a,main button,main label,main span')].filter(e=>visible(e)&&e.textContent.trim()&&parseFloat(getComputedStyle(e).fontSize)<15).slice(0,80).map(e=>({tag:e.tagName,class:e.className,text:e.textContent.trim().slice(0,200),font:getComputedStyle(e).fontSize,...rect(e)})),
      controls:[...document.querySelectorAll('a,button,input,summary,select')].filter(visible).map(e=>({tag:e.tagName,text:e.innerText?.trim().slice(0,100),label:e.getAttribute('aria-label'),...rect(e)})),
      text:document.body.innerText};
  });
}
(async()=>{
const results={};
for(const device of ['mobile','desktop','reflow']){
 const browser = await (device==='desktop'?chromium:webkit).launch({headless:true});
 const context=await browser.newContext(device==='mobile'?{...devices['iPhone 13']}:{viewport:device==='desktop'?{width:1440,height:1000}:{width:720,height:500}});
 for(const [name,route] of Object.entries(routes)){
   const page=await context.newPage();
   await page.goto('http://caviarofswitzerland.local'+route,{waitUntil:'networkidle'});
   await page.evaluate(()=>document.fonts.ready); await page.waitForTimeout(1200);
   await page.screenshot({path:path.join(out,`${device}-${name}-top.png`),scale:'css'});
   await page.screenshot({path:path.join(out,`${device}-${name}-full.png`),fullPage:true,scale:'css'});
   results[`${device}-${name}`]=await details(page);
   if(device==='mobile'){
     await page.evaluate(()=>{
       const elements=[...document.querySelectorAll('body,body *')];
       const sizes=elements.map(e=>{const s=getComputedStyle(e);return {e,f:parseFloat(s.fontSize),l:parseFloat(s.lineHeight)}});
       for(const {e,f,l} of sizes){e.style.setProperty('font-size',`${f*2}px`,'important');if(Number.isFinite(l))e.style.setProperty('line-height',`${l*2}px`,'important');}
     });
     await page.screenshot({path:path.join(out,`text200-${name}-top.png`),scale:'css'});
     await page.screenshot({path:path.join(out,`text200-${name}-full.png`),fullPage:true,scale:'css'});
     results[`text200-${name}`]=await details(page);
   }
   await page.close();
 }
 await browser.close();
}
fs.writeFileSync(path.join(out,'evidence.json'),JSON.stringify(results,null,2));
console.log(JSON.stringify(Object.fromEntries(Object.entries(results).map(([k,v])=>[k,{width:v.width,viewport:v.viewport,height:v.height,headings:v.headings}])),null,2));
})();

