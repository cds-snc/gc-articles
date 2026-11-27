# Heading

Tags: `<gcds-heading>`  
Also called: title, section heading, subheading  
Docs: https://design-system.canada.ca/en/components/heading/

A heading is a title that establishes levels of hierarchy to organize page content into a structure.

## API (from custom-elements.json, v1.7.1)

### `<gcds-heading>`

A heading is a title that establishes levels of hierarchy to organize page content into a structure and matches Canada.ca typography styles.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `tag` | `"h1" \| "h2" \| "h3" \| "h4" \| "h5" \| "h6"` |  | **yes** | Sets the appropriate HTML tag for the selected level. |
| `character-limit` | `boolean` | `true` |  | Sets the line length to a maximum amount of characters per line for each heading level, ensuring a comfortable, accessible reading length. |
| `heading-role` | `"light" \| "primary" \| "secondary"` | `'primary'` |  | Sets the main style of the heading. |
| `margin-bottom` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` | `'300'` |  | Adds margin below the heading. The default margin-botttom is 300. |
| `margin-top` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` |  |  | Adds margin above the heading. The default margin-top for h1 is set to 0, while for h2 to h6 headings, it's 600. |

**Slots:**

- `default` - Slot for the heading content

## Use case

### Problems headings solves

Headings help structure content into a hierarchy for a visitor.

Use headings to:

- Aid both sighted and non-sighted people to navigate page content with a clear and organized hierarchy.

- Allow assistive technologies to scan heading levels that signal or summarize the content they contain. A person can then choose what they want to read without reviewing the entire page.

- Introduce a page or emphasize key sections or topics, making it easier for a person to scan and locate relevant information.

- Divide lengthy content into manageable sections. Breaking up your content supports readability, simplifies site navigation, and reduces the risk of people abandoning their task.

- Apply consistent typography styles and sizes throughout a website to create a cohesive and user-friendly design.

### Related components

Text for paragraphs displaying non-heading content with matching GC Design System styles.

Screenreader-only for information that’s detectable with assistive technologies like screen readers, but invisible to sighted users.

## Design guidance

### Heading anatomy

- **Heading** is a prominent line of text that introduces the paragraph or other body content that immediately follows it. The visual prominence of the heading decreases based on its level in the hierarchical order. More visual emphasis is given to the first heading, the H1, than a subordinate heading like an H5 or H6. Assistive technologies, such as screen readers, identify headings by their levels.
- **Text** is non-heading content. Its subject matter is related to the idea outlined in its corresponding heading.

### Design and accessibility for headings

#### Order heading levels hierarchically

Heading levels follow a sequential, hierarchical order: higher levels have larger display text than lower ones.

- Set correct and consistent heading levels to align the coded and visual headings on a page. Consistent headings support an equitable experience for people who use assistive technologies.

- Order headings sequentially from lowest to highest (H1–H6).

- Avoid skipping heading levels. Instead, restructure content so headings are used sequentially, from largest (H1) to smallest (H6).

- Only use an H1 heading once on a page. Other heading types can be used multiple times.

- Use H2 headings to describe individual sections. Each section should have one H2 heading.

- Opt to use H3–H6 headings within sections. Sections can contain multiple H3–H6 headings, as long as they are placed in hierarchical order.

#### Maintain accessible character limits

- Maintain the default character limits for each heading line for a comfortable and accessible reading length. These defaults create a balanced, consistent line length across heading font sizes.

- Keep character limits to the following lengths to maintain a visual balance across headings on a page:

- 31 characters for H1.

- 35 characters for H2.

- 40 characters for H3.

- 45 characters for H4.

- 50 characters for H5.

- 57 characters for H6.

#### Write clear and consistent headings

- Use sentence case (only the initial letter is capitalized).

- Include a single, descriptive H1 heading for each page to convey the page’s content and the purpose of the page’s title.

- Use subheadings to break up content. Split the content into smaller sections and group common elements together.

- Make each heading distinctive and make it clear what content you’ll find in that section.

- When you’ve exhausted available headings and other style elements, consider using font weights to emphasize words in body content.

- When writing in English, keep in mind the French headings will be around 30% longer.

#### Use consistent spacing

- Maintain the default spacing for a comfortable and accessible reading experience. These defaults create visual breaks and separation between different sections.

- Keep spacing values to the following to maintain a visual balance across headings on a page:

- Bottom margin: 300 for all.

- Top margin: 0 for H1 and 600 for H2-6.

## Code guidance and examples

### Coding and accessibility for headings

#### Order heading levels hierarchically

Heading levels follow a sequential, hierarchical order: higher levels have larger display text than lower ones.

- Set correct and consistent heading levels to align the coded and visual headings on a page. Consistent headings support an equitable experience for people who use assistive technologies.

- Order headings sequentially from lowest to highest (H1–H6).

- Avoid skipping heading levels. Instead, restructure content so headings are used sequentially, from largest (H1) to smallest (H6).

- Only use an H1 heading once on a page. Other heading types can be used multiple times.

- Use H2 headings to describe individual sections. Each section should have one H2 heading.

- Opt to use H3–H6 headings within sections. Sections can contain multiple H3–H6 headings, as long as they are placed in hierarchical order.

#### Maintain accessible character limits

- Maintain the default `character-limit` for each heading line by keeping the attribute set to `true` for a comfortable and accessible reading length. These defaults create a balanced, consistent line length across heading font sizes.

- Keep character limits to the following lengths to maintain a visual balance across headings on a page:

- 31 characters for H1.

- 35 characters for H2.

- 40 characters for H3.

- 45 characters for H4.

- 50 characters for H5.

- 57 characters for H6.

#### Use consistent spacing

- Maintain default spacing for a comfortable and accessible reading experience. These defaults create visual breaks and separation between different sections.

- Keep spacing values to the following to maintain a visual balance across headings on a page:

- Default `margin-bottom`: `300` for all

- Default `margin-top`: `0` for H1 and `600` for H2–6

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `tag`

The `tag` attribute sets the appropriate HTML heading element for the selected level.

```html
<gcds-heading tag="h2">Heading</gcds-heading>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `character-limit`

The `character-limit` attribute defines the line length to a maximum number of characters per line for each heading level, ensuring a comfortable, accessible reading length.

```html
<gcds-heading tag="h2" character-limit="false">
  Heading
</gcds-heading>
```

##### `heading-role`

The `heading-role` attribute sets the main style of the heading. The available options are:

- `primary` (default)

- `secondary`

- `light`

```html
<gcds-heading tag="h2" heading-role="secondary">
  Heading
</gcds-heading>
```

##### `margin-bottom`

The `margin-bottom` attribute sets a margin below the heading. The default `margin-bottom` is `300`.

```html
<gcds-heading tag="h2" margin-bottom="600">
  Heading
</gcds-heading>
```

##### `margin-top`

The `margin-top` attribute sets a margin above the heading. The default `margin-top` for `h1` is set to `0`, while for `h2` to `h6` headings, it’s `600`.

```html
<gcds-heading tag="h2" margin-top="200">
  Heading
</gcds-heading>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding text to the heading.

```html
<gcds-heading tag="h2">Heading</gcds-heading>
```

### Heading
