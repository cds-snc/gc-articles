# Screenreader-only

Tags: `<gcds-sr-only>`  
Also called: visually hidden, assistive text  
Docs: https://design-system.canada.ca/en/components/screenreader-only/

The screenreader-only component is text information only accessible with assistive technologies.

## API (from custom-elements.json, v1.7.1)

### `<gcds-sr-only>`

The screenreader-only component is text information only accessible with assistive technologies.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `tag` | `"h1" \| "h2" \| "h3" \| "h4" \| "h5" \| "h6" \| "p" \| "span"` | `'p'` |  | Sets the appropriate HTML tag for the content. |

**Slots:**

- `default` - Slot for the hidden accessible content.

## Use case

### Problems screenreader-only solves

Use screenreader-only to:

- Provide context to people using screen reader assistive technologies.

- Offer a text option to communicate information typically solely available by visual means.

- Communicate effectively without adding more text to the main content. This helps reduce cognitive load for everyone by only providing content when and where it’s needed.

- Improve the assistive tech experience when there are no other suitable options in semantic HTML.

### Related components

Headings for structuring content by creating levels of hierarchy that organize page content visually and mentally, using GC Design System styles.

Text for paragraphs displaying non-heading content with matching GC Design System styles.

## Design guidance

### Design and accessibility for the screenreader-only component

#### Write helpful screenreader-only content

- Flag contextual information exclusively communicated through visual styling that’s absent from ‌semantic HTML.

- Keep screenreader-only content clear and very brief. Adding too much text unnecessarily increases the cognitive load.

- Provide additional text content specifically for assistive technologies only when the interface design relies on visual information to communicate.

- Avoid including additional information that’s unavailable in the interface.

- Avoid duplicating any information included in descriptive text (alt-text). If the descriptive text already provides the context, the screenreader-only component may not be needed.

## Code guidance and examples

### Coding and accessibility for screenreader-only

#### Write helpful screenreader-only content

- Flag contextual information exclusively communicated through visual styling that’s absent from ‌semantic HTML.

- Keep screenreader-only content clear and very brief. Adding too much text unnecessarily increases the cognitive load.

- Provide additional text content for assistive technologies only when the interface design relies on visual information to communicate.

- Avoid including additional information that’s unavailable in the interface.

- Avoid duplicating any information included in descriptive text (alt-text). If the descriptive text already provides the context, the screenreader-only component may not be needed.

#### Provide an equitable experience in both Official Languages

- Use the screenreader-only content to make sure the text will be read in the language associated with the component’s language tag.

- Avoid using an `aria-label` attribute to set another language because some assistive technologies will default to page’s source language.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `tag`

The `tag` attribute sets the appropriate HTML element for the content. By default, it is set to `p`.

```html
<gcds-sr-only tag="h2">
  Text only seen by assistive technologies.
</gcds-sr-only>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing the hidden accessible content.

```html
<gcds-sr-only>
  Text only seen by assistive technologies.
</gcds-sr-only>
```
