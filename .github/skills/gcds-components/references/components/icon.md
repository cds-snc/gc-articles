# Icon

Tags: `<gcds-icon>`  
Also called: symbol, glyph, graphic  
Docs: https://design-system.canada.ca/en/components/icon/

An icon is a symbol that visually represents an action or idea.

## API (from custom-elements.json, v1.7.1)

### `<gcds-icon>`

An icon is a symbol that visually represents an action or idea.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `name` | `"arrow-down" \| "arrow-up" \| "arrow-up-down" \| "checkmark-circle" \| "chevron-down" \| "chevron-left" \| "chevron-right" \| "chevron-up" \| "close" \| "download" \| "email" \| "exclamation-circle" \| "external" \| "filter" \| "info-circle" \| "phone" \| "search" \| "sort" \| "tune" \| "warning-triangle"` |  | **yes** | Name of the icon. |
| `label` | `string` |  |  | Add icon description. |
| `margin-left` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` |  |  | Add margin to the left of the icon |
| `margin-right` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` |  |  | Add margin to the right of the icon |
| `size` | `"h1" \| "h2" \| "h3" \| "h4" \| "h5" \| "h6" \| "inherit" \| "text" \| "text-small"` | `'inherit'` |  | Defines the size of the icon. |

## Use case

### Problems icons solve

Use an icon to:

- Clarify information with a visual reference.

- Highlight key actions or states, like a checkmark for validation.

- Scan content quicker and reduce cognitive load.

### Related components

Text to display written content in a styled and formatted paragraph.

Notice to display a short, prominent message.

### Icon use cases

#### Emphasize information

Choose an icon to match the information you want to emphasize.

#### Info-circle

Non-critical but helpful information.

#### Warning-triangle

Cautionary warning with no action needed.

#### Exclamation-circle

Critical warning before an action.

#### Checkmark-circle

Confirmation of completion or successful actions.

#### Indicate action

Choose an icon to match the action you want to indicate.

#### Arrow-[up/down]

Indicate the active sort order (ascending or descending).

#### Arrow-up-down

Indicate that content items can be sorted.

#### Chevron-[left/right/up/down]

Expand / collapse content or indicate direction.

#### Close

Cancel an action or close a flow.

#### Download

Download a file.

#### Email

Send an email.

#### External-link

Open a link in a new tab or window.

#### Filter

Filter content.

#### Phone

Call a phone number.

#### Search

Search for specific information.

#### Sort

Sort content.

#### Tune

Tune or adjust settings.

## Design guidance

### Icon anatomy

- **Icon** is a symbol that visually represents an object, action, or idea.
- **Accompanying text** is a word or phrase that gives additional context.

### Design and accessibility for icon

#### Use icon intentionally

- Use an icon to assist people with site functionality.

- Use sparingly since too many icons create unnecessary distractions and reduce their effectiveness.

#### Use icon with text

- Pair an icon with accompanying text to reduce ambiguity.

- A universally understood icon can stand alone, like a search icon.

- Place an icon before text unless indicating secondary information, like dropdown arrows.

#### Write a meaningful description

- Use unique and specific accompanying text to prevent repetition for screen readers.

- Use alt-text when there’s no accompanying text.

#### Match icon and text size

- Match icon size to accompanying text, like using a small icon with small text.

- Align the icon so it’s the same height as the accompanying text.

- Use our sizing scale to ensure the icon size matches the text size.

**Text small**

**Text**

**H6**

**H5**

**H4**

**H3**

**H2**

**H1**

#### Choose an accessible colour

- Use a solid colour that matches your accompanying text.

- Check that the colour meets or exceeds WCAG 2.1 AA.

## Code guidance and examples

### Coding and accessibility for icons

#### Select a relevant icon

Render an icon by changing the `name` attribute to a corresponding icon name.

`arrow-up`
`arrow-down`
`arrow-up-down`
`info-circle`
`warning-triangle`
`exclamation-circle`
`checkmark-circle`
`chevron-left`
`chevron-right`
`chevron-up`
`chevron-down`
`close`
`download`
`email`
`external`
`filter`
`phone`
`search`
`sort`
`tune`

#### Specify the sizing scale

- Change the size of an icon by setting the `size` attribute to a specific font size, like `text-small` | `text` |  `h1` | `h2` | `h3` | `h4` | `h5` | `h6`.

- Match the icon size to its accompanying text by setting the `size` attribute to `inherit`.

- Set a minimum 24 x 24 pixel height/width for an interactive icon without text. If you can increase the size, 44 x 44 pixels exceeds accessibility standards, but is more accessible.

#### Adjust the placement

Add a margin to the left or right of an icon by setting the `margin-left` or `margin-right` attribute to a spacing value, like `0` | `25` | `50` | `75` | `100` | `125` | `150` | `175`| `200` | `225`| `250` | `300` | `350`| `400` | `450` | `500` | `550` | `600` | `650` | `700` | `750` | `800` | `850` | `900` | `950` | `1000` | `1050` | `1100` | `1150` | `1200` | `1250`.

#### Set label text

- Use the label property for an icon with no accompanying text.

- You don’t need to use the label property if an icon is decorative.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `name`

The `name` attribute defines the name of a specific icon.

```html
<gcds-icon name="close"></gcds-icon>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `label`

The `label` attribute defines the icon description for non-decorative icons.

```html
<gcds-icon
  label="Clicking this icon will close the element."
  name="close"
></gcds-icon>
```

##### `margin-left`

The `margin-left` attribute sets the margin to the left of the icon.

```html
<gcds-icon name="close" margin-left="50"></gcds-icon>
```

##### `margin-right`

The `margin-right` attribute sets the margin to the right of the icon.

```html
<gcds-icon name="close" margin-right="50"></gcds-icon>
```

##### `size`

The `size` attribute sets the size of the icon.

```html
<gcds-icon name="close" size="h3"></gcds-icon>
```
