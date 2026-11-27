# CSS Shortcuts reference

Utility classes from `@gcds-core/css-shortcuts@1.2.0`. Use them for custom markup that no GCDS component covers. Values come from GCDS design tokens. Every class can take a responsive prefix (`xs:`, `sm:`, `md:`, `lg:`, `xl:`), and some take state prefixes (see *State*).

Docs: https://design-system.canada.ca/en/css-shortcuts/

## Reset styles

CSS Shortcuts includes reset styles in its stylesheet. The resets remove inconsistent browser styles and apply default styles that match GC Design System. They display elements predictably and consistently across browsers.

By default, these styles follow Canada.ca standards and provide a consistent foundation for basic HTML elements. Some elements may still require additional styling depending on your needs.

## Responsive layout

The responsive layout prefix applies a class only to the specified screen size. It can be added to any class. A class without a prefix will apply to all screen sizes.

Adding breakpoints directly to the classes lets you to create responsive layouts without writing custom CSS.

| CSS Shortcut class | Applied style | Minimum width |
|---|---|---|
| `xs:` | `@media (width > 30em) { ... }` | `480px` |
| `sm:` | `@media (width > 40em) { ... }` | `640px` |
| `md:` | `@media (width > 48em) { ... }` | `768px` |
| `lg:` | `@media (width > 64em) { ... }` | `1024px` |
| `xl:` | `@media (width > 80em) { ... }` | `1280px` |

## State

The state prefix applies a class based on the specified interaction state. A class without a prefix will apply to all states.

Adding the state prefix lets you create dynamic and user-friendly interfaces without writing custom CSS for each interaction.

| CSS Shortcut class | Applied style |
|---|---|
| `hover:` | `.hover:<class>:hover {   <property-name>: <property-value>; }` |
| `focus:` | `.focus:<class>:focus {   <property-name>: <property-value>; }` |

## Box sizing

The box sizing class sets the `box-sizing` property. It specifies if the height and width of an element includes borders and padding.

| CSS Shortcut class | Applied style |
|---|---|
| `box-border` | `box-sizing: border-box;` |
| `box-content` | `box-sizing: content-box;` |

## Container sizing

The container class sets the `max-width` property. It changes the size of a container at different viewport sizes to create responsive layouts.

| CSS Shortcut class | Applied style |
|---|---|
| `container-xs` | `max-width: 20rem` |
| `container-sm` | `max-width: 30rem` |
| `container-md` | `max-width: 48rem` |
| `container-lg` | `max-width: 62rem` |
| `container-xl` | `max-width: 71.25rem` |
| `container-full` | `max-width: 100%` |

## Display

The display class sets the `display` property. It changes how containers are arranged on a page.

| CSS Shortcut class | Applied style |
|---|---|
| `d-block` | `display: block;` |
| `d-inline` | `display: inline;` |
| `d-inline-block` | `display: inline-block;` |
| `d-flex` | `display: flex;` |
| `d-inline-flex` | `display: inline-flex;` |
| `d-grid` | `display: grid;` |
| `d-inline-grid` | `display: inline-grid;` |
| `d-table d-table-row d-table-cell` | `display: table; display: table-row; display: table-cell;` |
| `d-inline-table d-table-row d-table-cell` | `display: inline-table; display: table-row; display: table-cell;` |
| `d-none` | `display: none;` |

## Overflow

The overflow class sets the `overflow` property. It controls how content is handled when it does not fit in its container and overflows.

| CSS Shortcut class | Applied style |
|---|---|
| `overflow-visible` | `overflow: visible;` |
| `overflow-x-visible` | `overflow-x: visible;` |
| `overflow-y-visible` | `overflow-y: visible;` |
| `overflow-hidden` | `overflow: hidden;` |
| `overflow-x-hidden` | `overflow-x: hidden;` |
| `overflow-y-hidden` | `overflow-y: hidden;` |
| `overflow-scroll` | `overflow: scroll;` |
| `overflow-x-scroll` | `overflow-x: scroll;` |
| `overflow-y-scroll` | `overflow-y: scroll;` |

## Position

The position class sets the `position` property. It determines how the element is positioned in the document.

| CSS Shortcut class | Applied style |
|---|---|
| `position-static` | `position: static;` |
| `position-absolute` | `position: absolute;` |
| `position-fixed` | `position: fixed;` |
| `position-relative` | `position: relative;` |
| `position-sticky` | `position: sticky;` |

## Visibility

The visibility class sets the `visibility` property. It displays or hides an element without affecting the layout or flow of the document.

