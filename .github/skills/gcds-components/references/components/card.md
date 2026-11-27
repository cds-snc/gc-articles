# Card

Tags: `<gcds-card>`  
Also called: tile, content card  
Docs: https://design-system.canada.ca/en/components/card/

A card is a navigational container with structured content on a single topic.

## API (from custom-elements.json, v1.7.1)

### `<gcds-card>`

A card is a box containing structured, actionable content on a single topic.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `card-title` | `string` |  | **yes** | The card title attribute specifies the title that appears on the card |
| `href` | `string` |  | **yes** | The href attribute specifies the URL of the page the link goes to |
| `badge` | `string` |  |  | The badge attribute specifies the badge text that appears in the top left corner of the card. 20 character limit. |
| `card-title-tag` | `"h3" \| "h4" \| "h5" \| "h6"` |  |  | The card title tag property specifies the HTML heading element for the title. This property does not modify the font size. It is used to assign the heading level in order to maintain heading hierarchy and accessibility for assistive technologies. |
| `description` | `string` |  |  | The description attribute specifies the body of text that appears on the card |
| `img-alt` | `string` |  |  | The img alt attribute specifies the alt text for the image provided, if none, image will be decorative |
| `img-src` | `string` |  |  | The img src attribute specifies the path to the image |
| `rel` | `string` |  |  | The rel attribute specifies the relationship between the current document and the linked document |
| `target` | `string` |  |  | The target attribute specifies where to open the linked document |

**Slots:**

- `default` - Slot for the card description. Accepts rich text and overwrites the description prop if used. The description prop is mirrored into this slot for the same DOM-text reason when nothing is slotted.
- `title` - Slot for the card title. Accepts rich text, so markup such as `<abbr>`, `<em>` or an icon can be used where the card-title prop cannot. Falls back to the card-title prop, which is mirrored into the light DOM so the title text stays readable by DOM-text extraction tools.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the card loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the card has been clicked. Contains the href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the card has focus.

## Use case

Review these common uses for cards to see if they match the problem you’re solving for.

### Problems cards solve

Cards organize information on the same subject in a static box shaped like a playing card.

Use a card to:

- Group small pieces of related information as a unit.

- Present bite-sized previews and summaries of information with a link to navigate to more details elsewhere.

- Support content discovery by creating more visual interest.

Note: For Canada.ca, avoid using cards in place of the doormats with the templates for landing page and theme and topic page.

### Related components

Details to hide secondary information that a person can expand on the same page.

Container for basic layouts with a set width.

## Design guidance

### Card anatomy

- The **container** holds all of a card’s elements.
- The **badge** (optional) is a visual indicator to inform users of the card’s status or category. Badge text should be no more than 20 characters.
- The **image** (optional) is a 1:1, 16:9, or 4:3 ratio visual.
- The **headline** is a short attention-grabbing title about the card’s content.
- The **description** (optional) has text that supports the card title. It may include a few lines of summary on the card’s topic or a list of details about the same subject, like a list of event details.

### Design and accessibility for cards

#### Write a scannable card title

- Use a meaningful title to inform users of what they’ll find on the card.

- Choose a brief description or call-to-action statement for the title.

#### Choose what information to add to the card

- Consider adding an image related to the title to make individual cards more identifiable.

- Consider adding a description to provide more information about the card’s topic.

- Consider adding a badge to inform users of the status or the category of the card’s content. Common examples of statuses include: “New”, “Updated”, “Coming soon” and “Featured”. Common examples of categories include: “videos”, “articles”, “reports”. The badges are customizable but are limited to 20 characters maximum.

#### Group cards for visual consistency

Organize cards within a grid component:

- For desktop screens, use either 2 or 3 cards per row.

- For mobile screens, use 1 card per row.

- Try to include the same properties for each card within a section on a page. For example, if one card has an image, then all cards get an image.

## Code guidance and examples

### Coding and accessibility for cards

#### Write a scannable card title

Use the `card-title` attribute to add a short and descriptive title to the card.

#### Select the correct heading level

Set the `card-title-tag` to define the correct heading level for the card title. While the font size remains the same regardless of the tag, setting the correct heading level maintains proper hierarchy and accessibility for assistive technologies.

