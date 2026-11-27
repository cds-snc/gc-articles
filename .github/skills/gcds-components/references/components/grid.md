# Grid

Tags: `<gcds-grid>`, `<gcds-grid-col>`  
Also called: layout, columns, columns layout, grid container  
Docs: https://design-system.canada.ca/en/components/grid/

A grid is a responsive, flexible column layout to position elements on a page.

## API (from custom-elements.json, v1.7.1)

### `<gcds-grid>`

A grid is a responsive, flexible column layout to position elements on a page.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `align-content` | `"center" \| "end" \| "space-around" \| "space-between" \| "space-evenly" \| "start" \| "stretch"` |  |  | If total grid size is less than the size of its grid container, this property aligns the grid along the block (column) axis |
| `align-items` | `"baseline" \| "center" \| "end" \| "start" \| "stretch"` |  |  | Aligns grid items along the block (column) axis |
| `alignment` | `"center" \| "end" \| "start"` |  |  | Defines the grid's alignment if the grid containers size is smaller than the parent's size. |
| `columns` | `string` |  |  | Defines the default number of grid columns for all viewports if columns-tablet and columns-desktop are not defined. Option to set different layouts for desktop with columns-desktop and for tablet with columns-tablet. |
| `columns-desktop` | `string` |  |  | Provides option to set a different number of grid columns for desktop screens. |
| `columns-tablet` | `string` |  |  | Provides option to set a different number of grid columns for tablet screens. If columns-desktop is not defined, columns-tablet will be used to define the number of columns for desktop as well. |
| `container` | `"full" \| "lg" \| "md" \| "sm" \| "xl" \| "xs"` |  |  | Defines grid container size |
| `display` | `"grid" \| "inline-grid"` | `'grid'` |  | Defines element as grid or inline-grid container |
| `equal-row-height` | `boolean` | `false` |  | Sets all grid items to have an equal height, based on the tallest item. |
| `gap` | `"150" \| "175" \| "200" \| "225" \| "250" \| "300" \| "350" \| "400" \| "450" \| "500" \| "550" \| "600" \| "650" \| "700" \| "750" \| "800"` | `'300'` |  | Defines the horizontal and vertical spacing between items in a grid container for all viewports if gap-tablet and gap-desktop are not defined. Option to set different spacing for desktop with gap-desktop and for tablet with gap-tablet. |
| `gap-desktop` | `"150" \| "175" \| "200" \| "225" \| "250" \| "300" \| "350" \| "400" \| "450" \| "500" \| "550" \| "600" \| "650" \| "700" \| "750" \| "800"` |  |  | Provides option to set horizontal and vertical spacing between items in a grid container for desktop screens. |
| `gap-tablet` | `"150" \| "175" \| "200" \| "225" \| "250" \| "300" \| "350" \| "400" \| "450" \| "500" \| "550" \| "600" \| "650" \| "700" \| "750" \| "800"` |  |  | Provides option to set horizontal and vertical spacing between items in a grid container for tablet screens. If gap-desktop is not defined, gap-tablet will be used to define the spacing for desktop screens as well. |
| `justify-content` | `"center" \| "end" \| "space-around" \| "space-between" \| "space-evenly" \| "start" \| "stretch"` |  |  | If total grid size is less than the size of its grid container, this property aligns the grid along the inline (row) axis |
| `justify-items` | `"center" \| "end" \| "start" \| "stretch"` |  |  | Aligns grid items along the inline (row) axis |
| `place-content` | `"center" \| "end" \| "space-around" \| "space-between" \| "space-evenly" \| "start" \| "stretch"` |  |  | Sets both the align-content + justify-content properties |
| `place-items` | `"center" \| "end" \| "start" \| "stretch"` |  |  | Sets both the align-items + justify-items properties |
| `tag` | `"article" \| "aside" \| "div" \| "dl" \| "main" \| "nav" \| "ol" \| "section" \| "ul"` | `'div'` |  | Set tag for grid container |

