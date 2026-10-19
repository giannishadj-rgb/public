const { chromium }=require('@playwright/test');
const fs=require('fs');
(async()=>{
 const browser=await chromium.launch({headless:true});
 const out='output/luxury-reopened-20261017';
 for(const v of [{name:'desktop',width:1440,height:950},{name:'mobile',width:390,height:844}]) {
  const page=await browser.newPage({viewport:{width:v.width,height:v.height},deviceScaleFactor:1});
  await page.goto('http://caviarofswitzerland.local/',{waitUntil:'networkidle'});
  await page.evaluate(()=>document.fonts.ready);
  await page.waitForTimeout(1400);
  await page.screenshot({path:`${out}/${v.name}-first.png`});
  await page.screenshot({path:`${out}/${v.name}-full.png`,fullPage:true});
  const data=await page.evaluate(()=>({title:document.title,text:document.body.innerText,sections:[...document.querySelectorAll('main section')].map(x=>({id:x.id,class:x.className,y:Math.round(x.getBoundingClientRect().y+scrollY),height:Math.round(x.getBoundingClientRect().height),heading:x.querySelector('h1,h2')?.innerText})),links:[...document.querySelectorAll('main a')].map(x=>({text:x.innerText,url:x.href})),overflow:document.documentElement.scrollWidth-innerWidth}));
  fs.writeFileSync(`${out}/${v.name}-observations.json`,JSON.stringify(data,null,2));
  for(const s of data.sections){if(!s.id)continue; await page.locator('#'+s.id).screenshot({path:`${out}/${v.name}-${s.id}.png`});}
  await page.close();
 }
 await browser.close();
})();