| CSS Shortcut class | Applied style |
|---|---|
| `visibility-hidden` | `visibility: hidden;` |
| `visibility-visible` | `visibility: visible;` |
| `visibility-sr-only` | `clip-path: inset(100%); clip: rect(1px, 1px, 1px, 1px); height: 1px; overflow: hidden; position: absolute; white-space: nowrap; width: 1px;` |

## Font

The font class sets the `font` property. It controls the font style, size, weight, family and line height of a text element.

In most cases you do not need to use this class. Body text and heading elements are styled to match GCDS by default. Use semantic HTML tags , like `h1`, `h2` whenever possible.

| CSS Shortcut class | Applied style |
|---|---|
| `font-h1` | `font: var(--gcds-font-h1);  @media only screen and (width < 48em) {  font: var(--gcds-font-h1-mobile); }` |
| `font-h2` | `font: var(--gcds-font-h2);  @media only screen and (width < 48em) {  font: var(--gcds-font-h2-mobile); }` |
| `font-h3` | `font: var(--gcds-font-h3);  @media only screen and (width < 48em) {  font: var(--gcds-font-h3-mobile); }` |
| `font-h4` | `font: var(--gcds-font-h4);  @media only screen and (width < 48em) {  font: var(--gcds-font-h4-mobile); }` |
| `font-h5` | `font: var(--gcds-font-h5);  @media only screen and (width < 48em) {  font: var(--gcds-font-h5-mobile); }` |
| `font-h6` | `font: var(--gcds-font-h6);  @media only screen and (width < 48em) {  font: var(--gcds-font-h6-mobile); }` |
| `font-text` | `font: var(--gcds-font-text);  @media only screen and (width < 48em) {  font: var(--gcds-font-text-mobile); }` |
| `font-text-small` | `font: var(--gcds-font-text-small);  @media only screen and (width < 48em) {  font: var(--gcds-font-text-small-mobile); }` |

## Font family

The font family class sets the `font-family` property. It controls the typeface of a text element.

In most cases you do not need to use this class. Body text and heading elements are styled to match GCDS by default. Use semantic HTML tags , like `h1`, `h2` whenever possible.

| CSS Shortcut class | Applied style |
|---|---|
| `font-family-heading` | `font-family: var(--gcds-font-families-heading);` |
| `font-family-body` | `font-family: var(--gcds-font-families-body);` |
| `font-family-monospace` | `font-family: var(--gcds-font-families-monospace);` |

## Font size

The font size class sets the `font-size` property. It controls how big or small text elements appear on the page.

In most cases you do not need to use this class. Body text and heading elements are styled to match GCDS by default. Use semantic HTML tags , like `h1`, `h2` whenever possible.

| CSS Shortcut class | Applied style |
|---|---|
| `font-size-h1` | `font-size: var(--gcds-font-sizes-h1);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-h1-mobile); }` |
| `font-size-h2` | `font-size: var(--gcds-font-sizes-h2);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-h2-mobile); }` |
| `font-size-h3` | `font-size: var(--gcds-font-sizes-h3);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-h3-mobile); }` |
| `font-size-h4` | `font-size: var(--gcds-font-sizes-h4);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-h4-mobile); }` |
| `font-size-h5` | `font-size: var(--gcds-font-sizes-h5);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-h5-mobile); }` |
| `font-size-h6` | `font-size: var(--gcds-font-sizes-h6);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-h6-mobile); }` |
| `font-size-text` | `font-size: var(--gcds-font-sizes-text);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-text-mobile); }` |
| `font-size-text-small` | `font-size: var(--gcds-font-sizes-text-small);  @media only screen and (width < 48em) {  font-size: var(--gcds-font-sizes-text-small-mobile); }` |

## Font style

The font style class sets the `font-style` property. It adds or removes italic styling of text.

View GCDS typography guidance to choose the right font style for your context.

| CSS Shortcut class | Applied style |
|---|---|
| `font-normal` | `font-style: normal;` |
| `font-italic` | `font-style: italic;` |

## Font weight

The font weight class sets the `font-weight` property. It controls how bold text is.

View GCDS typography guidance to choose the right font weight for your context.

| CSS Shortcut class | Applied style |
|---|---|
| `font-regular` | `font-weight: var(--gcds-font-weights-regular);` |
| `font-light` | `font-weight: var(--gcds-font-weights-light);` |
| `font-medium` | `font-weight: var(--gcds-font-weights-medium);` |
| `font-semibold` | `font-weight: var(--gcds-font-weights-semibold);` |
| `font-bold` | `font-weight: var(--gcds-font-weights-bold);` |

## Line height

