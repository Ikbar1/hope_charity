const { chromium } = require(process.env.HOPE_PLAYWRIGHT || 'playwright');
const fs = require('node:fs');
const path = require('node:path');
const assert = require('node:assert/strict');
(async () => {
    const root = path.resolve(process.argv[2]);
    const base = 'https://ikbar1.github.io/hope_charity/';
    const files = url => {
        const pathname = decodeURIComponent(new URL(url).pathname).replace(/^\/hope_charity\//, '');
        const file = path.join(root, pathname);
        assert.ok(file.startsWith(root + path.sep) || file === root);
        return fs.statSync(file).isDirectory() ? path.join(file, 'index.html') : file;
    };
    const browser = await chromium.launch({ channel: 'msedge' });
    const page = await browser.newPage();
    const mime = { '.html': 'text/html', '.css': 'text/css', '.js': 'application/javascript', '.png': 'image/png', '.jpg': 'image/jpeg', '.woff2': 'font/woff2' };
    if (!process.argv.includes('--live')) await page.route('https://ikbar1.github.io/hope_charity/**', route => {
        const file = files(route.request().url());
        return route.fulfill({ body: fs.readFileSync(file), contentType: mime[path.extname(file)] || 'application/octet-stream' });
    });
    for (const width of [1440, 390]) {
        await page.setViewportSize({ width, height: 900 });
        for (const locale of ['', 'en/', 'ar/']) for (const target of ['', 'profil/', 'program/', 'kontak/']) {
            const response = await page.goto(base + locale + target);
            assert.equal(response.status(), 200);
            await page.evaluate(() => document.fonts.ready);
            assert.equal(await page.locator('meta[name=robots]').getAttribute('content'), 'noindex, nofollow');
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
            assert.equal(await page.locator('.brand img').evaluate(i => i.complete && i.naturalWidth > 0), true);
            for (const url of await page.locator('[href],[src]').evaluateAll(nodes => nodes.map(n => n.href || n.src))) {
                assert.ok(url.startsWith('https://ikbar1.github.io/hope_charity/'), url);
                assert.ok(fs.existsSync(files(url)), url);
            }
        }
    }
    if (process.argv.includes('--live')) {
        await page.goto(base);
        await page.setViewportSize({ width: 1440, height: 900 });
        await page.screenshot({ path: 'docs/verification/mockup-pages/pages-live-home.jpg', fullPage: true });
    }
    await browser.close();
    console.log('Static export verified: 12 routes × 2 viewports; assets, links, locale and noindex valid.');
})().catch(error => { console.error(error); process.exitCode = 1; });
