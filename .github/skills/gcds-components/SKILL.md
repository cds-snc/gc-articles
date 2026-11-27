---
name: gcds-components
description: Reference for the GC Design System (GCDS, design-system.canada.ca) web components (gcds-*), CSS Shortcuts and design tokens. Use whenever writing, reviewing or debugging markup that uses <gcds-*> elements or @gcds-core packages, choosing which GCDS component fits a UI need, looking up a component's attributes, slots and events, or styling custom markup to match Government of Canada / Canada.ca standards.
---

# GC Design System components

GCDS ships framework-agnostic web components (`<gcds-*>`, built with Stencil) plus optional React, Vue and Angular wrappers, a CSS utility library (CSS Shortcuts) and design tokens. This skill is the source of truth for **what exists and how to use it** (v1.7.1). The `gcds-new-site` and `gcds-convert` skills build on it.

## Rules

1. **Never invent API.** Before using a component, open its file in [references/components/](references/components/) (index below). The API tables are generated from [custom-elements.json](references/custom-elements.json); if prose or doc examples disagree with the table, the table wins.
2. **Prefer, in order:** a GCDS component → a CSS Shortcuts class ([css-shortcuts.md](references/css-shortcuts.md)) → a design token in custom CSS ([design-tokens.md](references/design-tokens.md)) → hand-written CSS. Never hard-code colours, fonts, or spacing the tokens already define.
3. **Never restyle component internals.** Components use shadow DOM. Don't target internals, don't override component tokens (`--gcds-button-*`, etc.), and don't wrap components to change their look. Use the attributes they expose. Use layout utilities only on the outside.
4. **Language comes from the nearest `lang` attribute.** Set `<html lang="en">` or `<html lang="fr">`. Components localize their built-in text (such as "required", validation messages, the header and footer) automatically. No component has a `lang` prop. Every page needs an equivalent page in the other official language, linked from `gcds-header lang-href`.
5. **Attribute syntax:**
   - HTML: use kebab-case (`button-role="secondary"`). Boolean attributes can be bare or `="true"`/`="false"`. Objects and arrays are **JSON strings in single quotes**, for example `options='[{"id":"a","label":"A","value":"a"}]'`. The JSON must be valid: double-quoted keys, no trailing commas. Write an apostrophe inside the JSON as the typographic `’` (preferred, and correct French typography: `J’accepte`) or as `&#39;`. Never use a raw `'`, which ends the attribute.
   - React: use camelCase props (`buttonRole`), events `onGcdsChange`, and pass real arrays and objects.
   - Vue: `:prop` bindings, events `@gcdsChange`, `v-model` on form components.
   - Angular: `[prop]` bindings, events `(gcdsChange)`, `ngModel` and reactive forms. See [frameworks.md](references/frameworks.md).
6. **Custom elements are never self-closing in HTML.** Write `<gcds-input ...></gcds-input>`, not `<gcds-input />`. Self-closing is fine in JSX and Vue SFCs.
7. **Always set required attributes.** For example, `gcds-input` needs `input-id`, `name` and `label`. `gcds-heading` needs `tag`. `gcds-notice` needs `notice-role`, `notice-title` and `notice-title-tag`.
8. **Keep parent/child structure flat.** `gcds-nav-link` and `gcds-nav-group` must be **direct children** of `gcds-top-nav`, `gcds-side-nav` or `gcds-nav-group`, because they check `parentNode` to style themselves. `gcds-breadcrumbs-item` goes directly in `gcds-breadcrumbs`, and `gcds-grid-col` directly in `gcds-grid`. Don't wrap them in `<li>`, `<div>`, router-link elements, or framework components that render an extra element.
9. **Headings and landmarks:** use exactly one `h1` per page and don't skip levels. Components that render headings (`card-title-tag`, `notice-title-tag`, `legend-size`, `gcds-stepper tag`) must fit the page outline. Use one `gcds-header`, one `<main>` (usually `gcds-container tag="main"`) and one `gcds-footer`.
10. **Events** are DOM `CustomEvent`s named `gcds*` (`gcdsClick`, `gcdsChange`, `gcdsInput`, `gcdsBlur`, `gcdsFocus`, `gcdsError`, `gcdsValid`, `gcdsSubmit`, `gcdsDismiss`, ...). The value is in `event.detail`. Listen for these instead of native events.
11. **Forms:** use GCDS form components inside a native `<form>`. They are form-associated, so values submit by `name`, and they validate themselves. Read [forms-and-validation.md](references/forms-and-validation.md) before building any form.
12. **Accessibility is part of the job.** Every form field has a visible label. Don't use placeholder text as a label. Link text and button labels say where they go or what they do. Images need `alt`. Don't use colour alone to convey meaning.
13. **Verify:** after writing or changing GCDS markup, run the linter (see below) and fix every error. Then run the browser check if you can.

