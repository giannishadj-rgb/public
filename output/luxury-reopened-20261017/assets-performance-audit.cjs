const {chromium}=require('playwright');
const fs=require('fs');
const path=require('path');
const out=__dirname;
const prefix=process.env.AUDIT_PREFIX||'';
const observer=()=>{
  window.__audit={lcp:[],shifts:[],longTasks:[]};
  new PerformanceObserver(list=>{for(const e of list.getEntries())window.__audit.lcp.push({startTime:e.startTime,renderTime:e.renderTime,loadTime:e.loadTime,size:e.size,url:e.url,element:e.element?{tag:e.element.tagName,cls:e.element.className,id:e.element.id,src:e.element.currentSrc||null,text:e.element.textContent?.slice(0,180)}:null});}).observe({type:'largest-contentful-paint',buffered:true});
  new PerformanceObserver(list=>{for(const e of list.getEntries())window.__audit.shifts.push({value:e.value,startTime:e.startTime,hadRecentInput:e.hadRecentInput,sources:e.sources?.map(s=>({node:s.node?{tag:s.node.tagName,cls:s.node.className,id:s.node.id,text:s.node.textContent?.slice(0,100)}:null,previous:s.previousRect,current:s.currentRect}))});}).observe({type:'layout-shift',buffered:true});
  new PerformanceObserver(list=>{for(const e of list.getEntries())window.__audit.longTasks.push({startTime:e.startTime,duration:e.duration,name:e.name});}).observe({type:'longtask',buffered:true});
};
(async()=>{
 const browser=await chromium.launch({headless:true});
 const all=[];
 for(let i=1;i<=3;i++){
  const context=await browser.newContext({viewport:{width:390,height:844},deviceScaleFactor:3,isMobile:true,hasTouch:true,ignoreHTTPSErrors:true,serviceWorkers:'block',userAgent:'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36'});
  const page=await context.newPage();
  await page.addInitScript(observer);
  const cdp=await context.newCDPSession(page);
  await cdp.send('Network.enable');
  await cdp.send('Network.setCacheDisabled',{cacheDisabled:true});
  await cdp.send('Network.emulateNetworkConditions',{offline:false,latency:150,downloadThroughput:1600000/8,uploadThroughput:750000/8,connectionType:'cellular4g'});
  await cdp.send('Emulation.setCPUThrottlingRate',{rate:4});
  const requests=new Map();
  cdp.on('Network.requestWillBeSent',e=>requests.set(e.requestId,{id:e.requestId,url:e.request.url,type:e.type,start:e.timestamp,priority:e.request.initialPriority,initiator:e.initiator.type,redirect:e.redirectResponse?.status}));
  cdp.on('Network.responseReceived',e=>{const r=requests.get(e.requestId);if(r)Object.assign(r,{status:e.response.status,mime:e.response.mimeType,headers:e.response.headers,protocol:e.response.protocol,fromDiskCache:e.response.fromDiskCache,responseTime:e.timestamp,timing:e.response.timing});});
  cdp.on('Network.loadingFinished',e=>{const r=requests.get(e.requestId);if(r)Object.assign(r,{end:e.timestamp,encodedDataLength:e.encodedDataLength});});
  cdp.on('Network.loadingFailed',e=>{const r=requests.get(e.requestId);if(r)Object.assign(r,{error:e.errorText,canceled:e.canceled});});
  const errors=[];page.on('pageerror',e=>errors.push(e.message));
  let navigationError=null;
  try{await page.goto('http://caviarofswitzerland.local/',{waitUntil:'load',timeout:65000});}catch(e){navigationError=e.message;}
  await page.waitForTimeout(12000);
  const result=await page.evaluate(()=>({audit:window.__audit,navigation:performance.getEntriesByType('navigation').map(e=>e.toJSON()),paint:performance.getEntriesByType('paint').map(e=>e.toJSON()),resources:performance.getEntriesByType('resource').map(e=>e.toJSON()),images:[...document.images].map(e=>({src:e.currentSrc,width:e.width,height:e.height,naturalWidth:e.naturalWidth,naturalHeight:e.naturalHeight,loading:e.loading,fetchPriority:e.fetchPriority,complete:e.complete,alt:e.alt,rect:e.getBoundingClientRect().toJSON()})),headStyles:[...document.querySelectorAll('link[rel="stylesheet"]')].map(e=>e.href),preloads:[...document.querySelectorAll('link[rel="preload"]')].map(e=>({href:e.href,as:e.as,media:e.media,imagesrcset:e.imageSrcset,imagesizes:e.imageSizes})),fonts:[...document.fonts].map(e=>({family:e.family,status:e.status,weight:e.weight})),location:location.href,viewport:{width:innerWidth,height:innerHeight,dpr:devicePixelRatio},scrollWidth:document.documentElement.scrollWidth}));
  let windowStart=0,session=0,maximum=0,last=0;
  for(const e of result.audit.shifts.filter(e=>!e.hadRecentInput)){
    if(e.startTime-last>1000||e.startTime-windowStart>5000){session=e.value;windowStart=e.startTime;}else session+=e.value;
    maximum=Math.max(maximum,session);last=e.startTime;
  }
  result.summary={run:i,CLS:maximum,CLSsum:result.audit.shifts.filter(e=>!e.hadRecentInput).reduce((s,e)=>s+e.value,0),LCP:result.audit.lcp.at(-1)?.startTime,FCP:result.paint.find(e=>e.name==='first-contentful-paint')?.startTime,TTFB:result.navigation[0]?.responseStart,totalEncodedBytes:[...requests.values()].reduce((s,r)=>s+(r.encodedDataLength||0),0),requestCount:requests.size,errors,navigationError};
  result.requests=[...requests.values()];
  fs.writeFileSync(path.join(out,`${prefix}mobile-cold-${i}.json`),JSON.stringify(result,null,2));
  if(i===1)await page.screenshot({path:path.join(out,`${prefix}mobile-cold-first-viewport.png`)});
  all.push(result.summary);console.log(JSON.stringify(result.summary));
  await context.close();
 }
 fs.writeFileSync(path.join(out,`${prefix}mobile-cold-summary.json`),JSON.stringify({conditions:{network:'CDP real-time throttling: 1.6 Mbps down, 0.75 Mbps up, 150 ms request latency',cpu:'4x slowdown',cache:'disabled in each new browser context',viewport:'390x844 DPR 3',sample:'3 local cold browser runs, no scroll or interaction; 12s settle after load',server:'Local WP; server OS/opcache may be warm; not a production field or lab speed claim'},runs:all},null,2));
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
