import { chromium, devices } from '@playwright/test';

const base = 'http://caviarofswitzerland.local';
const routes = [
  ['home', '/'],
  ['shop', '/shop/'],
  ['pdp', '/products/advanced-repair-serum/'],
];

const browser = await chromium.launch();
for (const [mode, options] of [
  ['desktop', { viewport: { width: 1440, height: 950 } }],
  ['mobile', { ...devices['iPhone 13'] }],
]) {
  const context = await browser.newContext({ ...options, reducedMotion: 'reduce' });
  for (const [name, path] of routes) {
    const page = await context.newPage();
    await page.goto(base + path, { waitUntil: 'networkidle' });
    await page.addStyleTag({ content: `
      *, *::before, *::after { animation: none !important; transition: none !important; }
      [data-hx-reveal], .hx-load-rise { opacity: 1 !important; transform: none !important; clip-path: none !important; }
    ` });
    await page.evaluate(async () => {
      await document.fonts.ready;
      for (let y = 0; y < document.documentElement.scrollHeight; y += 700) {
        window.scrollTo(0, y);
        await new Promise(resolve => setTimeout(resolve, 80));
      }
      window.scrollTo(0, 0);
    });
    await page.waitForTimeout(300);
    await page.screenshot({ path: `tmp/site-review/stable-${name}-${mode}.png`, fullPage: true });
    await page.close();
  }
  await context.close();
}
await browser.close();