The line height class sets the `line-height` property. It controls the vertical spacing between lines of text.

In most cases you do not need to use this class. Body text and heading elements are styled to match GCDS by default. Use semantic HTML tags , like `h1`, `h2` whenever possible.

| CSS Shortcut class | Applied style |
|---|---|
| `line-h1` | `line-height: var(--gcds-line-heights-h1);` |
| `line-h2` | `line-height: var(--gcds-line-heights-h2);` |
| `line-h3` | `line-height: var(--gcds-line-heights-h3);` |
| `line-h4` | `line-height: var(--gcds-line-heights-h4);` |
| `line-h5` | `line-height: var(--gcds-line-heights-h5);` |
| `line-h6` | `line-height: var(--gcds-line-heights-h6);` |
| `line-text` | `line-height: var(--gcds-line-heights-text);` |
| `line-text-small` | `line-height: var(--gcds-line-heights-text-small);` |

## Link colour

The link colour class sets the `color` property. It controls the colour of a link.

View GCDS colour guidance to choose the right link colour for your context.

| CSS Shortcut class | Applied style |
|---|---|
| `link-default` | `color: var(--gcds-link-default);` |
| `link-hover` | `color: var(--gcds-link-hover);` |
| `link-inherit` | `color: var(--gcds-link-inherit);` |
| `link-light` | `color: var(--gcds-link-light);` |

## Link size

The link size class sets the `font` property. It controls how big or small a link is.

In most cases you do not need to use this class. Links are styled to match GCDS by default. Use the semantic HTML tag `a` or the GCDS component `gcds-link` whenever possible.

| CSS Shortcut class | Applied style |
|---|---|
| `link-size-regular` | `font: var(--gcds-link-font-regular-desktop);  @media only screen and (width < 48em) {  font: var(--gcds-link-font-regular-mobile); }` |
| `link-size-small` | `font: var(--gcds-link-font-small-desktop);  @media only screen and (width < 48em) {  font: var(--gcds-link-font-small-mobile); }` |
| `link-size-inherit` | `font: inherit;  @media only screen and (width < 48em) {  font: inherit; }` |

## Link text decoration

The link text decoration class sets the `text-decoration` property. It adds or removes an underline to a link.

| CSS Shortcut class | Applied style |
|---|---|
| `link-underline` | `text-decoration: underline; text-underline-position: under;` |
| `link-no-underline` | `text-decoration: none;` |

## List style

The list style class sets the `list-style` property. It controls the appearance of list item markers.

| CSS Shortcut class | Applied style |
|---|---|
| `list-circle` | `list-style: circle; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-disc` | `list-style: disc; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-square` | `list-style: square; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-decimal` | `list-style: decimal; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-decimal-leading-zero` | `list-style: decimal-leading-zero; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-upper-roman` | `list-style: upper-roman; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-lower-roman` | `list-style: lower-roman; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-upper-alpha` | `list-style: upper-alpha; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-lower-alpha` | `list-style: lower-alpha; margin: 0 0 0 var(--gcds-spacing-400);` |
| `list-none` | `list-style: none;` |

## Text align

The text align class sets the `text-align` property. It controls the horizontal alignment of text within its container.

| CSS Shortcut class | Applied style |
|---|---|
| `text-center` | `text-align: center;` |
| `text-justify` | `text-align: justify;` |
| `text-left` | `text-align: left;` |
| `text-right` | `text-align: right;` |

## Text colour

The text colour class sets the `color` property. It controls the colour of text.

View GCDS colour guidance to choose the right text colour for your context.

| CSS Shortcut class | Applied style |
|---|---|
| `text-primary` | `color: var(--gcds-text-primary);` |
| `text-secondary` | `color: var(--gcds-text-secondary);` |
| `text-current` | `color: currentColor;` |
| `text-light` | `color: var(--gcds-text-light);` |
| `text-transparent` | `color: transparent;` |

## Text overflow

The text overflow class sets the `text-overflow` property. It controls if overflow text is clipped with or without an ellipsis.

| CSS Shortcut class | Applied style |
|---|---|
| `text-clip` | `overflow: hidden; text-overflow: clip; white-space: nowrap;` |
| `text-truncate` | `overflow: hidden; text-overflow: ellipsis; white-space: nowrap;` |

## Text transform

The text transform class sets the `text-transform` property. It controls the capitalization patterns of text.

| CSS Shortcut class | Applied style |
|---|---|
| `text-capitalize` | `text-transform: capitalize;` |
| `text-lowercase` | `text-transform: lowercase;` |
| `text-uppercase` | `text-transform: uppercase;` |
| `text-none` | `text-transform: none;` |

