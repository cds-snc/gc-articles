---
name: gcds-convert
description: Convert or migrate existing frontend code (plain HTML, WET-BOEW/GCWeb/Canada.ca theme, Bootstrap, Tailwind, Material or custom components, in static HTML, server templates, React, Vue or Angular) to the GC Design System (GCDS) gcds-* web components, CSS Shortcuts and design tokens. Use when a developer provides existing pages, components or a repo and asks to convert, migrate, port, restyle or "make it use" the GC Design System / GCDS / Canada.ca design system.
---

# Convert existing frontend code to the GC Design System

This skill builds on **`gcds-components`**. Load that skill first: it holds the rules, the component APIs and the verification scripts. Paths such as `../gcds-components/...` are relative to **this skill's directory**. Resolve them to absolute paths before running commands from the project directory.

The goal is **the same product, rebuilt on GCDS**. Keep its content, URLs, form field names, behaviour and accessibility, and swap the presentation layer for GCDS components, CSS Shortcuts and tokens.

## Workflow

```
- [ ] 1. Inventory the existing code
- [ ] 2. Mapping plan agreed
- [ ] 3. GCDS installed alongside the old styles
- [ ] 4. Converted: shell → content → actions → forms → layout → custom CSS
- [ ] 5. Old framework removed, lint clean, behaviour verified
- [ ] 6. Report
```

### 1. Inventory

1. Identify the stack (static HTML, server templates, React, Vue, Angular), the styling system (WET/GCWeb, Bootstrap 3/4/5, Tailwind, Material, custom CSS), the build tool, the test setup and the language handling (how EN and FR pages are produced).
2. Run the inventory scan on the code to convert:
   ```bash
   python3 ../gcds-components/scripts/gcds_lint.py --inventory <dir-or-files>
   ```
   It lists each line with markup that has a GCDS equivalent (header, footer, nav, breadcrumbs, headings, paragraphs, links, buttons, every form control type, alerts, details and accordions, tables, cards, pagination, grids, icons, sr-only text, and WET or Bootstrap assets). It's a heuristic: read the actual code too.
3. Note things GCDS has **no** component for (see "No GCDS equivalent" in [element-mapping.md](references/element-mapping.md)): tabs, modals, tooltips, toasts, badges and tags, progress bars, spinners, carousels, comboboxes, toggle switches, calendars.

### 2. Plan the mapping

Produce a short plan for the user: one row per pattern found (not per occurrence), with **current → GCDS replacement → notes / behaviour changes**. Use [element-mapping.md](references/element-mapping.md) for components and [css-migration.md](references/css-migration.md) for utility classes and custom CSS.

Use `ask_user` only for real decisions, such as:

- Site type (Canada.ca page or standalone GC service), which changes the header and footer configuration (see `../gcds-new-site/references/page-anatomy.md`).
- Components with no GCDS equivalent: keep and restyle with tokens, or restructure (for example, tabs to sections with "On this page", a modal to a separate page or a `gcds-details`).
- Whether to keep a form library (react-hook-form, Formik, VeeValidate, Angular reactive forms) or switch to GCDS built-in validation.
- Scope: everything at once, or page by page with both systems loaded during the transition.

**If you can't ask** (non-interactive run, or the user said to proceed), use these defaults and list each one in the report:

| Decision | Default |
|---|---|
| Site type | Canada.ca page if the source uses WET/GCWeb with Canada.ca breadcrumbs or the theme menu, otherwise a standalone service |
| Footer | `display="full"` for Canada.ca and content sites, `compact` for transactional apps |
| Search | keep the legacy search `action` and `name`, set `placeholder` to the site name, and omit search if the legacy page had none |
| Breadcrumbs | carry over the legacy trail (minus the current page). Use `hide-canada-link` only if the legacy trail didn't start at Canada.ca |
| Missing labels or options | infer them and mark `TODO(content)` (see "Legacy form problems" in element-mapping.md) |
| No equivalent component | restructure if the content survives intact, otherwise keep it and restyle with tokens |
| Form library | keep it, and set `validate-on="other"` on GCDS fields |
| French page | convert the existing FR page if there is one. If not, create it and mark untranslated copy `TODO(fr)` |

### 3. Install GCDS

Install following `../gcds-components/references/frameworks.md`. While converting, the old CSS framework can stay loaded. GCDS components are shadow-DOM encapsulated, so old global CSS mostly can't leak in, but old resets can still affect light-DOM content such as lists and paragraphs.

