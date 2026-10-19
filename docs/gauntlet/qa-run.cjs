const {chromium}=require('playwright');
const AxeBuilder=require('@axe-core/playwright').default;
const fs=require('fs');
(async()=>{
const browser=await chromium.launch();const result={date:new Date().toISOString(),viewports:[],errors:[],networkFailures:[]};
for(const width of [360,390,430,768,1024,1440,1920]){
 const context=await browser.newContext({viewport:{width,height:width<600?844:950},reducedMotion:'reduce'}); const page=await context.newPage();
 page.on('pageerror',e=>result.errors.push({width,message:e.message}));
 page.on('console',m=>{if(m.type()==='error')result.errors.push({width,message:m.text()})});
 page.on('response',r=>{if(r.status()>=400)result.networkFailures.push({width,status:r.status(),url:r.url()})});
 await page.goto('http://caviarofswitzerland.local/',{waitUntil:'networkidle'});
 await page.evaluate(async()=>{await document.fonts.ready;for(let y=0;y<document.body.scrollHeight;y+=700){window.scrollTo(0,y);await new Promise(r=>setTimeout(r,40))}window.scrollTo(0,0)});
 const layout=await page.evaluate(()=>({scrollWidth:document.documentElement.scrollWidth,viewport:innerWidth,overflow:[...document.querySelectorAll('main *')].filter(e=>{let r=e.getBoundingClientRect();return r.width&& (r.right>innerWidth+1||r.left< -1)}).slice(0,20).map(e=>({tag:e.tagName,class:e.className,left:e.getBoundingClientRect().left,right:e.getBoundingClientRect().right})),brokenImages:[...document.images].filter(e=>e.complete&&!e.naturalWidth).map(e=>e.src),h1:[...document.querySelectorAll('h1')].map(e=>e.innerText),hero:[...document.querySelectorAll('.hxh__title,.hxh__lead,.hxh__cta,.hxh__steps')].map(e=>({class:e.className,rect:e.getBoundingClientRect().toJSON(),text:e.innerText})),smallTargets:[...document.querySelectorAll('main a,main button')].filter(e=>{let r=e.getBoundingClientRect();return r.width&&r.height<24}).map(e=>({text:e.innerText,height:e.getBoundingClientRect().height})),motion:[...document.querySelectorAll('main *')].filter(e=>{let s=getComputedStyle(e);return s.animationName!=='none'&&parseFloat(s.animationDuration)>.01}).slice(0,8).map(e=>e.className)}));
 const a11y=await new AxeBuilder({page}).include('main').withTags(['wcag2a','wcag2aa','wcag21aa']).analyze();
 result.viewports.push({width,...layout,axe:a11y.violations.map(v=>({id:v.id,impact:v.impact,description:v.description,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))}))});
 if(width===390){
 await page.getByRole('button',{name:'Open navigation menu'}).click();
 result.navigation={afterOpen:await page.evaluate(()=>({buttons:[...document.querySelectorAll('button')].map(e=>({text:e.innerText,aria:e.getAttribute('aria-label'),expanded:e.getAttribute('aria-expanded')})),bodyOverflow:getComputedStyle(document.body).overflow,active:document.activeElement.outerHTML}))};
 await page.keyboard.press('Escape'); result.navigation.afterEscape=await page.getByRole('button',{name:/navigation menu/}).getAttribute('aria-expanded');
 }
 await page.close();
}
const page=await browser.newPage();await page.goto('http://caviarofswitzerland.local/');
result.links=await page.locator('main a').evaluateAll(es=>es.map(e=>({text:e.innerText.trim(),href:e.href}))); result.routes=[];
for(const href of [...new Set(result.links.map(x=>x.href))].filter(x=>x.startsWith('http://caviarofswitzerland.local/'))){const r=await page.request.get(href);result.routes.push({href,status:r.status()})}
await page.keyboard.press('Tab');result.firstFocus=await page.evaluate(()=>({text:document.activeElement.innerText,html:document.activeElement.outerHTML}));
fs.mkdirSync('docs/gauntlet',{recursive:true});fs.writeFileSync('docs/gauntlet/technical-results.json',JSON.stringify(result,null,2));
console.log(JSON.stringify({viewports:result.viewports.map(x=>({width:x.width,overflow:x.scrollWidth>x.viewport,axe:x.axe,brokenImages:x.brokenImages,smallTargets:x.smallTargets,motion:x.motion})),errors:result.errors,networkFailures:result.networkFailures,navigation:result.navigation,routes:result.routes,firstFocus:result.firstFocus},null,2));await browser.close();
})();

