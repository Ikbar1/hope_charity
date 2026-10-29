const { chromium } = require(process.env.HOPE_PLAYWRIGHT || 'playwright');
const fs = require('node:fs');
const path = require('node:path');
const assert = require('node:assert/strict');
(async () => {
  const out = path.resolve('docs/verification/mockup-pages');
  fs.mkdirSync(out, { recursive: true });
  const browser = await chromium.launch({ channel: 'msedge', headless: true });
  const results = [];
  const targets = new Set();
  for (const width of [1440, 390]) {
    const context = await browser.newContext({ viewport: { width, height: 900 } });
    const page = await context.newPage();
    for (const name of ['index', 'profil']) {
      await page.goto('file:///' + path.resolve('mockups/charity-demo/' + name + '.html').replaceAll('\\', '/'));
      await page.screenshot({ path: path.join(out, `baseline-${name}-${width}.jpg`), fullPage: true });
    }
    for (const locale of ['id', 'en', 'ar']) for (const key of ['home', 'profile', 'program', 'contact']) {
      const url = 'http://127.0.0.1:8765' + (locale === 'id' ? '' : '/' + locale) + ({ home: '/', profile: '/profil', program: '/program', contact: '/kontak' }[key]);
      const response = await page.goto(url);
      await page.evaluate(() => document.fonts.ready);
      await page.locator('footer').scrollIntoViewIfNeeded();
      await page.waitForTimeout(100);
      await page.evaluate(() => scrollTo(0, 0));
      const state = await page.evaluate(() => ({ lang: document.documentElement.lang, dir: document.documentElement.dir, overflow: document.documentElement.scrollWidth > innerWidth, broken: [...document.images].filter(i => !i.hidden && i.complete && !i.naturalWidth).length, cards: document.querySelectorAll('[data-program-key]').length, activeContact: document.querySelectorAll('input:not(:disabled),textarea:not(:disabled),form').length }));
      assert.equal(response.status(), 200); assert.equal(state.lang, locale); assert.equal(state.overflow, false, url + ' overflow'); assert.equal(state.broken, 0); assert.equal(state.activeContact, 0);
      if (key === 'program') assert.equal(state.cards, 8);
      await page.screenshot({ path: path.join(out, `${locale}-${key}-${width}.jpg`), fullPage: true });
      for (const href of await page.locator('a').evaluateAll(links => links.map(a => a.href))) {
        const link = new URL(href);
        if (link.hash) assert.equal(await page.locator(link.hash).count(), 1, 'Missing fragment ' + href);
        if (link.origin === 'http://127.0.0.1:8765') targets.add(link.origin + link.pathname);
      }
      results.push({ width, locale, key, ...state });
    }
    await context.close();
  }
  for (const locale of ['id', 'ar']) {
    const context = await browser.newContext({ viewport: { width: 390, height: 844 }, javaScriptEnabled: false, reducedMotion: 'reduce' });
    const page = await context.newPage();
    await page.goto('http://127.0.0.1:8765' + (locale === 'id' ? '' : '/ar') + '/kontak');
    assert.equal(await page.locator('.primary-nav').isVisible(), true);
    await page.screenshot({ path: path.join(out, `${locale}-nojs-reduce.jpg`), fullPage: true });
    await context.close();
  }
  const context = await browser.newContext({ viewport: { width: 390, height: 844 } });
  const page = await context.newPage();
  await page.route('**/images/*.jpg', route => route.abort());
  await page.route('**/*.woff2', route => route.abort());
  await page.goto('http://127.0.0.1:8765/ar');
  await page.keyboard.press('Tab');
  assert.equal(await page.evaluate(() => document.activeElement.className), 'skip-link');
  await page.locator('.menu-toggle').click();
  assert.equal(await page.locator('.menu-toggle').getAttribute('aria-expanded'), 'true');
  await page.locator('.primary-nav a').first().focus();
  await page.keyboard.press('Escape');
  assert.equal(await page.locator('.menu-toggle').getAttribute('aria-expanded'), 'false');
  await page.screenshot({ path: path.join(out, 'ar-media-font-failure.jpg'), fullPage: true });
  for (const target of targets) assert.equal((await context.request.get(target)).status(), 200, target);
  fs.writeFileSync(path.join(out, 'results.json'), JSON.stringify(results, null, 2));
  await browser.close();
  console.log(JSON.stringify({ passed: results.length, extraModes: 3 }));
})().catch(error => { console.error(error); process.exitCode = 1; });
