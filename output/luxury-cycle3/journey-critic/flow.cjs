const { webkit, chromium, devices } = require('@playwright/test');
const fs=require('fs'),path=require('path');
const out=__dirname,base='http://caviarofswitzerland.local/';
const log=[];
async function ready(p){await p.waitForLoadState('networkidle').catch(()=>{});await p.waitForTimeout(300);}
async function shot(p,n){await p.screenshot({path:path.join(out,n+'.png')});}
async function record(p,n){
 const data=await p.evaluate(()=>{const visible=e=>{const r=e.getBoundingClientRect(),s=getComputedStyle(e);return r.width>0&&r.height>0&&s.visibility!=='hidden'&&s.display!=='none'};const rect=e=>{let r=e.getBoundingClientRect();return{x:r.x,y:r.y,w:r.width,h:r.height}};return{url:location.href,title:document.title,body:document.body.innerText,viewport:{w:innerWidth,h:innerHeight,sw:document.documentElement.scrollWidth},controls:[...document.querySelectorAll('a,button,input,select,summary,textarea')].filter(visible).map(e=>({tag:e.tagName,text:e.innerText.trim().slice(0,150),aria:e.getAttribute('aria-label'),role:e.getAttribute('role'),type:e.getAttribute('type'),name:e.getAttribute('name'),href:e.getAttribute('href'),rect:rect(e),font:getComputedStyle(e).fontSize,focus:e.tabIndex,disabled:e.disabled,required:e.required,autocomplete:e.autocomplete}))}});
 fs.writeFileSync(path.join(out,n+'.json'),JSON.stringify(data,null,2));log.push({stage:n,url:data.url,viewport:data.viewport});return data;
}
async function scrollLoad(p){await p.evaluate(async()=>{for(let y=0;y<document.body.scrollHeight;y+=window.innerHeight*.8){scrollTo(0,y);await new Promise(r=>setTimeout(r,120));}});await p.waitForTimeout(600);await p.evaluate(()=>scrollTo(0,0));}
(async()=>{
 const browser=await webkit.launch();
 const c=await browser.newContext({...devices['iPhone 13'],viewport:{width:390,height:844}});
 const p=await c.newPage();p.on('pageerror',e=>log.push({pageerror:e.message}));
 await p.goto(base);await ready(p);await scrollLoad(p);await shot(p,'m390-home-top');
 await p.getByRole('button',{name:'Open navigation menu'}).click();await shot(p,'m390-menu');
 await p.locator('#mobile-menu a[href="/shop/"]').click().catch(async()=>{await p.getByRole('link',{name:'Shop',exact:true}).filter({visible:true}).click()});
 await ready(p);await scrollLoad(p);console.log('SHOP',JSON.stringify(await record(p,'m390-shop')));await shot(p,'m390-shop-top');
 await p.locator('a[href*="/products/24h-regeneration-cream/"]').first().click();await ready(p);await scrollLoad(p);console.log('PDP',JSON.stringify(await record(p,'m390-cream')));await shot(p,'m390-cream-top');
 await p.getByRole('button',{name:/add to (bag|cart)/i}).first().scrollIntoViewIfNeeded();await shot(p,'m390-cream-purchase');
 await p.getByRole('button',{name:/add to (bag|cart)/i}).first().click();await ready(p);console.log('ADD',JSON.stringify(await record(p,'m390-after-add')));await shot(p,'m390-after-add');
 await c.storageState({path:path.join(out,'cart-session.json')});
 console.log('LOG',JSON.stringify(log));await browser.close();
})();
