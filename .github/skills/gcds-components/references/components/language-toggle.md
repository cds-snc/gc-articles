# Language toggle

Tags: `<gcds-lang-toggle>`  
Also called: language switch, language selector  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/language-toggle/

The language toggle is a link to the same content in the other Official Language.

## API (from custom-elements.json, v1.7.1)

### `<gcds-lang-toggle>`

The language toggle is a link to the same content in the other Official Language.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `href` | `string` |  | **yes** | The href attribute specifies the URL of the opposite language page |

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked. Contains the href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

Language toggle is a required component for Government of Canada websites and has specific requirements for Canada.ca pages.

Take a look at use cases for the language toggle to see if it fits the problem you’re solving for.

### Problems the language toggle solves

Use the language toggle to:

- Make sure people are able to switch between languages without losing track of where they were before switching.

- Support Official Languages by offering equitable access in French and English.

### Related components

Header for placing the Government of Canada branded header landmark.

## Design guidance

### Language toggle anatomy

The language toggle provides a link to the page in the other Official Language.

- The **toggle link** navigates to the page in the other Official Language and appears in short form on smaller screens, or viewports.

### Design and accessibility for the language toggle

#### Check language toggle requirements

The language toggle is required in the header on Canada.ca pages and GC sites.

**What’s required on Canada.ca and GC sites**

Always include the language toggle in the header and maintain default settings.

- Keep the placement in the top right corner of the header where it’s predictable and findable.
- Only include English and French as options.
- In the language toggle link, include the opposite Official Language of the current page.

**What’s optional to include on a Canada.ca page**

If needed, include links to content in other languages in the content area of the page.

## Code guidance and examples

### Coding and accessibility for language toggle

- Place the language toggle in the top right of the header landmark for predictability and findability.

- Use the `href-attribute` to set the url of the page in the other Official Language.

- Set the `lang` attribute with `en` to indicate the language toggle in French and `fr` will indicate the language toggle in English.

Note: If using the header component, the language toggle can be assigned using the `lang-href` attribute.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `href`

The `href` attribute specifies the URL of the same page in another language, typically English or French. Use this attribute to provide a link that allows users to switch between available language versions.

```html
<gcds-lang-toggle href="#"></gcds-lang-toggle>
```