**Slots:**

- `default` - Slot for the main content of the grid.

### `<gcds-grid-col>`

A grid column is a single column in a grid layout, allowing for flexible content arrangement.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `desktop` | `1 \| 10 \| 11 \| 12 \| 2 \| 3 \| 4 \| 5 \| 6 \| 7 \| 8 \| 9` |  |  | Optimize grid column size for desktop (1024px and above). Desktop grid column sizes are based on a 12 column grid. |
| `tablet` | `1 \| 2 \| 3 \| 4 \| 5 \| 6` | `6` |  | Optimize grid column size for tablet (768px - 1023px). Tablet grid column sizes are based on a 6 column grid. The tablet size will also be used for desktop, if desktop is undefined. |
| `tag` | `string` | `'div'` |  | Set tag for grid column |

**Slots:**

- `default` - Slot for the main content of the grid coloumn.

## Use case

Check out the problems grids solve to see if they fit the problem you’re solving for.

### Problems grids solve

Grids create column layouts that keep content elements grouped across different devices and screens.

Use grids to:

- Apply a flexible column layout that scales to the screen size (viewport) of the user’s device.

- Guide focus by defining the width of a column and the spacing between columns.

- Create complex layouts with control over the sizing of each column.

- Accommodate different screen sizes by setting a custom, optimized layout for different viewports, like mobile, tablet, and desktop.

- Limit the width of content displayed to ensure lines wrap and are more readable.

### Related components

Container for applying a basic, single column layout across all viewports.

Card for structuring actionable content in a group on a single topic.

## Design guidance

### Grid anatomy

- The **container** holds all elements within the grid and spans their combined width. The container is responsive and can be centred.
- The **column**, also called grid item, sets a boundary around its contents to contain it within the width set for that column.
- The **gap**, sometimes called the gutter, defines the width of the space between the columns.

### Design and accessibility for grids

#### Adjust layouts to optimize the experience on different screen sizes

- Optimize each grid component for the different screen sizes a person will use — or switch between — when they visit your site or use your product.

- Adapt for mobile, tablet, and large screens by defining each viewport layout for that grid component.

- Smaller screens, like mobile devices, typically display a single column layout.
- Medium-sized screens, like tablets, a single or 2-column layout.
- Larger screens, like desktop, a 3- or 4- column layout.

Tip: Keep grid layouts simple by designing for mobile, tablet, and desktop experiences that work with any screen size.

#### Limit width of content

- Keep text line length below 75 characters for a comfortable, accessible reading length.

- Limit column width to stop large screens from displaying lines of text that are overly long and difficult to read.

- Avoid exceeding the maximum width of 71.25rem (1140px) wide.

#### Communicate meaning by adjusting spacing

Choose spacing to support ease of reading and help users better understand the messages you’re communicating.

- Use smaller spacing tokens to group related concepts together.

- Use larger spacing tokens to separate elements into logical groups.

## Code guidance and examples

### Coding and accessibility for grids

#### Adjust layouts to optimize the experience on different screen sizes

- Optimize each grid component for the different screen sizes a person will use — or switch between — when they visit your site or use your product.

- Adapt for mobile, tablet, and large screens by defining each viewport layout for that grid component.

- Smaller screens, like mobile devices, typically display a single column layout.
- Medium-sized screens, like tablets, a single or 2-column layout.
- Larger screens, like desktop, a 3- or 4- column layout.

- Use the `columns` property to add your layout for smaller screens or if you are using the same layout for all screens.

- Use the `columns-tablet` property to set your tablet layout **(48em and 768px and above)** or if you are using the same layout for medium and large screens.

- Use the `columns-desktop` property to define your layout for larger screens **(64em and 1024px and above)**.

Tip: Keep layouts simple. Consider optimizing each layout for mobile, tablet, and desktop to provide a better user experience for all viewports.

