# Using GCDS in HTML, React, Vue and Angular

Versions referenced: `@gcds-core/components@1.7.1`, `@gcds-core/css-shortcuts@1.2.0`. To check for newer releases, run `npm view @gcds-core/components version` or see the [changelog](https://github.com/cds-snc/gcds-components/blob/main/CHANGELOG.md). Pin exact versions in production.

| Stack | Install | Load styles | Register components |
|---|---|---|---|
| Static HTML / CMS / server templates (CDN) | none | `<link rel="stylesheet" href="https://cdn.design-system.canada.ca/@gcds-core/components@1.7.1/dist/gcds/gcds.css">` | `<script type="module" src="https://cdn.design-system.canada.ca/@gcds-core/components@1.7.1/dist/gcds/gcds.esm.js"></script>` |
| HTML with npm / bundler (Vite, Webpack, Svelte, Astro, .NET, Java) | `npm i @gcds-core/components` | `import '@gcds-core/components/dist/gcds/gcds.css'` (or link `/node_modules/@gcds-core/components/dist/gcds/gcds.css`) | `import { defineCustomElements } from '@gcds-core/components/loader'; defineCustomElements();` (or a module script pointing at `dist/gcds/gcds.esm.js`) |
| React / Next.js / Remix / Gatsby | `npm i @gcds-core/components @gcds-core/components-react` | `import '@gcds-core/components-react/gcds.css'` in the entry or root layout | import wrappers: `import { GcdsButton } from '@gcds-core/components-react'` |
| Vue 3 / Nuxt / Quasar | `npm i @gcds-core/components-vue` | `import '@gcds-core/components-vue/gcds.css'` in `main.js` | `createApp(App).use(GcdsComponents)`, then import wrappers in each SFC |
| Angular 19 to 22 | `npm i @gcds-core/components @gcds-core/components-angular` | `@import '../node_modules/@gcds-core/components/dist/gcds/gcds.css';` in `styles.scss` (or add it to `angular.json` `styles`) | import `GcdsComponentsModule` |
| CSS Shortcuts (any stack, optional) | `npm i @gcds-core/css-shortcuts` | `@gcds-core/css-shortcuts/dist/gcds-css-shortcuts.min.css` or CDN `https://cdn.design-system.canada.ca/@gcds-core/css-shortcuts@1.2.0/dist/gcds-css-shortcuts.min.css` | none |

`<script type="module">` needs a page served over HTTP. For local work, use a dev server (`npx serve .`) instead of opening the file directly. You can scaffold a full project with `npm create @gcds-core/app my-project`, which offers HTML, React and Vue templates.

---

## Plain HTML / vanilla JS

```html
<gcds-input input-id="email" name="email" label="Email address" type="email" autocomplete="email" required></gcds-input>
<gcds-radios
  name="contact"
  legend="How should we contact you?"
  options='[{"id":"c-email","label":"Email","value":"email"},{"id":"c-phone","label":"Phone","value":"phone"}]'
></gcds-radios>
<script type="module">
  const email = document.querySelector('gcds-input[name="email"]');
  email.addEventListener('gcdsChange', (e) => console.log('value:', e.detail));

  // Objects, arrays and JS-only props (validator) are easiest to set as properties
  const radios = document.querySelector('gcds-radios');
  radios.options = [{ id: 'c-mail', label: 'Mail', value: 'mail' }, ...radios.options];
</script>
```

- Wait for definition before calling methods: `await customElements.whenDefined('gcds-input'); await el.validate();`
- Every method is async and returns a Promise.
- Server templates (Jinja, Nunjucks, ERB, Razor, Thymeleaf, Liquid) render `<gcds-*>` tags like any HTML. Make sure the JSON in attributes is HTML-escaped correctly, or render it into single-quoted attributes.

## React (and Next.js)

The wrappers expose PascalCase components with camelCase props and `on` + event-name handlers. The module is marked `'use client'`, so in the Next.js App Router you can import it from any component, and it renders as a client component.

```tsx
import { useState, type FormEvent } from 'react';
import { GcdsButton, GcdsErrorSummary, GcdsHeading, GcdsInput, GcdsRadios, GcdsText } from '@gcds-core/components-react';
import { validateGcdsForm } from './validateGcdsForm'; // see forms-and-validation.md

export function ContactForm() {
  const [email, setEmail] = useState('');

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    if (!(await validateGcdsForm(e.currentTarget))) return;
    await fetch('/api/contact', { method: 'POST', body: new FormData(e.currentTarget) });
  }

  return (
    <form noValidate onSubmit={handleSubmit}>
      <GcdsErrorSummary />
      <GcdsHeading tag="h2">Contact</GcdsHeading>
      <GcdsText>We reply within 2 business days.</GcdsText>
      <GcdsInput
        inputId="email"
        name="email"
        label="Email address"
        type="email"
        autocomplete="email"
        required
        value={email}
        onGcdsChange={(e) => setEmail(e.detail)}
      />
      <GcdsRadios
        name="contact"
        legend="How should we contact you?"
        options={[
          { id: 'c-email', label: 'Email', value: 'email' },
          { id: 'c-phone', label: 'Phone', value: 'phone' },
        ]}
        onGcdsChange={(e) => console.log(e.detail)}
      />
      <GcdsButton type="submit">Send</GcdsButton>
    </form>
  );
}
```

- To call methods, use a typed ref: `const ref = useRef<HTMLGcdsInputElement>(null); await ref.current?.validate();`.

- Use `className` for CSS Shortcuts: `<GcdsContainer className="mb-400">`.
- Element types (`HTMLGcdsInputElement`, ...) are global types declared by `@gcds-core/components`.
- **Client-side routing:** GCDS links render a real `<a>` in shadow DOM. To route in the SPA, cancel the click and navigate. Either use `onClick` (as the official starter's `AppLink` does) or `onGcdsClick`, and let modified clicks through for new tabs: `<GcdsLink href="/about" onClick={(e) => { if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return; e.preventDefault(); navigate('/about'); }}>`. This works the same for `GcdsNavLink`, `GcdsBreadcrumbsItem`, `GcdsButton type="link"` and `GcdsCard`.
- **Table custom cells:** `GcdsTable` from the React package is a wrapper. Set `slotted: true` on the column and add `renderCell: ({ row, rowIndex, column, value }) => <GcdsLink href={...}>{row.id}</GcdsLink>`.
- Don't use the raw `<gcds-*>` tags in React. Without the wrappers, props stay string attributes and events aren't wired.

## Vue 3 (and Nuxt)

```js
// main.js
import { createApp } from 'vue';
import { GcdsComponents } from '@gcds-core/components-vue';
import '@gcds-core/components-vue/gcds.css';
import App from './App.vue';
createApp(App).use(GcdsComponents).mount('#app');
```

```vue
<script setup>
import { ref } from 'vue';
import { GcdsButton, GcdsInput, GcdsSelect } from '@gcds-core/components-vue';
const name = ref('');
const province = ref('');
</script>

<template>
  <form @submit="onSubmit">
    <GcdsInput input-id="name" name="name" label="Full name" v-model="name" required />
    <GcdsSelect select-id="prov" name="province" label="Province or territory" default-value="Select an option" v-model="province">
      <option value="on">Ontario</option>
      <option value="qc">Quebec</option>
    </GcdsSelect>
    <GcdsButton type="submit">Continue</GcdsButton>
  </form>
</template>
```

- The `GcdsComponents` plugin only registers the custom elements. Import the wrapper components you use in each SFC to get `v-model` and typed props.
- `v-model` binds `value` and updates on `gcdsChange`. It works on `GcdsInput`, `GcdsTextarea`, `GcdsSelect`, `GcdsRadios`, `GcdsCheckboxes` (array value), `GcdsDateInput` and `GcdsFileUploader`.
- Use `:options="[...]"` and `:columns="[...]"` to pass real arrays. Listen to events with `@gcdsClick`, `@gcdsChange`, and so on.
- With `vueCompilerOptions.strictTemplates: true`, `vue-tsc` rejects `slot="..."` and `id="..."` on wrapper components, because they aren't declared props. They work at runtime through attribute fallthrough. Keep `strictTemplates` off (the create-vue default), or put those attributes on raw `<gcds-*>` tags.
- Nav and breadcrumb children must be direct children of their parent component. A custom `NavLink.vue` wrapper is fine only if its root is the `<GcdsNavLink>` itself.
- If you use raw `<gcds-*>` tags without the wrappers, tell the compiler they are custom elements: `vue({ template: { compilerOptions: { isCustomElement: (t) => t.startsWith('gcds-') } } })` in `vite.config`. You lose `v-model` this way.
- **Routing:** use `@gcdsClick="(e) => { e.preventDefault(); router.push('/about') }"` with `href="/about"`.
- **Table custom cells:** use `<GcdsTable :columns :data>` with `<template #fieldName="{ row }">...</template>` for each column that has `slotted: true`.
- **Nuxt:** register the plugin in a client plugin file (`plugins/gcds.client.ts`) and add the CSS to `nuxt.config` `css: []`.

## Angular

```ts
// standalone component (Angular 19+)
import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { GcdsComponentsModule } from '@gcds-core/components-angular';

@Component({
  selector: 'app-contact',
  standalone: true,
  imports: [GcdsComponentsModule, FormsModule, ReactiveFormsModule],
  templateUrl: './contact.component.html',
})
export class ContactComponent {
  email = '';
}
```

```html
<!-- contact.component.html -->
<form (ngSubmit)="submit()">
  <gcds-input inputId="email" name="email" label="Email address" type="email" [(ngModel)]="email" required></gcds-input>
  <gcds-link href="/privacy" routerLink="/privacy">Privacy</gcds-link>
  <gcds-button type="submit">Send</gcds-button>
</form>
```

- If you use an NgModule, add `GcdsComponentsModule` to its `imports`. You don't need `CUSTOM_ELEMENTS_SCHEMA`.
- Tags stay kebab-case (`<gcds-input>`). Props can be kebab-case attributes or camelCase `@Input`s (`inputId="x"`, `[options]="opts"`). Events use `(gcdsChange)="..."`, and the value is in `$event.detail`.
- `ngModel`, `formControlName` and `formControl` work on `gcds-input`, `gcds-textarea`, `gcds-select`, `gcds-radios`, `gcds-checkboxes`, `gcds-date-input` and `gcds-file-uploader`.
- **Routing:** add `routerLink` (plus optional `queryParams` and `fragment`) to `gcds-link`, `gcds-button`, `gcds-nav-link`, `gcds-breadcrumbs-item`, `gcds-card`, `gcds-lang-toggle` or `gcds-header`. The directive cancels `gcdsClick` and calls `router.navigate`. Keep `href` too, for semantics and open-in-new-tab.
- **Table:** use `<gcds-table-ng [columns] [data]>` (not `gcds-table`) with `<ng-template gcdsCell="fieldName" let-row let-rowIndex="rowIndex">...</ng-template>` for slotted columns.

## SSR, hydration and testing

- Components upgrade on the client. Server-rendered markup is plain `<gcds-*>` tags that hydrate once `gcds.esm.js` (or the wrappers) load. Don't read component state during SSR.
- To get component internals in tests, use the element's shadow root (`el.shadowRoot.querySelector(...)`). Playwright pierces shadow DOM by default, so prefer role and label locators: `page.getByRole('textbox', { name: 'Email address' })`.
- Jest with jsdom does not run Stencil components. Test behaviour in a real browser (Playwright, Cypress), or mock the wrappers.