## Choosing a component

| Need | Use | Not |
|---|---|---|
| Page title or section title | `gcds-heading tag="h1..h6"` | styled `<div>`/`<p>` |
| Paragraph of body text | `gcds-text` (`size="small"` for captions) | inline styles |
| Navigate somewhere (inline or standalone) | `gcds-link` (`external` for other sites, `download` for files) | `gcds-button` |
| Do something (submit, start, delete) | `gcds-button` (`button-role` primary, secondary, danger, or start) | a styled link |
| Prominent call to action that navigates | `gcds-button type="link" href` | |
| Message that's part of the page content when it loads | `gcds-notice` | `gcds-alert` |
| Message that appears in response to an event (user action, system event) | `gcds-alert` | `gcds-notice` |
| Show or hide supplementary content | `gcds-details` | custom accordion |
| Clickable summary that links to one page | `gcds-card` | `gcds-container border` + link |
| Bordered or sized box, or page-width wrapper | `gcds-container` (`layout="page"` for main content) | |
| Responsive columns | `gcds-grid` (+ `gcds-grid-col` for spans) | Bootstrap rows/cols |
| One choice from fewer than 7 options | `gcds-radios` | `gcds-select` |
| One choice from more than 7 options | `gcds-select` | |
| Zero or more choices, or a single consent box | `gcds-checkboxes` | |
| Short free text, email, phone, number | `gcds-input` (`type`, `inputmode`, `autocomplete`, `size`) | |
| Long free text | `gcds-textarea` | |
| Known date (birth date, expiry date) | `gcds-date-input` (`format="full"`, `"compact"` or `"iso"`) | `<input type="date">` |
| File upload | `gcds-file-uploader` | |
| Group several fields under one question | `gcds-fieldset` (radios, checkboxes and date input already include one) | |
| List of form errors | `gcds-error-summary` inside the `<form>` | |
| Progress in a multi-page form | `gcds-stepper` | |
| Tabular data (sort, filter, paginate) | `gcds-table` | `<table>` |
| Long result lists split across pages | `gcds-pagination` (`display="list"` or `"simple"`) | |
| Site navigation, horizontal | `gcds-top-nav` in `gcds-header slot="menu"` | |
| Site navigation, vertical (app or docs) | `gcds-side-nav` | |
| Canada.ca theme and topic navigation | `gcds-topic-menu` in `gcds-header slot="menu"` | |
| Text for screen readers only | `gcds-sr-only` | `.sr-only` CSS |
| Icon | `gcds-icon name` (fixed set of 20 icons) | icon fonts |
| Last updated date | `gcds-date-modified` (`YYYY-MM-DD`) | |
| GC branding | `gcds-header` and `gcds-footer` (they include `gcds-signature` and the wordmark) | images of the FIP |

**Canada.ca required components:** header, footer, signature, language toggle, search, breadcrumbs and date modified. Using `gcds-header` (with `gcds-search slot="search"` and `gcds-breadcrumbs slot="breadcrumb"`) plus `gcds-footer` and `gcds-date-modified` covers all of them.

## Component index