## Word break

The word break class sets the `word-break` property. It controls how words or unbreakable strings break when reaching the end of a line.

| CSS Shortcut class | Applied style |
|---|---|
| `break-normal` | `word-break: normal;` |
| `break-word` | `word-break: break-word;` |
| `break-all` | `word-break: break-all;` |

## Margin

The margin class sets the `margin` property. It controls the space outside the border of an element.

Logical properties adapt to the writing mode and text direction. Instead of literal directions like "left", "right", "top", or "bottom", they use flow-relative terms.

This flexibility prepares layouts for internationalization without needing manual adjustments for different text directions.

The `value` can be either one of our predefined spacing tokens (`0` - `1250`) or `auto`.

| CSS Shortcut class | Applied style | Legacy property |
|---|---|---|
| `m-<value>` | `margin: var(--gcds-spacing-<value>);` | `margin` |
| `mt-<value>` | `margin-block-start: var(--gcds-spacing-<value>);` | `margin-top` |
| `mb-<value>` | `margin-block-end: var(--gcds-spacing-<value>);` | `margin-bottom` |
| `ms-<value>` | `margin-inline-start: var(--gcds-spacing-<value>);` | `margin-left` |
| `me-<value>` | `margin-inline-end: var(--gcds-spacing-<value>);` | `margin-right` |
| `mx-<value>` | `margin-inline: var(--gcds-spacing-<value>);` | `margin-left + margin-right` |
| `my-<value>` | `margin-block: var(--gcds-spacing-<value>);` | `margin-top + margin-bottom` |

## Padding

The padding sets the `padding` property. It controls the space inside an element between its content and border.

Logical properties adapt to the writing mode and text direction. Instead of literal directions like "left", "right", "top", or "bottom", they use flow-relative terms.

This flexibility prepares layouts for internationalization without needing manual adjustments for different text directions.

The `value` can be either one of our predefined spacing tokens (`0` - `1250`).

| CSS Shortcut class | Applied style | Legacy property |
|---|---|---|
| `p-<value>` | `padding: var(--gcds-spacing-<value>);` | `padding` |
| `pt-<value>` | `padding-block-start: var(--gcds-spacing-<value>);` | `padding-top` |
| `pb-<value>` | `padding-block-end: var(--gcds-spacing-<value>);` | `padding-bottom` |
| `ps-<value>` | `padding-inline-start: var(--gcds-spacing-<value>);` | `padding-left` |
| `pe-<value>` | `padding-inline-end: var(--gcds-spacing-<value>);` | `padding-right` |
| `px-<value>` | `padding-inline: var(--gcds-spacing-<value>);` | `padding-left + padding-right` |
| `py-<value>` | `padding-block: var(--gcds-spacing-<value>);` | `padding-top + padding-bottom` |

## Align content

The align content class sets the `align-content` property. It controls how extra space is distributed between items in a flex or grid container along the cross axis.

In flexbox, the `flex-direction` property sets the main and cross axis. The effects of `align-content` change based on the container's direction.

| CSS Shortcut class | Applied style |
|---|---|
| `align-content-around` | `align-content: space-around;` |
| `align-content-between` | `align-content: space-between;` |
| `align-content-evenly` | `align-content: space-evenly;` |
| `align-content-center` | `align-content: center;` |
| `align-content-start` | `align-content: start;` |
| `align-content-end` | `align-content: end;` |

## Align items

The align items class sets the `align-items` property. It controls how items are aligned along the cross axis of a flex or grid container.

In flexbox, the `flex-direction` property sets the main and cross axis. The effects of `align-items` change based on the container's direction.

| CSS Shortcut class | Applied style |
|---|---|
| `align-items-baseline` | `align-items: baseline;` |
| `align-items-center` | `align-items: center;` |
| `align-items-stretch` | `align-items: stretch;` |
| `align-items-start` | `align-items: start;` |
| `align-items-end` | `align-items: end;` |

## Align self

The align self class sets the `align-self` property. It controls how an individual item aligns itself along the container’s cross axis, allowing it to override the container’s `align-items` setting for that specific item.

In flexbox, the `flex-direction` property sets the main and cross axis. The effects of `align-self` change based on the container's direction.

| CSS Shortcut class | Applied style |
|---|---|
| `align-self-auto` | `align-self: auto;` |
| `align-self-baseline` | `align-self: baseline;` |
| `align-self-center` | `align-self: center;` |
| `align-self-stretch` | `align-self: stretch;` |
| `align-self-start` | `align-self: start;` |
| `align-self-end` | `align-self: end;` |

## Flex

