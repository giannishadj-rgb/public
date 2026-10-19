// QA only: decode all native lazy images before full-page capture.
// Merely waiting for `complete` leaves asynchronous offscreen images unpainted.
const { chromium } = require('playwright');
const fs = require('fs');
(async () => {
 const browser = await chromium.launch();
 const summary = [];
 for (const width of [390, 1440]) {
  const page = await browser.newPage({ viewport: { width, height: width === 390 ? 844 : 950 } });
  await page.goto('http://caviarofswitzerland.local/');
  await page.locator('img').evaluateAll(async images => {
   images.forEach(image => image.loading = 'eager');
   await Promise.all(images.map(image => image.decode()));
  });
  await page.evaluate(() => document.fonts.ready);
  await page.waitForTimeout(1300);
  await page.screenshot({ path: `${__dirname}/screenshots/latest-${width}.png`, fullPage: true });
  summary.push(await page.evaluate(() => ({
   width: innerWidth, height: document.documentElement.scrollHeight,
   sections: [...document.querySelectorAll('main section')].map(s => ({ id: s.id, height: Math.round(s.getBoundingClientRect().height) })),
   images: [...document.querySelectorAll('main img')].map(i => ({ src: i.currentSrc, loaded: i.complete && i.naturalWidth > 0 }))
  })));
  await page.close();
 }
 await browser.close();
 fs.writeFileSync(__dirname + '/capture-results.json', JSON.stringify(summary, null, 2));
 console.log(summary.map(s => ({ width: s.width, height: s.height, imagesLoaded: s.images.every(i => i.loaded) })));
})();
