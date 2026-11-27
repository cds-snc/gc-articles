#!/usr/bin/env node
/**
 * Browser check for pages that use GC Design System components.
 *
 * For every page it checks:
 *   - every <gcds-*> element is defined and hydrated, with no empty renders (empty = invalid or missing props)
 *   - no console errors or warnings (GCDS logs prop validation errors there)
 *   - axe-core (WCAG 2.x A/AA) violations, with known upstream GCDS issues reported separately
 *   - exactly one h1, a language toggle in gcds-header, and <html lang> set to en or fr
 *   - with --forms: submitting each form empty shows a focused gcds-error-summary and does not navigate
 *
 * Setup (once, in the project or any scratch dir; modules resolve from the current directory):
 *   npm i -D playwright @axe-core/playwright && npx playwright install chromium
 *
 * Usage:
 *   node gcds_browser_check.mjs --serve ./site en/index.html fr/index.html   # serves ./site on a free port
 *   node gcds_browser_check.mjs http://localhost:5173/en/ http://localhost:5173/fr/
 *   add --forms to exercise forms, --all to serve and check every .html under --serve dir
 * Exit status 1 when any check fails.
 */
import { createRequire } from 'node:module';
import { createServer } from 'node:http';
import { readFile, readdir, stat } from 'node:fs/promises';
import { extname, join, resolve, relative } from 'node:path';

const req = createRequire(join(process.cwd(), 'noop.js'));
let chromium, AxeBuilder;
try {
  const pw = await import(req.resolve('playwright'));
  chromium = pw.chromium ?? pw.default?.chromium;
  const axe = await import(req.resolve('@axe-core/playwright'));
  AxeBuilder = axe.AxeBuilder ?? axe.default?.AxeBuilder ?? axe.default?.default ?? axe.default;
} catch {
  console.error('Missing dependencies. Run in this directory:\n  npm i -D playwright @axe-core/playwright && npx playwright install chromium');
  process.exit(2);
}

const KNOWN_UPSTREAM = [
  { rule: 'label-content-name-mismatch', target: /gcds-pagination/, note: 'gcds-pagination FR prev/next label (upstream)' },
];

const args = process.argv.slice(2);
const flag = (f) => { const i = args.indexOf(f); if (i >= 0) { args.splice(i, 1); return true; } return false; };
const opt = (f) => { const i = args.indexOf(f); if (i >= 0) { const v = args[i + 1]; args.splice(i, 2); return v; } return undefined; };
const checkForms = flag('--forms');
const all = flag('--all');
const serveDir = opt('--serve');