The flex class sets the `flex` shorthand property on a flex item. It controls how the item grows, shrinks, and takes up space relative to its siblings inside a flex container.

| CSS Shortcut class | Applied style |
|---|---|
| `flex-1` | `flex: 1 1 0%;` |
| `flex-auto` | `flex: 1 1 auto;` |
| `flex-initial` | `flex: 0 1 auto;` |
| `flex-none` | `flex: none;` |

## Flex direction

The flex direction class sets the `flex-direction` property. It defines the main axis along which the flex items are placed. This determines if their layout direction is horizontal (row) or vertical (column).

| CSS Shortcut class | Applied style |
|---|---|
| `flex-col` | `flex-direction: column;` |
| `flex-col-reverse` | `flex-direction: column-reverse;` |
| `flex-row` | `flex-direction: row;` |
| `flex-row-reverse` | `flex-direction: row-reverse;` |

## Flex grow

The flex grow class sets the `flex-grow` property. It controls how a flex item will grow relative to other items when extra space is available in the container.

| CSS Shortcut class | Applied style |
|---|---|
| `flex-grow` | `flex-grow: 1;` |
| `flex-grow-0` | `flex-grow: 0;` |

## Flex shrink

The flex shrink class sets the `flex-shrink` property. It controls how much a flex item shrinks when there isn’t enough space in the container.

| CSS Shortcut class | Applied style |
|---|---|
| `flex-shrink` | `flex-shrink: 1;` |
| `flex-shrink-0` | `flex-shrink: 0;` |

## Flex wrap

The flex wrap class sets the `flex-wrap` property. It controls how flex items wrap onto multiple lines if there isn’t enough space for them.

In flexbox, the `flex-direction` property sets the main and cross axis. The effects of `flex-wrap` change based on the container's direction.

| CSS Shortcut class | Applied style |
|---|---|
| `flex-wrap` | `flex-wrap: wrap;` |
| `flex-wrap-reverse` | `flex-wrap: wrap-reverse;` |
| `flex-nowrap` | `flex-wrap: nowrap;` |

## Gap

The gap class sets the `gap`, `column-gap` or `row-gap` properties. It controls the spacing between items in a grid or flex container.

The `value` can be either one of our predefined spacing tokens (`0` - `1250`).

| CSS Shortcut class | Applied style |
|---|---|
| `gap-<value>` | `gap: var(--gcds-spacing-<value>);` |
| `gap-x-<value>` | `column-gap: var(--gcds-spacing-<value>);` |
| `gap-y-<value>` | `row-gap: var(--gcds-spacing-<value>);` |

## Grid columns

The grid columns class sets the `grid-template-columns` property. It controls how space in a grid container is divided into columns. It sets how wide each column should be and how many columns the grid will have.

| CSS Shortcut class | Applied style |
|---|---|
| `grid-cols-1` | `grid-template-columns: repeat(1, minmax(0, 1fr));` |
| `grid-cols-2` | `grid-template-columns: repeat(2, minmax(0, 1fr));` |
| `grid-cols-3` | `grid-template-columns: repeat(3, minmax(0, 1fr));` |
| `grid-cols-4` | `grid-template-columns: repeat(4, minmax(0, 1fr));` |
| `grid-cols-5` | `grid-template-columns: repeat(5, minmax(0, 1fr));` |
| `grid-cols-6` | `grid-template-columns: repeat(6, minmax(0, 1fr));` |
| `grid-cols-7` | `grid-template-columns: repeat(7, minmax(0, 1fr));` |
| `grid-cols-8` | `grid-template-columns: repeat(8, minmax(0, 1fr));` |
| `grid-cols-9` | `grid-template-columns: repeat(9, minmax(0, 1fr));` |
| `grid-cols-10` | `grid-template-columns: repeat(10, minmax(0, 1fr));` |
| `grid-cols-11` | `grid-template-columns: repeat(11, minmax(0, 1fr));` |
| `grid-cols-12` | `grid-template-columns: repeat(12, minmax(0, 1fr));` |
| `grid-cols-none` | `grid-template-columns: none;` |

## Grid rows

The grid rows class sets the `grid-template-rows` property. It controls how space in a grid container is divided into rows. It sets how many rows the grid will have and the height of each row.

