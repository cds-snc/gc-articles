# Element and component mapping to GCDS

Check every target in `../../gcds-components/references/components/<name>.md` before using it. Unless noted, attributes not listed here don't exist.

## Page shell

| Existing | GCDS |
|---|---|
| `<header>`, WET `#wb-bnr`, Bootstrap `.navbar` brand bar, custom logo/FIP image | `<gcds-header lang-href skip-to-href="#main-content">` (renders the signature) |
| Skip links (`#wb-tphp`, `.skip-link`) | built into `gcds-header` via `skip-to-href`; delete yours |
| Language link (`#wb-lng`, "Français" link) | `gcds-header lang-href="<same page, other lang>"`; delete yours |
| Search form (`#wb-srch`, navbar search) | `<gcds-search slot="search" action name placeholder>` (defaults to Canada.ca search; set `action` for site search) |
| Breadcrumbs (`#wb-bc ol.breadcrumb`, `.breadcrumb`) | `<gcds-breadcrumbs slot="breadcrumb">` + `<gcds-breadcrumbs-item href>` per ancestor (omit the current page; Canada.ca link is automatic unless `hide-canada-link`) |
| Canada.ca mega menu (`.gcweb-menu`, `#wb-sm`) | `<gcds-topic-menu slot="menu">` |
| Site nav bar (`.navbar-nav`, `<nav><ul>`) | `<gcds-top-nav slot="menu" label>` + `<gcds-nav-link href [current]>`; first link `slot="home"`; dropdowns become `<gcds-nav-group open-trigger menu-label>` |
| Sidebar nav (`#wb-sec`, `.list-group`, `.nav-stacked`) | `<gcds-side-nav label>` + `gcds-nav-link` / `gcds-nav-group` |
| Sign-in / account link in header | `<gcds-button slot="account" type="link" href>` |
| `<main property="mainContentOfPage" class="container">` | `<gcds-container id="main-content" layout="page" tag="main">` |
| Page details / date modified (`.pagedetails`, `#wb-dtmd`) | `<gcds-date-modified>YYYY-MM-DD</gcds-date-modified>` at end of main; drop "Report a problem" widgets unless required |
| `<footer>`, `#wb-info` | `<gcds-footer display="full">` (+ `contextual-heading` / `contextual-links` for ≤3 site links) |

## Content

| Existing | GCDS | Notes |
|---|---|---|
| `<h1>`…`<h6>` | `<gcds-heading tag="hN">` | Keep the level; fix skipped levels; one h1. Visual size follows level. If a heading was styled smaller/larger, fix the hierarchy rather than restyling |
| `<p>`, `.lead` | `<gcds-text>` (`size="small"` for small print, `text-role="secondary"` for muted) | Inline markup (`<strong>`, `<em>`, `<abbr>`, `<gcds-link>`) stays inside |
| `<a href>` in text or standalone | `<gcds-link href>` | `external` for other domains (adds icon + new tab); `download` for files; `display="block"` for standalone links; remove manual icons/"(opens in new window)" spans |
| `<a class="btn ...">` | `<gcds-button type="link" href button-role>` | |
| `.sr-only`, `.visually-hidden`, `.wb-inv` | `<gcds-sr-only tag="span">` | `tag` = `p` (default), `span`, or `h1`-`h6` |
| `.alert.alert-info/success/warning/danger` already on the page | `<gcds-notice notice-role="info/success/warning/danger" notice-title notice-title-tag="h2">` | Notice **requires** a title; promote the first bold line to `notice-title` |
| Flash message after an action, dismissible banner | `<gcds-alert alert-role heading>` | `hide-close-btn`, `is-fixed`, `container` |
| `<details><summary>`, `.accordion`, `.collapse` + toggle button | `<gcds-details details-title>` (one per panel; `open` to start expanded) | Accordion groups → consecutive `gcds-details` |
| `.card`, `.panel`, tiles linking to one destination | `<gcds-card card-title href description card-title-tag>` (`img-src`/`img-alt`, `badge`) | The whole card is the link. Cards with **no link**, or with several links and actions, become `gcds-container border padding="300"` + content (`gcds-card` requires `href`) |
| `.well`, bordered box | `<gcds-container border padding="300">` or `<div class="b-sm b-default p-300">` | |
| Data `<table>` (sortable/filterable, `.wb-tables`, DataTables) | `<gcds-table columns data [sort] [filter] [pagination]>` + `<span slot="caption">` | Move rows to `data` JSON / framework data; custom cells via `slotted` columns. Layout tables → `gcds-grid` |
| Simple static `<table>` | `gcds-table` (preferred) or keep native `<table>` with tokens | |
| `.pagination`, pager | `<gcds-pagination label display="list" total-pages current-page url>` or `display="simple" previous-href next-href` | |
| Step indicator ("Step 2 of 4"), `.gc-subway`, progress steps | `<gcds-stepper current-step total-steps tag>Step title</gcds-stepper>` | It **is** the step heading |
| Icon fonts (Glyphicons, Font Awesome, Bootstrap Icons, Material) | `<gcds-icon name size label>` | Only 20 icons: arrow-down/up/up-down, checkmark-circle, chevron-down/left/right/up, close, download, email, exclamation-circle, external, filter, info-circle, phone, search, sort, tune, warning-triangle. Decorative icons: omit `label`. No match → drop the icon or keep an inline SVG with `aria-hidden="true"` |
| "Last updated" text | `gcds-date-modified` | |
| Hero/banner image | native `<img class="img-responsive" alt>` inside `gcds-grid` | |