#### Maintain standard tag usage to be accessible

By default, the `tag` property is set to use a `div` tag.

- Opt to change the tag to a context-appropriate value using standard tags. For example, when using an unordered list set the tag property to `ul`.

- Choose a tag from the list of available options for grid in the code builder. Using established standards for HTML tags that are semantic increase the accessibility of the content and improves the experience for everyone.

#### Add spacing between columns

- Use the `gap` property to add spacing between your `columns` in the grid.

- Use GC Design System design tokens as a reference for the size of the `gap` in the grid. The tokens measurements match up with the spacing values for the `gap` attribute (150-800).

#### Choose an option for equal width columns

Opt out of setting the minimum and maximum width when you want to design equal-width columns without width restrictions. This will allow the grid and its columns to scale to the size of the viewport.

**Example displaying 3 columns for desktop, 2 columns for tablet, and 1 column for mobile:**

Desktop

This is some example content to display the grid component.

This is some example content to display the grid component.

This is some example content to display the grid component.

Tablet

This is some example content to display the grid component.

This is some example content to display the grid component.

This is some example content to display the grid component.

Mobile

This is some example content to display the grid component.

This is some example content to display the grid component.

This is some example content to display the grid component.

```html
<gcds-grid tag="article" columns-desktop="1fr 1fr 1fr" columns-tablet="1fr 1fr" columns="1fr">
  <p>This is some example content to display the grid component.</p>
  <p>This is some example content to display the grid component.</p>
  <p>This is some example content to display the grid component.</p>
</gcds-grid>
```

Set the minimum and maximum width to design equal-width columns with restrictions to limit how wide they will span on any screen size.

- Set a minimum width when you want to prevent a column from being too narrow in smaller viewports.

- Set a maximum width when you want to prevent a column from being too wide in larger viewports.

**Example uses equal-width columns with a minimum width of 6.25rem (100px) and a maximum width of 18.75rem (300px) for all screen sizes:**

Desktop

This is some example content to display the grid component.

This is some example content to display the grid component.

This is some example content to display the grid component.

Tablet

This is some example content to display the grid component.

This is some example content to display the grid component.

This is some example content to display the grid component.

Mobile

This is some example content to display the grid component.

This is some example content to display the grid component.

This is some example content to display the grid component.

```html
<gcds-grid tag="article" columns="repeat(auto-fit, minmax(100px, 300px))">
  <p>This is some example content to display the grid component.</p>
  <p>This is some example content to display the grid component.</p>
  <p>This is some example content to display the grid component.</p>
</gcds-grid>
```

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `align-content`

The `align-content` attribute sets the alignment of the grid along the block (column) axis when the total grid size is less than the size of its grid container. The available options are:

- `center`

- `end`

- `space-around`

- `space-between`

- `space-evenly`

- `start`

- `stretch`

1

2

3

4

5

6 
```html
<gcds-grid align-content="space-between" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
  <p>4</p>
  <p>5</p>
  <p>6</p>
</gcds-grid>
```

##### `align-items`

The `align-items` attribute sets the alignment of grid items along the block (column) axis. The available options are:

- `baseline`

- `center`

- `end`

- `start`

- `stretch`

1

2

3 
```html
<gcds-grid align-items="baseline" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p class="pb-800">2</p>
  <p class="pt-900">3</p>
</gcds-grid>
```

##### `alignment`

The `alignment` attribute sets the grid’s alignment if the grid container’s size is smaller than the parent’s size. The available options are:

- `center`

- `end`

- `start`

1

2

3 
```html
<gcds-grid alignment="end" container="sm" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `columns`

The `columns` attribute defines the default number of grid columns for all viewports if `columns-tablet` and `columns-desktop` are not defined. Option to set different layouts for desktop with `columns-desktop` and for tablet with `columns-tablet`.

1

2

3 
```html
<gcds-grid columns="1fr 1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `columns-desktop`