| CSS Shortcut class | Applied style |
|---|---|
| `grid-rows-1` | `grid-template-rows: repeat(1, minmax(0, 1fr));` |
| `grid-rows-2` | `grid-template-rows: repeat(2, minmax(0, 1fr));` |
| `grid-rows-3` | `grid-template-rows: repeat(3, minmax(0, 1fr));` |
| `grid-rows-4` | `grid-template-rows: repeat(4, minmax(0, 1fr));` |
| `grid-rows-5` | `grid-template-rows: repeat(5, minmax(0, 1fr));` |
| `grid-rows-6` | `grid-template-rows: repeat(6, minmax(0, 1fr));` |
| `grid-rows-7` | `grid-template-rows: repeat(7, minmax(0, 1fr));` |
| `grid-rows-8` | `grid-template-rows: repeat(8, minmax(0, 1fr));` |
| `grid-rows-9` | `grid-template-rows: repeat(9, minmax(0, 1fr));` |
| `grid-rows-10` | `grid-template-rows: repeat(10, minmax(0, 1fr));` |
| `grid-rows-11` | `grid-template-rows: repeat(11, minmax(0, 1fr));` |
| `grid-rows-12` | `grid-template-rows: repeat(12, minmax(0, 1fr));` |
| `grid-rows-none` | `grid-template-rows: none;` |

## Justify content

The justify content class sets the `justify-content` property. It controls how extra space is distributed between items in a flex or grid container along the main axis.

In flexbox, the `flex-direction` property sets the main and cross axis. The effects of `justify-content` change based on the container's direction.

| CSS Shortcut class | Applied style |
|---|---|
| `justify-content-around` | `justify-content: space-around;` |
| `justify-content-between` | `justify-content: space-between;` |
| `justify-content-evenly` | `justify-content: space-evenly;` |
| `justify-content-center` | `justify-content: center;` |
| `justify-content-start` | `justify-content: flex-start;` |
| `justify-content-end` | `justify-content: flex-end;` |

## Justify items

The justify items class sets the `justify-items` property. It controls how individual items are aligned along the inline (row) axis within their grid areas. It determines the horizontal positioning of content inside each grid cell.

In flexbox, `justify-items` is ignored, so it has no effect.

| CSS Shortcut class | Applied style |
|---|---|
| `justify-items-center` | `justify-items: center;` |
| `justify-items-stretch` | `justify-items: stretch;` |
| `justify-items-start` | `justify-items: start;` |
| `justify-items-end` | `justify-items: end;` |

## Justify self

The justify self class sets the `justify-self` property. It controls how an individual grid item is aligned along the inline (row) axis within its grid area, allowing it to override the container’s `justify-items` setting for that specific item.

In flexbox, `justify-self` is ignored, so it has no effect.

| CSS Shortcut class | Applied style |
|---|---|
| `justify-self-auto` | `justify-self: auto;` |
| `justify-self-center` | `justify-self: center;` |
| `justify-self-stretch` | `justify-self: stretch;` |
| `justify-self-start` | `justify-self: start;` |
| `justify-self-end` | `justify-self: end;` |

## Order

The order class sets the `order` property. It controls the visual order of items within a flex container. By default, flex items follow the order in which they appear in the HTML. The `order` property can change their position without modifying the HTML structure.

Using the `order` property changes the visual order of flex items without altering their order in the HTML. If the visual and reading orders don't match, it can cause confusion for people who use keyboard navigation or screenreaders. Test with assistive technologies to ensure information is clear and accessible.

| CSS Shortcut class | Applied style |
|---|---|
| `order-1` | `order: 1;` |
| `order-2` | `order: 2;` |
| `order-3` | `order: 3;` |
| `order-4` | `order: 4;` |
| `order-5` | `order: 5;` |
| `order-6` | `order: 6;` |
| `order-7` | `order: 7;` |
| `order-8` | `order: 8;` |
| `order-9` | `order: 9;` |
| `order-10` | `order: 10;` |
| `order-11` | `order: 11;` |
| `order-12` | `order: 12;` |
| `order-first` | `order: -9999;` |
| `order-last` | `order: 9999;` |
| `order-none` | `order: 0;` |

## Place content

The place content class sets the `place-content` property. It is a shorthand for setting both cross axis and main axis. It controls how extra space is distributed between rows and columns in multi-line flex or grid containers.

In flexbox, the `flex-direction` property sets the main and cross axis. The effects of `place-content` change based on the container's direction.

| CSS Shortcut class | Applied style |
|---|---|
| `place-content-around` | `place-content: space-around;` |
| `place-content-between` | `place-content: space-between;` |
| `place-content-evenly` | `place-content: space-evenly;` |
| `place-content-center` | `place-content: center;` |
| `place-content-stretch` | `place-content: stretch;` |
| `place-content-start` | `place-content: start;` |
| `place-content-end` | `place-content: end;` |

## Place items

