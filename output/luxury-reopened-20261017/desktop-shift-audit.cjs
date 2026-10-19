const{chromium}=require('playwright');const fs=require('fs');const path=require('path');
(async()=>{
 const browser=await chromium.launch({headless:true});const context=await browser.newContext({viewport:{width:1440,height:960},deviceScaleFactor:1,serviceWorkers:'block',ignoreHTTPSErrors:true});const page=await context.newPage();
 await page.addInitScript(()=>{window.__shifts=[];window.__phases=[];new PerformanceObserver(l=>l.getEntries().forEach(e=>window.__shifts.push({time:e.startTime,value:e.value,recentInput:e.hadRecentInput,scrollY,sources:e.sources.map(s=>({node:s.node?.tagName+'.'+s.node?.className,outer:s.node?.outerHTML?.slice(0,500),previous:s.previousRect,current:s.currentRect}))}))).observe({type:'layout-shift',buffered:true})});
 const cdp=await context.newCDPSession(page);await cdp.send('Network.enable');await cdp.send('Network.setCacheDisabled',{cacheDisabled:true});await cdp.send('Network.emulateNetworkConditions',{offline:false,latency:150,downloadThroughput:200000,uploadThroughput:93750,connectionType:'cellular4g'});await cdp.send('Emulation.setCPUThrottlingRate',{rate:4});
 await page.goto('http://caviarofswitzerland.local/',{waitUntil:'load'});
 for(const selector of ['.hxh','#collection','.hxv','#science','#heritage','#ritual','#closing','footer']){
  const el=page.locator(selector).first();if(await el.count()){
   await page.evaluate(s=>window.__phases.push({selector:s,time:performance.now(),scrollY,before:[...document.querySelectorAll('#heritage,.hxp__study,.hxp__study img,#ritual,.hxr__portrait,.hxr__portrait img')].map(e=>({cls:e.className,rect:e.getBoundingClientRect().toJSON(),complete:e.complete,src:e.currentSrc}))}),selector);
   await el.scrollIntoViewIfNeeded();await page.waitForTimeout(1600);
  }
 }
 await page.waitForTimeout(3500);const data=await page.evaluate(()=>({shifts:window.__shifts,phases:window.__phases}));fs.writeFileSync(path.join(__dirname,'desktop-shift-detail.json'),JSON.stringify(data,null,2));console.log(JSON.stringify(data.shifts));await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
