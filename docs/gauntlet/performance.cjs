// Local lab sample; not Lighthouse or production field data.
const { chromium } = require('playwright');
const fs = require('fs');
(async () => {
 const browser = await chromium.launch();
 const runs = [];
 for (let i = 0; i < 3; i++) {
  const context = await browser.newContext({ viewport: { width: 390, height: 844 }, deviceScaleFactor: 3, isMobile: true, hasTouch: true, serviceWorkers: 'block' });
  const page = await context.newPage();
  await page.addInitScript(() => {
   window.__gauntletPerf = { lcp: 0, cls: 0 };
   new PerformanceObserver(es => { for (const e of es.getEntries()) window.__gauntletPerf.lcp = e.startTime; }).observe({ type: 'largest-contentful-paint', buffered: true });
   new PerformanceObserver(es => { for (const e of es.getEntries()) if (!e.hadRecentInput) window.__gauntletPerf.cls += e.value; }).observe({ type: 'layout-shift', buffered: true });
  });
  const cdp = await context.newCDPSession(page);
  await cdp.send('Network.enable');
  await cdp.send('Network.setCacheDisabled', { cacheDisabled: true });
  await cdp.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 200000, uploadThroughput: 93750, connectionType: 'cellular4g' });
  await cdp.send('Emulation.setCPUThrottlingRate', { rate: 4 });
  await page.goto('http://caviarofswitzerland.local/', { waitUntil: 'load' });
  await page.waitForTimeout(5000);
  runs.push(await page.evaluate(() => window.__gauntletPerf));
  await context.close();
 }
 await browser.close();
 const result = { date: '2026-10-18', environment: 'Local Chromium, cold cache, 390x844 DPR3, 1.6Mbps / 150ms RTT / 4x CPU, initial viewport', runs, medianLcpMs: runs.map(r => r.lcp).sort((a,b) => a-b)[1], maxCls: Math.max(...runs.map(r => r.cls)) };
 fs.writeFileSync(__dirname + '/performance-results.json', JSON.stringify(result, null, 2));
 console.log(JSON.stringify(result));
})();