<!-- component-index:start -->
| Component | Tag(s) | Also called | Reference |
|---|---|---|---|
| Alert | `gcds-alert` | message, inline message | [alert.md](references/components/alert.md) |
| Breadcrumbs | `gcds-breadcrumbs`, `gcds-breadcrumbs-item` | breadcrumb trail | [breadcrumbs.md](references/components/breadcrumbs.md) |
| Button | `gcds-button` | call to action, CTA | [button.md](references/components/button.md) |
| Card | `gcds-card` | tile, content card | [card.md](references/components/card.md) |
| Checkboxes | `gcds-checkboxes` | checklist, check list | [checkboxes.md](references/components/checkboxes.md) |
| Container | `gcds-container` | wrapper, box | [container.md](references/components/container.md) |
| Date input | `gcds-date-input` | dates, dateinput, date, memorable date | [date-input.md](references/components/date-input.md) |
| Date modified | `gcds-date-modified` | Last updated, version date | [date-modified.md](references/components/date-modified.md) |
| Details | `gcds-details` | accordion, collapse | [details.md](references/components/details.md) |
| Error message | `gcds-error-message` | error, inline error | [error-message.md](references/components/error-message.md) |
| Error summary | `gcds-error-summary` | error list | [error-summary.md](references/components/error-summary.md) |
| Fieldset | `gcds-fieldset` | form group | [fieldset.md](references/components/fieldset.md) |
| File uploader | `gcds-file-uploader` | file upload, file input, dropzone | [file-uploader.md](references/components/file-uploader.md) |
| Footer | `gcds-footer` | site footer | [footer.md](references/components/footer.md) |
| Grid | `gcds-grid`, `gcds-grid-col` | layout, columns, columns layout, grid container | [grid.md](references/components/grid.md) |
| Header | `gcds-header` | global header, brand header | [header.md](references/components/header.md) |
| Heading | `gcds-heading` | title, section heading, subheading | [heading.md](references/components/heading.md) |
| Hint and label | `gcds-hint`, `gcds-label` |  | [hint-and-label.md](references/components/hint-and-label.md) |
| Icon | `gcds-icon` | symbol, glyph, graphic | [icon.md](references/components/icon.md) |
| Input | `gcds-input` | text input, input field | [input.md](references/components/input.md) |
| Language toggle | `gcds-lang-toggle` | language switch, language selector | [language-toggle.md](references/components/language-toggle.md) |
| Link | `gcds-link` | anchor, hyperlink, call to action | [link.md](references/components/link.md) |
| Notice | `gcds-notice` | contextual alert | [notice.md](references/components/notice.md) |
| Pagination | `gcds-pagination` | page index, page navigation | [pagination.md](references/components/pagination.md) |
| Radios | `gcds-radios` | radio button, radio | [radios.md](references/components/radios.md) |
| Screenreader-only | `gcds-sr-only` | visually hidden, assistive text | [screenreader-only.md](references/components/screenreader-only.md) |
| Search | `gcds-search` | search bar, site search box, search field | [search.md](references/components/search.md) |
| Select | `gcds-select` | dropdown, select menu | [select.md](references/components/select.md) |
| Side navigation | `gcds-side-nav`, `gcds-nav-group`, `gcds-nav-link` | sidebar menu, lefthand nav, vertical navigation | [side-navigation.md](references/components/side-navigation.md) |
| Signature | `gcds-signature` | wordmark | [signature.md](references/components/signature.md) |
| Stepper | `gcds-stepper` | progress indicator, steps | [stepper.md](references/components/stepper.md) |
| Table | `gcds-table` | data table, data grid | [table.md](references/components/table.md) |
| Text | `gcds-text` | text block, copy, caption, body text, paragraph | [text.md](references/components/text.md) |
| Textarea | `gcds-textarea` | text box | [textarea.md](references/components/textarea.md) |
| Theme and topic menu | `gcds-topic-menu` | global navigation, theme-based navigation | [theme-and-topic-menu.md](references/components/theme-and-topic-menu.md) |
| Top navigation | `gcds-top-nav`, `gcds-nav-group`, `gcds-nav-link` | site menu, navigation, navbar | [top-navigation.md](references/components/top-navigation.md) |
<!-- component-index:end -->

How to look things up, cheapest first:

1. **API only:** run `python3 <skill-dir>/scripts/gcds_lint.py --api input select radios`, or read [api-quick.md](references/api-quick.md) (all 41 tags in about 10 KB). This lists attributes, with required ones in bold, plus enum values, JS props, slots and events.
2. **Usage guidance and examples:** open `references/components/<name>.md`. Each file has the full API tables, then GCDS guidance on use cases, design, accessibility and code, then copy-ready examples per attribute. Read the guidance sections the first time you use a component in a task. Skim the per-attribute examples, which repeat themselves.