The place items class sets the `place-items` property. It is a shorthand for setting the cross axis and the main axis. It controls how items are aligned inside their grid areas along both axes in a grid container.

In flexbox, `justify-items` is ignored, so `place-items` has no effect.

| CSS Shortcut class | Applied style |
|---|---|
| `place-items-center` | `place-items: center;` |
| `place-items-stretch` | `place-items: stretch;` |
| `place-items-start` | `place-items: start;` |
| `place-items-end` | `place-items: end;` |

## Place self

The place self class sets the `place-self` property. It is a shorthand for setting both the cross axis the main axis on a single grid item. It overrides the container’s `place-items` setting and controls how that individual item is aligned within its grid area.

In flexbox, `justify-self` is ignored, so `place-self` has no effect.

| CSS Shortcut class | Applied style |
|---|---|
| `place-self-auto` | `place-self: auto;` |
| `place-self-center` | `place-self: center;` |
| `place-self-stretch` | `place-self: stretch;` |
| `place-self-start` | `place-self: start;` |
| `place-self-end` | `place-self: end;` |

## Background colour

The background colour class sets the `background-color` property. It controls the colour of an element’s background.

View GCDS colour guidance to choose the right background colour for your context.

| CSS Shortcut class | Applied style |
|---|---|
| `bg-primary` | `background-color: var(--gcds-bg-primary);` |
| `bg-active` | `background-color: var(--gcds-active-background);` |
| `bg-black` | `background-color: var(--gcds-color-grayscale-1000);` |
| `bg-current` | `background-color: currentColor;` |
| `bg-danger` | `background-color: var(--gcds-danger-background);` |
| `bg-dark` | `background-color: var(--gcds-bg-dark);` |
| `bg-disabled` | `background-color: var(--gcds-disabled-background);` |
| `bg-inherit` | `background-color: inherit;` |
| `bg-light` | `background-color: var(--gcds-bg-light);` |
| `bg-transparent` | `background-color: transparent;` |
| `bg-white` | `background-color: var(--gcds-bg-white);` |

## Border colour

The border colour class sets the `border-colour` property. It controls the colour of an element’s border.

| CSS Shortcut class | Applied style |
|---|---|
| `b-default` | `border-color: #7d828b;` |
| `b-current` | `border-color: currentColor;` |
| `b-dark` | `border-color: #000000;` |
| `b-inherit` | `border-color: inherit;` |
| `b-light` | `border-color: #ffffff;` |
| `b-transparent` | `border-color: transparent;` |

## Border radius

The border radius class sets the `border-radius` property. It controls how round the corners of an element’s border appear.

| CSS Shortcut class | Applied style |
|---|---|
| `b-radius-sm` | `border-radius: 0.125rem;` |
| `b-radius-md` | `border-radius: 0.375rem;` |
| `b-radius-lg` | `border-radius: 3rem;` |
| `b-radius-xl` | `border-radius: 100%;` |

## Border style

The border style class sets the `border-style` property. It controls the line style for all sides of an element’s border.

| CSS Shortcut class | Applied style |
|---|---|
| `b-solid` | `border-style: solid;` |
| `b-dashed` | `border-style: dashed;` |
| `b-dotted` | `border-style: dotted;` |
| `b-none` | `border-style: none;` |

## Border width

The border width class sets the `border-width` property. It controls the width of an element’s border.

Logical properties adapt to the writing mode and text direction. Instead of literal directions like "left", "right", "top", or "bottom", they use flow-relative terms.

This flexibility prepares layouts for internationalization without needing manual adjustments for different text directions.

The class `value` can be set to one of the following:

- `0`
- `sm` (0.0625rem)
- `md` (0.125rem)
- `lg` (0.25rem)
- `xl` (0.375rem)

| CSS Shortcut class | Applied style |
|---|---|
| `b-<value>` | `border: <value> solid currentColor;` |
| `bt-<value>` | `border-block-start: <value> solid currentColor;` |
| `bb-<value>` | `border-block-end: <value> solid currentColor;` |
| `be-<value>` | `border-inline-end: <value> solid currentColor;` |
| `bs-<value>` | `border-inline-start: <value> solid currentColor;` |
| `bx-<value>` | `border-inline: <value> solid currentColor;` |
| `by-<value>` | `border-block: <value> solid currentColor;` |

## Icon names

The icon class sets the `content` property and other icon base styles like the icon font family, style and weight. It controls what gcds-icon to display.