### 4. Convert, in this order

Each step should leave the app working. Commit or checkpoint between steps if the user uses git.

1. **Page shell.** Replace header, nav, breadcrumbs, search, language toggle, `<main>`, date modified and footer with the GCDS anatomy. Remove custom skip links, signature and wordmark images, and language-toggle code, because the components provide them. Keep `lang-href` pointing at the real other-language URL.
2. **Content.** `h1`-`h6` become `gcds-heading tag`, paragraphs become `gcds-text`, and links become `gcds-link` (`external` for off-site links; drop manual "opens in new window" icons). Lists keep native `<ul>` and `<ol>` with `list-disc` / `list-decimal`. Visually hidden text becomes `gcds-sr-only`. Notes and alerts become `gcds-notice` (static) or `gcds-alert` (event-driven).
3. **Actions.** Buttons become `gcds-button` with `button-role` mapped from the old variant, and `type` preserved (`submit` stays submit). Links styled as buttons become `gcds-button type="link" href`. Expand/collapse becomes `gcds-details`.
4. **Forms** (largest behaviour change; read `../gcds-components/references/forms-and-validation.md`):
   - Each label + control + hint + error group becomes **one** GCDS component (`label`, `hint`, `error-message` attributes). Delete the old wrapper markup.
   - Keep every `name` (the backend depends on it) and `value`, and map `id` to `input-id`, `select-id`, `textarea-id` or `uploader-id`.
   - A radio or checkbox group becomes a single `gcds-radios` / `gcds-checkboxes` with `legend` and an `options` JSON array. Keep the option values and ids. **Payload changes to flag:** `gcds-checkboxes` submits `name=a,b` (one comma-joined field), and `gcds-date-input` submits one `name=YYYY-MM-DD` instead of separate day, month and year fields. Adapt the backend or serialize before submit.
   - Three-part date inputs, or `<input type="date">` for known dates, become `gcds-date-input`.
   - Replace bespoke validation with `required` / `type` / `pattern` plus custom `validator`s, and add `gcds-error-summary` inside the form. Keep server-side validation. Render server errors through `error-message` and `error-links`.
   - Event handlers: native `change` / `input` / `blur` become `gcdsChange` / `gcdsInput` / `gcdsBlur`, and the value comes from `event.detail`, not `event.target.value`. Re-wire framework bindings (React `onGcdsChange`, Vue `v-model`, Angular `ngModel` / `formControlName`).
5. **Layout.** Bootstrap and WET grids become `gcds-grid` (+ `gcds-grid-col`), and containers become `gcds-container`. Convert utility classes with [css-migration.md](references/css-migration.md).
6. **Custom CSS.** Delete rules that now duplicate GCDS. Rewrite the remaining rules with `--gcds-*` tokens (colours, spacing, fonts, borders, breakpoints `48em` and `64em`). Never style component internals.

Framework-specific gotchas are in [framework-notes.md](references/framework-notes.md): controlled inputs, form libraries, refs, routing links, tests querying shadow DOM, jsdom, and SSR.

### 5. Remove the old system and verify

- Remove the old CSS and JS (WET/GCWeb `theme.min.css` / `wet-boew.min.js`, Bootstrap, jQuery plugins that only served the old UI, icon fonts) once the inventory finds nothing left that depends on them. Remove unused dependencies from `package.json`.
- `python3 ../gcds-components/scripts/gcds_lint.py <src>` must show **0 errors**. `--inventory` should list only deliberate leftovers.
- Run the existing build, type-check and tests. Update test selectors that targeted the old markup (see framework-notes.md).
- Browser check (after `npm i -D playwright @axe-core/playwright && npx playwright install chromium` in the current directory): `node ../gcds-components/scripts/gcds_browser_check.mjs --forms <urls>`, or `--serve <dir> --all --forms` for static files. It catches components that render nothing because of invalid props, console errors, axe violations, a missing or duplicate h1, and broken error summaries.
- Run the app and compare each converted page with the original: same content, links, form submissions (same field names and values reach the backend), validation messages in both languages, keyboard navigation and focus order, and the language toggle reaching the matching page. Use the browser/Playwright tools if available. Add an axe scan if the project has e2e tests.

### 6. Report

Summarize the files changed, the mapping applied, behaviour that intentionally changed (validation timing, error text, link icons, focus after submit), anything with no GCDS equivalent and how you handled it, removed dependencies, and follow-ups (such as `TODO(fr)` content, and custom components to revisit when GCDS adds equivalents).
