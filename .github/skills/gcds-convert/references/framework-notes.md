# Framework notes for conversions

Read alongside `../../gcds-components/references/frameworks.md`. These are the behaviours that break when you swap native or library components for GCDS.

## Events and values (all frameworks)

| Before | After |
|---|---|
| `onChange={(e) => set(e.target.value)}` | `onGcdsChange={(e) => set(e.detail)}` (React) / `@gcdsChange` / `(gcdsChange)="v = $event.detail"` |
| `onInput` / keystroke handlers | `onGcdsInput` (`e.detail` = current value) |
| `onBlur` / `onFocus` | `onGcdsBlur` / `onGcdsFocus` |
| Checkbox group `e.target.checked` per box | `gcds-checkboxes` `gcdsChange` with `e.detail` = array of checked values |
| Radio `e.target.value` | `gcds-radios` `gcdsChange` with `e.detail` = selected value |
| `ref.current.focus()` / `.value` | Same on the GCDS host (it delegates focus). `.value` is a property. Validation is async: `await ref.current.validate()` |
| `onClick` on links/buttons | Still works (native click bubbles from the shadow DOM). `gcdsClick` is also available, and `preventDefault()` on either one cancels link navigation |
| `e.target` inside handlers | The GCDS host element, not the inner `<input>` |

`event.target.name` still works, because `name` is a property on the host.

## React

- Import wrappers from `@gcds-core/components-react`. Don't render raw `<gcds-*>` in JSX: props would become strings and events would be lost.
- **Controlled inputs:** pass `value={state}` and update it from `onGcdsChange` (fires on commit) or `onGcdsInput` (every keystroke). There's no React-style "controlled" lock, so the user's typing is never blocked.
- **react-hook-form:** `register()` doesn't work (no native input in the light DOM). Use `<Controller render={({ field }) => <GcdsInput ... value={field.value} onGcdsChange={(e) => field.onChange(e.detail)} onGcdsBlur={field.onBlur} errorMessage={fieldState.error?.message} />} />`. Set `validateOn="other"` on GCDS fields so only one system validates and shows errors.
- **Formik:** same idea: `value={values.x}`, `onGcdsChange={(e) => setFieldValue('x', e.detail)}`, `errorMessage={touched.x && errors.x}`, and `validateOn="other"`.
- **Routing:** replace `<Link to>` with a small `AppLink` wrapper around `GcdsLink` / `GcdsNavLink` that calls `navigate()` in `onClick` after `preventDefault()` (see `../../gcds-new-site/references/framework-shells.md`).
- **Next.js:** the wrapper module is `'use client'`, so it's safe to import from server components. If you hit hydration warnings, check that the server and client render the same `lang` and the same props.

## Vue

- Import wrappers from `@gcds-core/components-vue` in each SFC. `v-model` replaces `:value` plus `@input` on the seven form components.
- **VeeValidate:** use `useField` with `value` and `errorMessage` bound to `:value` / `@gcdsChange` / `:error-message`, and set `validate-on="other"` on GCDS fields.
- **Router links:** replace `<RouterLink>` with `GcdsLink` or `GcdsNavLink` using `@gcdsClick="(e) => { e.preventDefault(); router.push(to) }"` and a matching `href`.
- **Vuetify, PrimeVue, Element:** remove the library components page by page. Shadow DOM keeps their global styles off GCDS components, but their resets can still affect slotted light-DOM content.

## Angular

- Import `GcdsComponentsModule` into each standalone component (or the NgModule). `formControlName`, `[formControl]` and `[(ngModel)]` work through the bundled value accessors.
- Reactive form validators keep working. To avoid double messages, set `validateOn="other"` on GCDS fields and bind `[errorMessage]="ctrl.touched && ctrl.errors ? msg : ''"`. Alternatively, drop Angular validators and use GCDS `required` and `validator`.
- `routerLink` works on GCDS link components (`gcds-link`, `gcds-nav-link`, `gcds-button`, `gcds-breadcrumbs-item`, `gcds-card`) through `GcdsRouterDirective`. Keep `href` too.
- Angular Material: replace `mat-form-field` / `matInput` / `mat-select` / `mat-radio-group` / `mat-checkbox` / `mat-button` with GCDS equivalents. Remove the `@angular/material` theme CSS when done.
- `gcds-table` in templates: use `<gcds-table-ng>` for slotted cell templates.

## Server templates (Django, Rails, Jinja, Razor, Thymeleaf, Twig, Liquid, EJS)

- Field macros and partials (`{% include "field.html" %}`, `form.as_p`, `f.text_field`, Razor tag helpers) are the best place to convert once. Make the macro output the GCDS component with `label`, `hint` and `error-message` from the framework's form object.
- Serialize `options`, `columns`, `data`, `error-links` and `contextual-links` with the framework's JSON filter, inside **single-quoted** attributes (`options='{{ opts|tojson }}'`). Make sure the JSON contains no unescaped `'`. Jinja `|tojson` escapes it as `\u0027`. In other engines, HTML-escape the value.
- Django `form.errors` and Rails `errors.full_messages` map to `error-message` on each field plus `error-links` on `gcds-error-summary`.
- CSRF tokens and hidden inputs stay native.

## Tests

- **Selectors:** text inputs and buttons now live in shadow roots. Playwright pierces open shadow DOM, so `getByRole('textbox', { name: 'Email address' })` and `getByRole('button', { name: 'Submit' })` keep working. CSS selectors like `#email` must target the host (`gcds-input[input-id="email"]`) or use role and label locators. Cypress needs `includeShadowDom: true`.
- **Testing Library + jsdom (Jest, Vitest):** Stencil components don't fully render in jsdom. Assert on host props and attributes and emitted events, or move behaviour tests to Playwright or Cypress. Unit-test logic separately from markup.
- **Snapshot tests:** expect large diffs. Regenerate them deliberately after review.
- **E2E typing:** `locator.fill()` works on the inner input through the role locator. Validation fires on blur, so press Tab or submit before asserting on errors.
- **Accessibility tests:** `@axe-core/playwright` analyses shadow DOM, so keep existing axe checks and expect them to pass.
