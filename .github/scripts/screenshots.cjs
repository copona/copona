// Captures README screenshots of a running demo store.
// Usage: node .github/scripts/screenshots.cjs http://127.0.0.1:8000 docs/screenshots
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const base = (process.argv[2] || 'http://127.0.0.1:8000').replace(/\/$/, '');
const outDir = process.argv[3] || 'docs/screenshots';

const storefront = [
  { name: 'home', url: '/' },
  { name: 'category', url: '/index.php?route=product/category&path=100' },
  { name: 'product', url: '/index.php?route=product/product&product_id=4' },
];

(async () => {
  fs.mkdirSync(outDir, { recursive: true });
  const browser = await chromium.launch();

  const shoot = async (page, url, file) => {
    await page.goto(base + url, { waitUntil: 'networkidle' });
    await page.screenshot({ path: path.join(outDir, file) });
    console.log('saved', file);
  };

  // Storefront first: once the admin is logged in, the catalog shows the admin bar.
  const desktop = await browser.newPage({ viewport: { width: 1440, height: 900 } });
  for (const shot of storefront) {
    await shoot(desktop, shot.url, `${shot.name}.png`);
  }

  const mobile = await browser.newPage({ viewport: { width: 390, height: 844 }, deviceScaleFactor: 2, isMobile: true });
  await shoot(mobile, storefront[2].url, 'product-mobile.png');

  await desktop.goto(base + '/admin/', { waitUntil: 'networkidle' });
  await desktop.fill('input[name="username"]', process.env.ADMIN_USERNAME || 'admin');
  await desktop.fill('input[name="password"]', process.env.ADMIN_PASSWORD || 'admin123');
  await Promise.all([
    desktop.waitForNavigation({ waitUntil: 'networkidle' }),
    desktop.click('button[type="submit"]'),
  ]);
  await desktop.screenshot({ path: path.join(outDir, 'admin-dashboard.png') });
  console.log('saved admin-dashboard.png');

  await browser.close();
})().catch((err) => {
  console.error(err);
  process.exit(1);
});
