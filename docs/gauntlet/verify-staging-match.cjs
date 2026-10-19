const {chromium}=require('playwright');const fs=require('fs');
(async()=>{let b=await chromium.launch();let report=[];
for(let width of [390,1440])for(let[name,url]of[['staging','https://staging.caviarofswitzerland.com/'],['local','http://caviarofswitzerland.local/']]){
console.log('Checking',name,width);let p=await b.newPage({viewport:{width,height:1000},reducedMotion:'reduce'});let errors=[];p.on('pageerror',e=>errors.push(e.message));p.on('response',r=>{if(r.status()>=400)errors.push(r.status()+' '+r.url())});await p.goto(url,{waitUntil:'domcontentloaded'});
await p.evaluate(async()=>{await Promise.race([document.fonts.ready,new Promise(r=>setTimeout(r,3000))]);for(let y=0,h=Math.min(20000,document.body.scrollHeight);y<h;y+=700){scrollTo(0,y);await new Promise(r=>setTimeout(r,80))}await Promise.race([Promise.all([...document.images].map(i=>i.decode().catch(()=>{}))),new Promise(r=>setTimeout(r,5000))]);scrollTo(0,0)});await p.waitForTimeout(600);
await p.screenshot({path:`docs/gauntlet/screenshots/sync-${name}-${width}.png`,fullPage:true});
let state=await p.evaluate(()=>({title:document.title,text:document.querySelector('main').innerText,sections:[...document.querySelectorAll('main>section')].map(e=>({id:e.id,cls:e.className,height:e.offsetHeight,top:e.offsetTop})),height:document.body.scrollHeight,overflow:document.documentElement.scrollWidth>innerWidth,images:[...document.querySelectorAll('img')].filter(e=>!e.complete||e.naturalWidth===0).map(e=>e.src),links:[...document.querySelectorAll('main a')].map(e=>({text:e.textContent.trim(),href:e.getAttribute('href')}))}));report.push({name,width,errors,...state});await p.close();}
fs.writeFileSync('docs/gauntlet/staging-match-qa.json',JSON.stringify(report,null,2));console.log(JSON.stringify(report.map(({text,links,...r})=>r),null,2));await b.close()})();