| CSS Shortcut class | Applied style |
|---|---|
| `gcds-icon-info-circle` | `content: "\e90a";` |
| `gcds-icon-warning-triangle` | `content: "\e90d";` |
| `gcds-icon-exclamation-circle` | `content: "\e909";` |
| `gcds-icon-checkmark-circle` | `content: "\e908";` |
| `gcds-icon-chevron-left` | `content: "\e901";` |
| `gcds-icon-chevron-right` | `content: "\e902";` |
| `gcds-icon-chevron-up` | `content: "\e903";` |
| `gcds-icon-chevron-down` | `content: "\e900";` |
| `gcds-icon-close` | `content: "\e90b";` |
| `gcds-icon-download` | `content: "\e906";` |
| `gcds-icon-email` | `content: "\e905";` |
| `gcds-icon-external` | `content: "\e904";` |
| `gcds-icon-phone` | `content: "\e90c";` |
| `gcds-icon-search` | `content: "\e907";` |

## Icon size

The icon size class sets the `font-size` and `line-height` properties of icons. It controls the size of gcds-icons.

| CSS Shortcut class | Applied style |
|---|---|
| `icon-h1` | `font-size: var(--gcds-font-sizes-h1); line-height: var(--gcds-line-heights-h1);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-h1-mobile);  line-height: var(--gcds-line-heights-h1-mobile); }` |
| `icon-h2` | `font-size: var(--gcds-font-sizes-h2); line-height: var(--gcds-line-heights-h2);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-h2-mobile);  line-height: var(--gcds-line-heights-h2-mobile); }` |
| `icon-h3` | `font-size: var(--gcds-font-sizes-h3); line-height: var(--gcds-line-heights-h3);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-h3-mobile);  line-height: var(--gcds-line-heights-h3-mobile); }` |
| `icon-h4` | `font-size: var(--gcds-font-sizes-h4); line-height: var(--gcds-line-heights-h4);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-h4-mobile);  line-height: var(--gcds-line-heights-h4-mobile); }` |
| `icon-h5` | `font-size: var(--gcds-font-sizes-h5); line-height: var(--gcds-line-heights-h5);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-h5-mobile);  line-height: var(--gcds-line-heights-h5-mobile); }` |
| `icon-h6` | `font-size: var(--gcds-font-sizes-h6); line-height: var(--gcds-line-heights-h6);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-h6-mobile);  line-height: var(--gcds-line-heights-h6-mobile); }` |
| `icon-text` | `font-size: var(--gcds-font-sizes-text); line-height: var(--gcds-line-heights-text);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-text-mobile);  line-height: var(--gcds-line-heights-text-mobile); }` |
| `icon-text-small` | `font-size: var(--gcds-font-sizes-text-small); line-height: var(--gcds-line-heights-text-small);  @media only screen and (width < 48em) {  font-size: var(--gcds font sizes-text-small-mobile);  line-height: var(--gcds-line-heights-text-small-mobile); }` |
| `icon-inherit` | `font-size: inherit; line-height: inherit;  @media only screen and (width < 48em) {  font-size: inherit;  line-height: inherit; }` |

## Image

The image class sets the `width` or `max-width` properties of an image. It controls how big the image is.

| CSS Shortcut class | Applied style |
|---|---|
| `img-full` | `width: 100%;` |
| `img-responsive` | `max-width: 100%;` |

## Cursor

The cursor class sets the `cursor` property. It controls what cursor style is displayed when hovering over an element.

| CSS Shortcut class | Applied style |
|---|---|
| `cursor-default` | `cursor: default;` |
| `cursor-copy` | `cursor: copy;` |
| `cursor-grab` | `cursor: grab;` |
| `cursor-help` | `cursor: help;` |
| `cursor-move` | `cursor: move;` |
| `cursor-not-allowed` | `cursor: not-allowed;` |
| `cursor-pointer` | `cursor: pointer;` |
| `cursor-text` | `cursor: text;` |
| `cursor-zoom-in` | `cursor: zoom-in;` |
| `cursor-zoom-out` | `cursor: zoom-out;` |

## Pointer events

The pointer events class sets the `pointer-events` property. It controls if an element responds to a mouse event, like clicking or hovering.

| CSS Shortcut class | Applied style |
|---|---|
| `pointer-events-auto` | `pointer-events: auto;` |
| `pointer-events-none` | `pointer-events: none;` |

## Transition

The transition class sets the `transition` property. It applies an animation that allows fading content in or out when hovering.

| CSS Shortcut class | Applied style |
|---|---|
| `transition-fade-in` | `opacity: 0; transition: opacity 0.15s ease-in-out;  &:hover {  opacity: 1; }` |
| `transition-fade-out` | `opacity: 1; transition: opacity 0.15s ease-in-out;  &:hover {  opacity: 0; }` |
