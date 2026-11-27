# Forms and validation with GCDS

GCDS form components (`gcds-input`, `gcds-textarea`, `gcds-select`, `gcds-radios`, `gcds-checkboxes`, `gcds-date-input`, `gcds-file-uploader`) are **form-associated custom elements**:

- Inside a native `<form>`, they submit their value under their `name`, like native controls. `FormData`, `form.reset()` and the `form="id"` attribute all work.
- They render their own `<label>`/`<legend>`, hint, "(required)" marker and error message, in the page language.
- They validate themselves. By default (`validate-on="blur"`), a field validates when it loses focus **and** when its form submits. If any field has an error, the form's `submit` event is cancelled.
- `gcds-error-summary` placed in the same `<form>` collects every field error on submit, lists them in page order, focuses itself, and links each item to its field.

## Canonical form

```html
<form action="/apply" method="post" novalidate>
  <gcds-error-summary></gcds-error-summary>

  <gcds-input input-id="full-name" name="fullName" label="Full name" autocomplete="name" required></gcds-input>

  <gcds-input input-id="email" name="email" label="Email address" type="email" autocomplete="email"
    hint="We'll send your confirmation here." required></gcds-input>

  <gcds-date-input name="dob" legend="Date of birth" format="full" required></gcds-date-input>

  <gcds-radios name="contact" legend="How should we contact you?" required
    options='[{"id":"contact-email","label":"Email","value":"email"},{"id":"contact-phone","label":"Phone","value":"phone"}]'>
  </gcds-radios>

  <gcds-select select-id="province" name="province" label="Province or territory" default-value="Select an option" required>
    <option value="ab">Alberta</option>
    <option value="qc">Quebec</option>
  </gcds-select>

  <gcds-checkboxes name="consent" required
    options='[{"id":"consent","label":"I confirm the information is accurate","value":"yes"}]'>
  </gcds-checkboxes>

  <gcds-button type="submit">Submit application</gcds-button>
</form>
```

Rules:

- Put `gcds-error-summary` **inside** the `<form>`, as its first child. It only listens to fields in its own form. Leave `listen` at its default `true` to collect errors automatically.
- Give every field a unique `*-id` and `name`. Radio and checkbox option `id`s must be unique on the page.
- `required` turns on the built-in required validator and the bilingual messages. Don't add a "*" or "(required)" to labels yourself.
- `type="email"` validates the email format. Native constraints (`pattern`, `min`, `max`, `minlength`, `maxlength`, `step`) are also validated and get localized messages.
- One question per field. Ask for the minimum. Use `hint` for format instructions, not placeholders.
- Group related fields under one question with `gcds-fieldset legend legend-size`. Radios, checkboxes and date input already render their own fieldset and legend, so don't wrap those just to add a legend.
- Only one primary button per form. Use `button-role="secondary"` for "Save draft" or "Back". Use `button-role="danger"` for destructive actions.
- For multi-step flows, add `gcds-stepper current-step total-steps` above the page heading, and keep one topic per page.

## validate-on

| Value | Behaviour |
|---|---|
| `blur` (default) | validates on blur and on form submit |
| `submit` | validates only on form submit |
| `other` | never validates automatically; call `await el.validate()` yourself |

## Server-side errors

When the server rejects a submission, re-render the page with the user's values and an `error-message` on each invalid field. Then list the same errors in the summary, in page order:

```html
<gcds-error-summary error-links='{"#email-field":"Email address - Enter an email address in the correct format, like name@example.com"}'></gcds-error-summary>
<gcds-input id="email-field" input-id="email" name="email" label="Email address" type="email" value="bad@" error-message="Enter an email address in the correct format, like name@example.com"></gcds-input>
```

Setting `error-links` turns off automatic listening. Each key is `#` + an id that exists in the **light DOM**. The summary runs `document.querySelector(key).focus()`, and `*-id` values live inside the shadow root, so give the component host its own `id` as shown. The hosts delegate focus to their inner control. Also prefix the page `<title>` with "Error: " while errors are shown.

On client-validated pages (static sites, SPAs), update the title from the browser:

