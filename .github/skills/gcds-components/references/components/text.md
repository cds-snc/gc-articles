# Text

Tags: `<gcds-text>`  
Also called: text block, copy, caption, body text, paragraph  
Docs: https://design-system.canada.ca/en/components/text/

Text is a styled and formatted paragraph that displays written content in an accessible way.

## API (from custom-elements.json, v1.7.1)

### `<gcds-text>`

Text is a styled and formatted paragraph that displays written content in an accessible way and matches Canada.ca typography styles.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `character-limit` | `boolean` | `true` |  | Sets the line length to a maximum amount of characters per line to ensure a comfortable, accessible reading length. |
| `display` | `"block" \| "flex" \| "inline" \| "inline-block" \| "inline-flex" \| "none"` | `'block'` |  | Specifies the display behaviour of the text. |
| `margin-bottom` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` | `'300'` |  | Adds margin below the text. |
| `margin-top` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` | `'0'` |  | Adds margin above the text. |
| `size` | `"body" \| "small"` | `'body'` |  | Sets the appropriate HTML tags for the selected size. |
| `text-role` | `"light" \| "primary" \| "secondary"` | `'primary'` |  | Sets the main style of the text. |

**Slots:**

- `default` - Slot for the content of the text element.

**CSS parts:** `::part(text)`

## Use case

### Problems text solves

Use the text component to:

- Convey information in small or large blocks of written content to provide essential details about a program or service without adding a high degree of structure or emphasis.

- Insert written content after a heading with a sentence, paragraph, or caption.

- Apply consistent font sizes, colour contrast, and white space that we’ve optimized for accessibility.

- Reinforce brand identity with consistent messaging styles and formats across web pages. For example, using GC Design System typography as a standard for consistent font style.

- Divide content into understandable sections with vertical and horizontal margins. Breaking up content supports readability, simplifies site navigation, and reduces task abandonment.

### Related components

Headings for structuring content by creating levels of hierarchy that organize page content visually and mentally, using GC Design System styles.

Screenreader-only for information that is accessible for assistive technologies like screen readers, but invisible for sighted users.

### Choose a text role

A role is a text sub-type that has a specific use on a page.

#### Primary

Use the `primary` `text-role` for:

- Most text content.

- Content that is the main focus of the section.

#### Secondary

Use the `secondary` `text-role` for:

- Supporting content that is important but secondary to the main task.

- When the main idea of the section can still be understood if the reader skips the secondary text.

#### Light

Use the `light` `text-role` for:

Content that needs to appear on a dark background when the contrast ratio between the light text and dark background meets WCAG (Web Content Accessibility Guidelines) standards.

## Design guidance

### Text anatomy

- **Text** is non-heading content. Its subject matter is related to the idea outlined in its corresponding heading.
- **Heading** is a prominent line of text that introduces the paragraph or other body content that immediately follows it. The visual prominence of the heading decreases based on its level in the hierarchical order.

### Design and accessibility for text

#### Write content that’s scannable

- Structure content to place the most important information for the task first.

- Keep content brief and task-oriented. Our need to tell does not equal a reader’s need to know.

- Remove unnecessary details and explanations. Include only the information a person needs.

- Use short sentences that communicate a single idea. Be clear who or what is doing the action in each sentence.

- Avoid frontloading content that could instead be placed where a person needs it. Rather than explain a process, make the process easier to follow and understand.

Tip: Think about whether the message could be interpreted another way.

#### Use a human tone

- Talk the way the people using your product talk. Learn about them and their language by reading their requests or issues and by testing your messages with them in user research.

- Use plain, conversational language, like the tone of voice you’d use if you were describing the situation to the person directly.

- As much as possible, replace any jargon or technical terminology, like invalid code, unrecognized field, and mandatory with plainer terms. If they need to be used, give a short definition following the use of the term.

- Use sentence case ​​(only the initial letter is capitalized) and avoid caps and exclamation marks.

Tip: To adopt a more human tone, think about explaining aloud to someone. How does it sound when you speak the content in conversation?

#### Choose regular or small text

- Set the text size based on the role of the content and product context.

- Use regular text for most non-heading content. Regular text uses the default font size.

- Only use small text for supporting information like captions or footnotes.

#### Use text styling sparingly

- Only use underline for links.

- Use bold text rather than italics to emphasize key points, or to highlight terms within content.

- Limit your use of bold text to avoid compromising readability and accessibility.

- Avoid using bold text as a heading. Headings are the accessible component for identifying content sections.

- Avoid italicizing large blocks of text, entire paragraphs, or lengthy content. Italics are often difficult to read for users with visual impairments or cognitive disabilities.

- Only use italics for:

- Legal reference, like the Accessible Canada Act.
- Words in other languages, including Latin.
- Titles of publications, works of art, and mathematical and scientific material.

- Use monospace text for code, commands, file names, and other technical content that should be read or copied exactly.

## Code guidance and examples

### Coding and accessibility for text

#### Ensure accessible character limits

- Maintain the default line length maximum of 65 characters for each line of text to ensure a comfortable, accessible reading length.

- Opt to shorten the line length by setting the `character-limit` attribute to `false`.

#### Set the text size

- Set the text `size` attribute to `body` or `small`.

- Use the default `body` `size` value to render a `p` element with the recommended font size and colour contrast.

- Opt to display smaller text by setting the `size` attribute to `small` to render a `small` element inside a `p` element.

#### Use text styling sparingly

- Only use underline for links.

- Use bold text sparingly.

- Avoid using bold text as a heading. Headings are the accessible component for identifying content sections.

- For bold text, use the `strong` tag within the `gcds-text` component to highlight terms and emphasize key points.

- Only use italics for:

- Legal reference, like the Accessible Canada Act.
- Words in other languages, including Latin.
- Titles of publications, works of art, and mathematical and scientific material.

- Use the `em` tag within the `gcds-text` component to emphasize specific words or phrases.

- Use the HTML `code` element within the `gcds-text` component to wrap content that represents code. The `code` element is styled with the monospace typography token.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `character-limit`

The `character-limit` attribute sets the line length to a maximum amount of characters per line to ensure a comfortable, accessible reading length.

```html
<gcds-text character-limit="false">
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```

##### `display`

The `display` attribute sets the display behaviour of the text. The available options are:

- `block` (default)

- `flex`

- `inline`

- `inline-block`

- `inline-flex`

- `none`

```html
<gcds-text display="flex">
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```

##### `margin-bottom`

The `margin-bottom` attribute sets a margin below the text. The default `margin-bottom` is `300`.

```html
<gcds-text margin-bottom="600">
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```

##### `margin-top`

The `margin-top` attribute sets a margin above the text. The default `margin-top` is `0`.

```html
<gcds-text margin-top="600">
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```

##### `size`

The `size` attribute sets the text size. The available options are:

- `body` (default)

- `small`

```html
<gcds-text size="small">
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```

##### `text-role`

The `text-role` attribute sets the main style of the text. The available options are:

- `primary` (default)

- `secondary`

- `light`

```html
<gcds-text text-role="secondary">
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing the content of the text element.

```html
<gcds-text>
This is text that's related to the heading and describes in further detail the heading's subject or action. Text is typically one or several lines or paragraphs that form a section's content or introduce a bulleted list, table, or other content, like an image or video.
</gcds-text>
```