## Other references

- [frameworks.md](references/frameworks.md): installing and using GCDS in plain HTML, React or Next.js, Vue or Nuxt, and Angular. Covers prop and event naming, `v-model`, `ngModel`, routing and table cells.
- [forms-and-validation.md](references/forms-and-validation.md): form structure, built-in and custom validation, error summary, and SPA submit handling.
- [css-shortcuts.md](references/css-shortcuts.md): every utility class (spacing, layout, flex and grid, typography, colour, borders, display, responsive and state prefixes).
- [design-tokens.md](references/design-tokens.md): `--gcds-*` custom properties for custom CSS.
- [api-quick.md](references/api-quick.md): a compact API for every tag.
- [custom-elements.json](references/custom-elements.json): the raw machine-readable schema.

## Verify GCDS usage

`<skill-dir>` is the absolute path of this skill's folder (the folder containing this SKILL.md). Resolve it before running commands from the project directory.

```bash
python3 <skill-dir>/scripts/gcds_lint.py <files-or-dirs>              # errors + warnings, exit 1 on error
python3 <skill-dir>/scripts/gcds_lint.py --inventory <files-or-dirs>  # legacy markup to convert
python3 <skill-dir>/scripts/gcds_lint.py --api <tag> [...]            # quick API lookup
```

The linter needs only Python 3 and reads `custom-elements.json`. Per component, it reports:

- unknown tags, attributes and `gcds*` events
- invalid enum values
- missing required attributes
- invalid JSON in object and array attributes
- self-closing custom elements in HTML
- nav, breadcrumb and grid children that aren't direct children of their parent

For complete HTML documents, it also checks for `<html lang>`, `<title>`, a single h1, a valid skip-link target, header, footer and date modified, and table captions. It reads HTML, JSX/TSX (`<GcdsButton buttonRole>`), Vue (`:prop`, `@event`), Angular (`[prop]`, `(event)`), Svelte, server templates and Markdown code blocks, and it skips values that contain template expressions (`{{ }}`, `${}`, `<% %>`). It can't judge content quality, heading wording or behaviour. That's what the browser check is for.

**Browser check** (needs Node and `npm i -D playwright @axe-core/playwright && npx playwright install chromium` in the current directory):

```bash
node <skill-dir>/scripts/gcds_browser_check.mjs --serve ./site --all --forms   # static files, served on a free port
node <skill-dir>/scripts/gcds_browser_check.mjs --forms http://localhost:5173/en/ http://localhost:5173/fr/
```

The browser check verifies that every `gcds-*` element is defined and rendered. Invalid or missing props make a component render nothing and log an error. It also checks for:

- console errors
- axe WCAG 2.1 AA violations (known upstream issues are listed separately)
- `<html lang>` and a single h1
- the language toggle

With `--forms`, it submits each form empty and expects a focused error summary. If you can't install Playwright (for example, offline), say so in your report and list the manual checks the user should do.

## Known doc discrepancies (v1.7.1)

The website examples have a few errors. The schema is correct:

- `gcds-top-nav alignment` accepts only `start` or `end`, not `right`.
- `gcds-search suggested` must be a JSON array string such as `suggested='["red","green"]'`. Single-quoted items like `"['red']"` fail `JSON.parse`.
- Header docs mention `signature-variant`, but `gcds-header` has no such attribute. To change the signature, put a `gcds-signature` in the header's `signature` slot.
- Some fieldset examples omit the required `name` on nested inputs. Always include it.
- The French basic page template writes the date as `DD-MM-YYYY`. `gcds-date-modified` content must use `YYYY-MM-DD` in both languages.

## Known component issues (v1.7.1)

These were found with axe and are inside component shadow DOM, so your markup can't fix them. Don't hack around them. Note them in reports and raise them upstream (https://github.com/cds-snc/gcds-components/issues):

- `gcds-pagination` in French: the "Suiv." / "Préc." buttons have an `aria-label` starting "Page suivante" / "Page précédente", which triggers axe `label-content-name-mismatch`.