## Actions

| Existing | GCDS |
|---|---|
| `<button>` / `.btn-primary` | `<gcds-button>` (`button-role="primary"` default) — keep `type` (`submit`/`button`/`reset`), `name`, `value`, `disabled` |
| `.btn-default`, `.btn-secondary`, `.btn-outline-*` | `button-role="secondary"` |
| `.btn-danger` | `button-role="danger"` |
| `.btn-call-to-action` (GCWeb), big "Start now" | `button-role="start"` (once per page) |
| `.btn-sm` | `size="small"` |
| `.btn-link` | `gcds-link` (if it navigates) or `gcds-button button-role="secondary"` (if it acts) |
| `<input type="submit|button|reset">` | `<gcds-button type="submit|button|reset">` |
| Button group / toolbar | sibling `gcds-button`s in `<div class="d-flex flex-wrap gap-200">` |

## Forms

| Existing | GCDS | Mapping |
|---|---|---|
| `<label>` + `<input type=text|email|tel|url|number|password|search>` (+ hint + error) | `<gcds-input input-id name label type hint error-message>` | `id`→`input-id`; keep `name`, `value`, `required`, `autocomplete`, `pattern`, `min`/`max`/`step`, `minlength`/`maxlength`, `readonly`, `disabled`, `inputmode`; `placeholder` has no equivalent → move to `hint`; Bootstrap `.form-control-sm`/widths → `size` (chars) |
| `<datalist>` | `suggestions='[{"label":"..."}]'` on `gcds-input` | |
| `<textarea>` | `<gcds-textarea textarea-id name label rows maxlength>` | Character counter is built in when `maxlength` set (`hide-limit` to hide) |
| `<select>` | `<gcds-select select-id name label default-value="Select an option">` with native `<option>`/`<optgroup>` children | Replace the empty first `<option>` with `default-value` |
| Radio group (`<fieldset><legend>` + radios) | `<gcds-radios name legend options hint>` | `options='[{"id","label","value","hint"?,"checked"?}]'`; set `value` for the selected one |
| Checkbox group | `<gcds-checkboxes name legend options>` | `value` is an array (`value='["a","b"]'`). **Submits one comma-joined field** (`name=a,b`), not repeated fields (`name=a&name=b`) like native checkboxes. Update the backend to split on `,`, or have option values that never contain commas |
| Single checkbox (consent) | `<gcds-checkboxes name options='[{"id":"consent","label":"I agree","value":"yes"}]' required>` | no `legend` needed for a single checkbox |
| Day/month/year inputs, `<input type="date">` for a known date | `<gcds-date-input name legend format="full|compact|iso">` | `value` `YYYY-MM-DD` (`YYYY-MM` for compact); `min`/`max` same format |
| `<input type="file">` | `<gcds-file-uploader uploader-id name label accept multiple>` | |
| `<fieldset><legend>` grouping several different fields | `<gcds-fieldset legend legend-size="h2..h6" hint>` | Not needed around radios/checkboxes/date-input |
| `.help-block`, `.form-text`, `aria-describedby` hint | `hint="..."` | |
| `.invalid-feedback`, `.has-error .help-block`, `.label-danger` | `error-message="..."` (server-rendered) or built-in validation | |
| Error list at top of form (`.alert-danger` with links, WET `.wb-frmvld` summary) | `<gcds-error-summary>` inside the `<form>` | `error-links` for server errors |
| WET `.wb-frmvld` / jQuery Validate / Parsley | GCDS built-in validation + `validator` property | Remove the plugin |
| `.form-group`, `.mb-3`, `.form-row` wrappers | delete (components carry their own spacing) | Keep `gcds-grid` only for side-by-side fields |
| `<input type="hidden">`, CSRF tokens | keep native | |

### Legacy form problems to fix (and flag) while converting

