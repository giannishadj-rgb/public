const{webkit,devices}=require('playwright');const fs=require('fs'),path=require('path');const out=path.join(__dirname,'mobile-artifacts');
(async()=>{const b=await webkit.launch();const context=await b.newContext({...devices['iPhone 13']});const results={};
for(const[name,route]of Object.entries({home:'/',shop:'/shop/',ritual:'/the-ritual/'})){
const p=await context.newPage();await p.goto('http://caviarofswitzerland.local'+route);await p.evaluate(()=>document.fonts.ready);
for(let y=0;y<await p.evaluate(()=>document.documentElement.scrollHeight);y+=550){await p.evaluate(y=>scrollTo(0,y),y);await p.waitForTimeout(70)}await p.waitForTimeout(600);await p.evaluate(()=>scrollTo(0,0));
await p.screenshot({path:path.join(out,`loaded-mobile-${name}-full.png`),fullPage:true,scale:'css'});
for(const y of name==='home'?[720,1100,2050,3300,4000]:name==='shop'?[600,1200,2000,2400]:[640,1500,5800,7100]){await p.evaluate(y=>scrollTo(0,y),y);await p.screenshot({path:path.join(out,`loaded-mobile-${name}-${y}.png`),scale:'css'})}
await p.evaluate(()=>{const all=[...document.querySelectorAll('body,body *')].map(e=>({e,f:parseFloat(getComputedStyle(e).fontSize),l:parseFloat(getComputedStyle(e).lineHeight)}));for(const{e,f,l}of all){e.style.setProperty('font-size',f*2+'px','important');if(Number.isFinite(l))e.style.setProperty('line-height',l*2+'px','important')}});
const selector=name==='home'?'.hx-collection':name==='shop'?'.hxshop-products':'.hxrt-index';
const heading=await p.locator('h3').first();if(await heading.count()){await heading.scrollIntoViewIfNeeded();await p.screenshot({path:path.join(out,`loaded-text200-${name}-first-products.png`),scale:'css'})}
results[name]=await p.evaluate(()=>({width:document.documentElement.scrollWidth,visibleOverflow:[...document.querySelectorAll('main h1, main h2,main h3,main p,main a')].filter(e=>{const r=e.getBoundingClientRect();return r.width>0&&(r.x<0||r.right>innerWidth+.5)}).map(e=>({text:e.textContent.trim().slice(0,120),class:e.className,r:e.getBoundingClientRect().toJSON()}))}));await p.close();}
fs.writeFileSync(path.join(out,'review-details.json'),JSON.stringify(results,null,2));await b.close();console.log(JSON.stringify(results,null,2));})();