const TYPES = { '.html': 'text/html', '.js': 'text/javascript', '.mjs': 'text/javascript', '.css': 'text/css', '.svg': 'image/svg+xml', '.png': 'image/png', '.jpg': 'image/jpeg', '.json': 'application/json', '.woff2': 'font/woff2' };
let server, base = '';
if (serveDir) {
  const root = resolve(serveDir);
  server = createServer(async (rq, rs) => {
    try {
      let p = join(root, decodeURIComponent(new URL(rq.url, 'http://x').pathname));
      if ((await stat(p)).isDirectory()) p = join(p, 'index.html');
      rs.writeHead(200, { 'content-type': TYPES[extname(p)] ?? 'application/octet-stream' });
      rs.end(await readFile(p));
    } catch { rs.writeHead(404).end('not found'); }
  });
  await new Promise((r) => server.listen(0, '127.0.0.1', r));
  base = `http://127.0.0.1:${server.address().port}/`;
  if (all) {
    const walk = async (d) => (await Promise.all((await readdir(d, { withFileTypes: true })).map((e) =>
      e.isDirectory() ? (e.name === 'node_modules' ? [] : walk(join(d, e.name))) : e.name.endsWith('.html') ? [join(d, e.name)] : []))).flat();
    args.push(...(await walk(root)).map((f) => relative(root, f)));
  }
}
const urls = args.map((a) => (/^https?:/.test(a) ? a : base + a.replace(/^\//, '')));
if (!urls.length) { console.error('No pages given. See usage in the file header.'); process.exit(2); }

const browser = await chromium.launch();
let failures = 0;
const fail = (m) => { failures++; console.log(`  FAIL ${m}`); };

for (const url of urls) {
  const ctx = await browser.newContext();
  const page = await ctx.newPage();
  const logs = [];
  page.on('console', (m) => ['error', 'warning'].includes(m.type()) && logs.push(`${m.type()}: ${m.text()}`));
  page.on('pageerror', (e) => logs.push(`pageerror: ${e.message}`));
  const resp = await page.goto(url, { waitUntil: 'networkidle' }).catch((e) => ({ status: () => `ERR ${e.message}` }));
  console.log(`\n${url} [${resp?.status?.()}]`);
  const s = await page.evaluate(async () => {
    const els = [...document.querySelectorAll('*')].filter((e) => e.localName.startsWith('gcds-'));
    const tags = [...new Set(els.map((e) => e.localName))];
    await Promise.race([Promise.all(tags.map((t) => customElements.whenDefined(t))), new Promise((r) => setTimeout(r, 5000))]);
    await new Promise((r) => setTimeout(r, 400));
    const undefinedTags = tags.filter((t) => !customElements.get(t));
    const empty = els.filter((e) => e.shadowRoot && !e.shadowRoot.innerHTML.trim()).map((e) => e.localName);
    const h1 = document.querySelectorAll('h1').length +
      [...document.querySelectorAll('gcds-heading[tag="h1"],gcds-stepper[tag="h1"],gcds-sr-only[tag="h1"]')].length;
    const toggle = !!document.querySelector('gcds-header')?.shadowRoot?.querySelector('gcds-lang-toggle');
    return { count: els.length, undefinedTags, empty, h1, toggle, lang: document.documentElement.lang };
  });
  console.log(`  ${s.count} gcds elements, lang="${s.lang}", h1=${s.h1}, lang toggle=${s.toggle}`);
  if (s.undefinedTags.length) fail(`not defined (GCDS script not loaded?): ${s.undefinedTags.join(', ')}`);
  if (s.empty.length) fail(`rendered nothing (invalid or missing required props): ${[...new Set(s.empty)].join(', ')}`);
  if (!['en', 'fr'].includes(s.lang)) fail('<html lang> is not en or fr');
  if (s.h1 !== 1) fail(`expected exactly one h1, found ${s.h1}`);
  if (s.count && !s.toggle) console.log('  note: no gcds-header language toggle found');
  const real = logs.filter((l) => !/favicon/i.test(l));
  if (real.length) fail(`console:\n    ${real.join('\n    ')}`);

  const axe = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'best-practice']).analyze();
  for (const v of axe.violations) {
    const target = v.nodes.map((n) => n.target.join(' ')).join(', ');
    const known = KNOWN_UPSTREAM.find((k) => k.rule === v.id && k.target.test(target));
    if (known) console.log(`  known upstream: ${v.id} - ${known.note}`);
    else fail(`axe ${v.id} (${v.impact}) ${v.help} :: ${target}`);
  }

  if (checkForms) {
    // Only author-written (light DOM) forms; Playwright locators would also match forms inside component shadow roots.
    const forms = await page.evaluate(() => {
      const fs = [...document.querySelectorAll('form')].filter((f) =>
        f.querySelector('gcds-input, gcds-select, gcds-radios, gcds-checkboxes, gcds-textarea, gcds-date-input, gcds-file-uploader'));
      fs.forEach((f, i) => f.setAttribute('data-gcds-check', String(i)));
      return fs.length;
    });
    for (let i = 0; i < forms; i++) {
      const form = page.locator(`form[data-gcds-check="${i}"]`);
      const before = page.url();
      const submit = form.locator('gcds-button[type="submit"], button[type="submit"], input[type="submit"]').first();
      if (!(await submit.count())) { console.log(`  form ${i + 1}: no submit button (skipped)`); continue; }
      await submit.click();
      await page.waitForTimeout(800);
      const r = await form.evaluate((f) => {
        const es = f.querySelector('gcds-error-summary');
        return {
          hasSummary: !!es,
          shown: !!es?.shadowRoot?.querySelector('.gcds-show'),
          items: es ? es.shadowRoot.querySelectorAll('li').length : 0,
          focused: document.activeElement?.localName,
        };
      }).catch(() => ({ navigated: true }));
      if (r.navigated || page.url() !== before) { console.log(`  form ${i + 1}: submitted (no required fields?)`); break; }
      console.log(`  form ${i + 1}: error summary shown=${r.shown} items=${r.items} focus=${r.focused}`);
      if (!r.hasSummary) fail(`form ${i + 1}: no gcds-error-summary inside the form`);
      else if (!r.shown || r.focused !== 'gcds-error-summary') fail(`form ${i + 1}: error summary not shown/focused after empty submit`);
    }
  }
  await ctx.close();
}

await browser.close();
server?.close();
console.log(failures ? `\n${failures} failure(s)` : '\nAll checks passed');
process.exit(failures ? 1 : 0);