- **No label, or a placeholder-only label:** GCDS requires `label`/`legend`. Derive one from the visible text near the field, the placeholder, `aria-label`, or the `name`, then flag it in the report as `TODO(content): confirm label`. Never use `hide-label` to dodge this.
- **Missing `id`:** generate `*-id` values from `name` (`name="email"` → `input-id="email"`). Radio and checkbox options need unique ids such as `<name>-<value>`.
- **`<select>` with only a placeholder option** (`<option>Choose</option>`): use `default-value="Choose an option"` plus the real options. If the options are unknown, leave `TODO(content)`. Note that the placeholder no longer submits a value.
- **Empty fields are not submitted.** Empty GCDS fields are left out of the payload entirely, while native controls send `name=`. Backends that check `key in form` instead of a truthy value need updating.
- **Icons inside buttons** (`<button><i class="fa ..."></i> Save</button>`): GCDS buttons take a text label only. Drop decorative icons. `type="link"` with an external `href` adds the external icon automatically.

### Before → after examples

**Bootstrap form group**

```html
<!-- before -->
<div class="mb-3">
  <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
  <input type="email" class="form-control is-invalid" id="email" name="email" placeholder="name@example.com" required>
  <div class="form-text">We'll never share your email.</div>
  <div class="invalid-feedback">Please enter a valid email.</div>
</div>

<!-- after -->
<gcds-input input-id="email" name="email" label="Email address" type="email" autocomplete="email" required
  hint="For example, name@example.com. We'll never share your email."
  error-message="Enter an email address in the correct format, like name@example.com"></gcds-input>
```

(Only render `error-message` when the server actually reported the error.)

**Radio group**

```html
<!-- before -->
<fieldset>
  <legend>Preferred language</legend>
  <div class="radio"><label><input type="radio" name="lang" id="lang-en" value="en" checked> English</label></div>
  <div class="radio"><label><input type="radio" name="lang" id="lang-fr" value="fr"> French</label></div>
</fieldset>

<!-- after -->
<gcds-radios name="lang" legend="Preferred language" value="en"
  options='[{"id":"lang-en","label":"English","value":"en"},{"id":"lang-fr","label":"French","value":"fr"}]'></gcds-radios>
```

**WET date of birth (3 fields)**

```html
<!-- after -->
<gcds-date-input name="dob" legend="Date of birth" format="full" required></gcds-date-input>
```

The submitted value is a single `dob=YYYY-MM-DD`. If the backend expects `dob-day`/`dob-month`/`dob-year`, update the backend or split the value before submit — call this out in the report.

**GCWeb alert → notice**

```html
<!-- before -->
<section class="alert alert-warning"><h2 class="h3">Service disruption</h2><p>Online applications are unavailable Sunday 2 to 6 am.</p></section>

<!-- after -->
<gcds-notice notice-role="warning" notice-title="Service disruption" notice-title-tag="h2">
  <gcds-text>Online applications are unavailable Sunday 2 to 6 am.</gcds-text>
</gcds-notice>
```

**Bootstrap grid**

```html
<!-- before -->
<div class="row"><div class="col-md-6 col-lg-4">A</div><div class="col-md-6 col-lg-8">B</div></div>

<!-- after (fixed fractions) -->
<gcds-grid columns-tablet="1fr 1fr" columns-desktop="1fr 2fr"><div>A</div><div>B</div></gcds-grid>

<!-- after (12-column spans; tablet is a 6-column grid) -->
<gcds-grid>
  <gcds-grid-col tablet="3" desktop="4">A</gcds-grid-col>
  <gcds-grid-col tablet="3" desktop="8">B</gcds-grid-col>
</gcds-grid>
```

Bootstrap `col-md-N` → `tablet = N / 2`; `col-lg-N` → `desktop = N`. Mobile is always 1 column.

## No GCDS equivalent

| Pattern | Recommended handling |
|---|---|
| Tabs (`.wb-tabs`, `.nav-tabs`) | Prefer restructuring: sections with an "On this page" list, separate pages, or `gcds-details`. If tabs are essential, keep an accessible tabs implementation (WAI-ARIA tabs pattern) styled with tokens |
| Modal / dialog (`.wb-overlay`, `.modal`) | Prefer a separate page or inline content; otherwise native `<dialog>` styled with tokens, with focus management |
| Tooltip / popover | Put the information in `hint` or a `gcds-details` |
| Toast | `gcds-alert` at the top of main |
| Badge / tag / label pill | `gcds-card badge` on cards; elsewhere a `<span>` styled with CSS Shortcuts (`px-100 b-radius-sm bg-light font-semibold`) |
| Progress bar / spinner | native `<progress>` with tokens; spinner with `role="status"` + `gcds-sr-only` text |
| Combobox / autocomplete | `gcds-input suggestions` (datalist) for simple cases; otherwise keep the existing accessible widget |
| Toggle switch | `gcds-checkboxes` single checkbox |
| Date picker calendar | `gcds-date-input` for known dates; keep a calendar only for scheduling, styled with tokens |
| Carousel | Replace with a static list/grid of `gcds-card`s |
| Dropdown menu (non-nav actions) | Separate `gcds-button`s or a `gcds-details` |

Anything kept custom: tokens only for colours/spacing/typography, 4.5:1 contrast, visible focus (`--gcds-focus-*` tokens), keyboard operable, translated in both languages.