The `columns-desktop` attribute provides the option to set a different number of grid columns for desktop screens.

1

2

3 
```html
<gcds-grid columns-desktop="1fr 1fr 1fr" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `columns-tablet`

The `columns-tablet` attribute provides the option to set a different number of grid columns for tablet screens. If `columns-desktop` is not defined, `columns-tablet` will be used to define the number of columns for desktop as well.

1

2

3 
```html
<gcds-grid columns-tablet="1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `container`

The `container` attribute defines the grid container size. The available options are:

- `full`

- `xl`

- `lg`

- `md`

- `sm`

- `xs`

1

2

3 
```html
<gcds-grid container="sm" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `display`

The `display` attribute defines the element as a `grid` or `inline-grid` container. The available options are:

- `grid` (default)

- `inline-grid`

1

2

3 
```html
<gcds-grid display="inline-grid" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `gap`

The `gap` attribute defines the horizontal and vertical spacing between items in a grid container for all viewports if `gap-tablet` and `gap-desktop` are not defined. Option to set different spacing for desktop with `gap-desktop` and for tablet with `gap-tablet`.

1

2

3

4

5

6 
```html
<gcds-grid gap="450" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
  <p>4</p>
  <p>5</p>
  <p>6</p>
</gcds-grid>
```

##### `gap-desktop`

The `gap-desktop` attribute provides the option to set horizontal and vertical spacing between items in a grid container for desktop screens.

1

2

3

4

5

6 
```html
<gcds-grid gap-desktop="800" gap="400" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
  <p>4</p>
  <p>5</p>
  <p>6</p>
</gcds-grid>
```

##### `gap-tablet`

The `gap-tablet` attribute provides the option to set horizontal and vertical spacing between items in a grid container for tablet screens. If `gap-desktop` is not defined, `gap-tablet` will be used to define the spacing for desktop screens as well.

1

2

3

4

5

6 
```html
<gcds-grid gap-tablet="500" gap="400" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
  <p>4</p>
  <p>5</p>
  <p>6</p>
</gcds-grid>
```

##### `justify-content`

The `justify-content` attribute sets the alignment of the grid along the inline (row) axis if the total grid size is less than the size of its grid container. The available options are:

- `center`

- `end`

- `space-around`

- `space-between`

- `space-evenly`

- `start`

- `stretch`

1

2

3 
```html
<gcds-grid justify-content="center" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `justify-items`

The `justify-items` attribute sets the alignment of the grid items along the inline (row) axis. The available options are:

- `center`

- `end`

- `start`

- `stretch`

1

2

3 
```html
<gcds-grid justify-items="center" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

##### `place-content`

The `place-content` attribute sets both the `align-content` and `justify-content` attributes. The available options are:

- `center`

- `end`

- `space-around`

- `space-between`

- `space-evenly`

- `start`

- `stretch`

1

2

3

4

5

6 
```html
<gcds-grid place-content="space-around" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
  <p>4</p>
  <p>5</p>
  <p>6</p>
</gcds-grid>
```

##### `place-items`

The `place-items` attribute sets both the `align-items` and `justify-items` attributes. The available options are:

- `center`

- `end`

- `start`

- `stretch`

1

2

3

4

5

6 
```html
<gcds-grid place-items="end" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
  <p>4</p>
  <p>5</p>
  <p>6</p>
</gcds-grid>
```

##### `tag`

The `tag` attribute sets the HTML element used to render the grid container. By default, it is set to `div`. The available options are:

- `div` (default)

- `article`

- `aside`

- `dl`

- `main`

- `nav`

- `ol`

- `section`

- `ul`

1

2

3 
```html
<gcds-grid tag="article" columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing the grid items.

1

2

3 
```html
<gcds-grid columns-tablet="1fr 1fr 1fr" columns="1fr">
  <p>1</p>
  <p>2</p>
  <p>3</p>
</gcds-grid>
```