#### Add a `description` to provide more information

To provide more information on the card’s topic, add a description to the card:

- Use the `description` attribute to provide a concise text description.

- For a description with more complex formatting, like bold or italics text, use the card’s default `slot`. **Avoid adding any interactive elements into the slot, as they ‌can cause accessibility issues. The card itself is already interactive and will take the person to the correct location.**

#### Use a `badge` to show the status or category of a card

- Use the `badge` attribute to add a status or a category to the top left corner of the card.

- Common examples of statuses include: “New”, “Updated”, “Coming soon” and “Featured”.

- Common examples of categories include: “videos”, “articles”, “reports”.

- The badges are customizable but are limited to 20 characters maximum.

#### Group multiple cards for visual consistency

Organize cards within a grid component:

- For desktop screens, use either 2 or 3 cards per row.

- For mobile screens, use 1 card per row.

- Try to include the same properties for each card within a section on a page. For example, if one card has an image, then all cards get an image.

#### Use valid properties

Component properties have built-in validation. Required properties or properties prone to misuse will be validated. If there are any validation errors, the component will be non-functional and won’t render. Review the browser’s console for details and adjust the properties accordingly to ensure proper functionality.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `card-title`

The `card-title` attribute defines the title that appears on the card.

```html
<gcds-card
  card-title="Card title link"
  href="#"
></gcds-card>
```

##### `href`

The `href` attribute specifies the URL of the page the link goes to.

```html
<gcds-card
  card-title="Card title link"
  href="#"
></gcds-card>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `badge`

The `badge` attribute defines the badge text that appears in the top left corner of the card. The badge has a 20 character limit.

```html
<gcds-card
  card-title="Card title link"
  badge="Tag"
  href="#"
></gcds-card>
```

##### `card-title-tag`

The `card-title-tag` attribute sets the HTML heading element for the title. This attribute does not modify the font size. It is used to assign the heading level in order to maintain heading hierarchy and accessibility for assistive technologies. The available options are:

- `h3`

- `h4`

- `h5`

- `h6`

```html
<gcds-card
  card-title="Card title link"
  card-title-tag="h3"
  href="#"
></gcds-card>
```

##### `description`

The `description` attribute defines the body of text that appears on the card.

```html
<gcds-card
  card-title="Card title link"
  href="#"
  description="Description or supporting text relating to the headline."
></gcds-card>
```

##### `img-alt`

The `img-alt` attribute defines the alt text for the image provided. If no img-alt is specified, the image will be decorative.

```html
<gcds-card
  card-title="Card title link"
  href="#"
  img-alt="The card component shows a box containing a rectangular image in the top half. Immediately below is a blue rectangular bar running across three quarters of the box representing the card title. Two longer grey bars are stacked just below the blue bar, representing the card description and context area/metadata sections."
  img-src="/images/common/components/preview-card.svg"
></gcds-card>
```

##### `img-src`

The `img-src` attribute specifies the URL or file path of the image to display.

```html
<gcds-card
  card-title="Card title link"
  href="#"
  img-alt="The card component shows a box containing a rectangular image in the top half. Immediately below is a blue rectangular bar running across three quarters of the box representing the card title. Two longer grey bars are stacked just below the blue bar, representing the card description and context area/metadata sections."
  img-src="/images/common/components/preview-card.svg"
></gcds-card>
```

##### `rel`

The `rel` attribute specifies the relationship between the current document and the linked document. It is commonly used for security and privacy purposes, especially when links open in a new tab or window.

```html
<gcds-card
  card-title="Card title link"
  href="#"
  rel="noopener noreferrer"
></gcds-card>
```

##### `target`

The target attribute specifies where the linked resource is opened. For example, it can open the link in the same browsing context or in a new tab or window.

```html
<gcds-card
  card-title="Card title link"
  href="#"
  target="_blank"
></gcds-card>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding description content to the card. It will overwrite the description attribute if it’s used.

```html
<gcds-card card-title="Card title link" href="#">
  Description or supporting text relating to the headline.
</gcds-card>
```