```js
const FIELDS = 'gcds-input, gcds-textarea, gcds-select, gcds-radios, gcds-checkboxes, gcds-date-input, gcds-file-uploader';
form.addEventListener('submit', () => setTimeout(() => {
  const hasErrors = [...form.querySelectorAll(FIELDS)].some((f) => f.errorMessage);
  const base = document.title.replace(/^(Error: |Erreur : )/, '');
  document.title = hasErrors ? `${document.documentElement.lang === 'fr' ? 'Erreur : ' : 'Error: '}${base}` : base;
}, 150));
```

## Where forms submit

- **A real backend** (server app, API, GC Forms): use `method="post"` and `action` pointing at it. This is the default for anything that collects personal information.
- **Static sites with no backend (prototypes):** `method="get" action="/en/…/confirmation/"` reaches a confirmation page, but it puts the answers in the URL, browser history and server logs. Never use it for personal information in production, and say so in the report. Alternatively, handle the submit in JavaScript (`validateGcdsForm` below, then `fetch` to an API).

## Custom validators (JavaScript)

`validator` is a JS-only property: an array of validator names or objects. A validator returns `{ valid, reason: { en, fr } }`:

```js
// <script type="module"> (top-level await)
const postal = document.querySelector('gcds-input[name="postal"]');
await postal.componentOnReady(); // the component has loaded and added its built-in validators

const postalCode = {
  validate: (value) => ({
    valid: !value || /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/.test(value.trim()),
    reason: {
      en: 'Enter a postal code in the format A1A 1A1',
      fr: 'Entrez un code postal dans le format A1A 1A1',
    },
  }),
};

// Append: after load, assigning a fresh array replaces the built-in required validator
postal.validator = [...(postal.validator ?? []), postalCode];
```

- Timing: if `validator` is set **before** the component loads, the component adds its built-in required validator to the front of your array. If it's set **after** load, your array is used as is. Appending after `componentOnReady()` (as above) works either way.

- Always provide both `en` and `fr` reasons. The component shows the one that matches the page language.
- Built-in validator names you can include explicitly: `requiredField`, `requiredEmailField`, `requiredSelectField`, `requiredDateInput`, `requiredFileInput`, `requiredRadio`, `requiredCheckboxGroup`, `requiredCheckboxSingle`. In React, Vue or Angular, pass the full array including the built-in, for example `validator={['requiredField', postalCode]}`.
- Error messages say what went wrong and how to fix it, without blaming the user. Write them in sentence case.

## Submitting from a SPA (React, Vue, Angular, fetch)

Framework submit handlers run **before** GCDS's own document-level submit listener. Validate explicitly, then read the values:

```js
const FIELDS = 'gcds-input, gcds-textarea, gcds-select, gcds-radios, gcds-checkboxes, gcds-date-input, gcds-file-uploader';

export async function validateGcdsForm(form) {
  const fields = [...form.querySelectorAll(FIELDS)];
  await Promise.all(fields.map((f) => f.validate()));
  return fields.every((f) => !f.errorMessage);
}

// React
async function handleSubmit(e) {
  e.preventDefault();
  const form = e.currentTarget;
  if (!(await validateGcdsForm(form))) return; // the error summary in the form updates itself
  await fetch('/api/apply', { method: 'POST', body: new FormData(form) });
}
```

- Leave the native `submit` event bubbling (don't call `stopPropagation`), so `gcds-error-summary` still renders and takes focus.
- Each field also emits `gcdsError` / `gcdsValid` (detail `{ message }`) if you need to track state yourself.
- `await el.checkValidity()` reports native constraint validity only. Use `validate()` + `errorMessage`, as above, to include custom validators.

## Accessibility checklist for forms

- [ ] Every field has a visible, specific label (use `hide-label` only when the surrounding context makes the purpose obvious, such as search).
- [ ] Hints explain the expected format, and errors explain how to fix the problem.
- [ ] `autocomplete` is set on personal-information fields (`name`, `email`, `tel`, `postal-code`, `bday`, ...).
- [ ] `size` fits the expected answer length (for example 7 for a postal code, 10 for a phone number). Use `inputmode="numeric"` with `type="text"` for codes and account numbers.
- [ ] On submit with errors, the error summary appears at the top and receives focus, and the `<title>` starts with "Error: " (server-rendered pages).
- [ ] No field is disabled just to block progress. Let users submit, then show errors.
- [ ] A confirmation page (`gcds-notice notice-role="success"` or a page `h1` like "Application submitted") follows a successful submit.
