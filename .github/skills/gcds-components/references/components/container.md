# Container

Tags: `<gcds-container>`  
Also called: wrapper, box  
Docs: https://design-system.canada.ca/en/components/container/

A container is a basic box layout with a set width for its contents.

## API (from custom-elements.json, v1.7.1)

### `<gcds-container>`

A container is a basic box layout with a set width for its contents.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `alignment` | `"center" \| "end" \| "start"` |  |  | Defines the container's alignment. This property is ignored when `layout` is set to `page`, as the page layout has higher priority. |
| `border` | `boolean` | `false` |  | Defines if the container has a border. |
| `layout` | `"full" \| "page"` |  |  | Controls how the container aligns with the page layout. When set to `page`, the container uses a max width of 1140px and switches to 90% width on smaller screens to scale consistently with core page layout components such as the header and footer. When set to `full`, the container spans the full width (100%) of its parent. |
| `margin` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` |  |  | Container margin. Horizontal margins (left and right) are not applied if the container’s alignment property is defined, since alignment has higher priority. |
| `padding` | `"0" \| "100" \| "1000" \| "1050" \| "1100" \| "1150" \| "1200" \| "125" \| "1250" \| "150" \| "175" \| "200" \| "225" \| "25" \| "250" \| "300" \| "350" \| "400" \| "450" \| "50" \| "500" \| "550" \| "600" \| "650" \| "700" \| "75" \| "750" \| "800" \| "850" \| "900" \| "950"` |  |  | Defines the container's padding. |
| `size` | `"full" \| "lg" \| "md" \| "sm" \| "xl" \| "xs"` | `'full'` |  | Defines container size. |
| `tag` | `string` | `'div'` |  | Set tag for container. |

**Slots:**

- `default` - Slot for the main content of the container.

## Use case

Check out container use cases to see if they fit the problem you’re solving for.

### Problems containers solves

Containers group related content in a space.

Use containers to:

- Arrange content and components in a basic vertical layout using a wrapper.

- Limit the width of content in a given space within a site or product.

- Support readability and scannability by reducing the overall length of a line of text and presenting smaller chunks of content at a time.

- Centre content on a screen or within a viewport.

### Related components

Grid for a responsive, flexible column layout to position elements on a page.

Card for grouping small pieces of related information as a single unit.

## Design guidance

### Container anatomy

- The **container** groups content together in a limited space that can hold text, images or other components. The width can be adjusted, for example, to limit the length of a line of text.
- The **optional border** surrounds the container showing the width.

### Design and accessibility for containers

#### Limit width of content

- Keep text line length below 65 characters for a comfortable, accessible reading length.

- Limit container width to stop large screens from displaying lines of text that are overly long and difficult to read.

- Avoid exceeding the maximum width of 71.25rem (1140px) wide.

#### Add a container size

The `size` property sets the size of the container. It comes in 6 sizes:

SizeREMPixels (Px)

`full`100%100%

`xl`71.25rem1140px

`lg`62rem992px

`md`48rem768px

`sm`30rem480px

`xs`20rem320px

By default, the container size will be set to `full` which means it will take up the entire width of the parent container.

## Code guidance and examples

### Coding and accessibility for containers

#### Limit width of content

- Keep text line length below 65 characters for a comfortable, accessible reading length.

- Limit column width to stop large screens from displaying lines of text that are overly long and difficult to read.

- Avoid exceeding the maximum width of 71.25rem (1140px) wide.

#### Add a container size

The `size` property sets the size of the container. It comes in 6 sizes:

SizeREMPixels (Px)

`full`100%100%

`xl`71.25rem1140px

`lg`62rem992px

`md`48rem768px

`sm`30rem480px

`xs`20rem320px

By default, the container size will be set to `full` which means it will take up the entire width of the parent container.

#### Align content with the page layout

Use `layout="page"` to visually align page content with the header and footer.

When `layout` is set to `page`, the container:

- Uses a maximum width of 71.25rem (1140px).

- Is centred automatically within the viewport.

- Switches to 90% width on smaller screens.

- Matches the layout behaviour of the header and footer components.

Using `layout="page"` ensures content maintains a consistent width, alignment, and reading experience across breakpoints. When `layout` is set to `page`, there is no need to define a container `size` or set `alignment`, as the page layout automatically controls width and alignment.

#### Position the container

Use the `alignment` attribute to control how a container is positioned within its parent. It supports three options:

- `start` - position the container horizontally to the start of the available space.

- `center` - centres the container horizontally.

- `end` - position the container horizontally to the end of the available space.

**Note:** When the `layout` attribute is set to `page`, the `alignment` attribute is ignored, the page layout automatically manages width and centring.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `alignment`

The `alignment` attribute sets how the container is positioned within its parent. This property is ignored when `layout` is set to `page`, as the `page` `layout` has higher priority. The available options are:

- `center`

- `end`

- `start`

```html
<gcds-container alignment="center" size="sm" border padding="300">
<p>Container</p>
</gcds-container>
```

##### `border`

The `border` attribute controls whether the container has a border or not.

```html
<gcds-container border padding="300">
<p>Container</p>
</gcds-container>
```

##### `layout`

The `layout` attribute sets how the container aligns with the page layout. The available options are:

- `full`

- `page`

```html
<gcds-container layout="full" border padding="300">
<p>Container</p>
</gcds-container>
```

##### `margin`

The `margin` attribute sets the container margin. Left and right margins are not applied if the container’s alignment attribute is defined, since alignment has higher priority.

```html
<gcds-container margin="800" size="xs" border padding="300">
<p>Container</p>
</gcds-container>
```

##### `padding`

The `padding` attribute sets the container’s padding, which is the inner spacing between the container’s border and its content.

```html
<gcds-container padding="600" border>
<p>Container</p>
</gcds-container>
```

##### `size`

The `size` attribute sets the width of the container. The available options are:

- `full` (default)

- `xl`

- `lg`

- `md`

- `sm`

- `xs`

```html
<gcds-container size="sm" border padding="300">
<p>Container</p>
</gcds-container>
```

##### `tag`

The `tag` attribute sets the HTML element used to render the container. By default, it is set to `div`.

```html
<gcds-container tag="section" border padding="300">
<p>Container</p>
</gcds-container>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding content inside the container.

```html
<gcds-container border padding="300">
<p>Container</p>
</gcds-container>
```
