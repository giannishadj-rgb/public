const { webkit, chromium, devices } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const out = __dirname;
const base = 'http://caviarofswitzerland.local/';
async function inspect(page,label){
 await page.waitForLoadState('networkidle').catch(()=>{});
 await page.screenshot({path:path.join(out,label+'.png'),fullPage:true});
 const data=await page.evaluate(()=>({url:location.href,title:document.title,body:document.body.innerText,links:[...document.querySelectorAll('a')].map(a=>({text:a.innerText.trim(),aria:a.getAttribute('aria-label'),href:a.getAttribute('href')})),buttons:[...document.querySelectorAll('button')].map(a=>({text:a.innerText.trim(),aria:a.getAttribute('aria-label'),type:a.type})),viewport:{w:innerWidth,sw:document.documentElement.scrollWidth,h:innerHeight}}));
 fs.writeFileSync(path.join(out,label+'.json'),JSON.stringify(data,null,2));
 return data;
}
(async()=>{
 fs.mkdirSync(out,{recursive:true});
 const browser=await webkit.launch();
 const context=await browser.newContext({...devices['iPhone 13'],viewport:{width:390,height:844},reducedMotion:'reduce'});
 const page=await context.newPage();
 await page.goto(base);
 const h=await inspect(page,'m390-home');
 console.log(JSON.stringify({stage:'home',...h},null,2));
 await context.storageState({path:path.join(out,'session.json')});
 await browser.close();
})();
