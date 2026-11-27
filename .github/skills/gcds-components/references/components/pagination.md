# Pagination

Tags: `<gcds-pagination>`  
Also called: page index, page navigation  
Docs: https://design-system.canada.ca/en/components/pagination/

Pagination is a division of content into multiple linked pages.

## API (from custom-elements.json, v1.7.1)

### `<gcds-pagination>`

Pagination is a division of content into multiple linked pages.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `label` | `string` |  | **yes** | Navigation element label |
| `current-page` | `number` |  |  | List display - Current page number |
| `display` | `"list" \| "simple"` | `'list'` |  | Determines the pagination display style. |
| `next-href` | `string` |  |  | Simple display - href for next link |
| `next-label` | `string` |  |  | Simple display - lable for next link |
| `previous-href` | `string` |  |  | Simple display - href for previous link |
| `previous-label` | `string` |  |  | Simple display - label for previous link |
| `total-pages` | `number` |  |  | List display - Total number of pages |
| `url` | `object \| string` |  |  | List display - URL object to create query strings and fragment on links |

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<object \| string>` Emitted when the link has been clicked. Contains the href in event detail when using simple display, or an object with page and href when using list display.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

Review common uses for pagination to see if they match the problem you’re solving for.

### Pagination use cases

Use pagination for very lengthy content on a single page that might be difficult to navigate, to reduce cognitive load, and to:

- Display search results when there’s a long list of returns, reducing the time it takes to load and amount of scrolling.

- Allow users to move through indexed content using next, previous, and page links.

- Split into sections any long documents, like reports, that have chapters or other natural divisions.

- Highlight the reader’s position in a sequence of content.

### Related components

Stepper to track progress in a multi-step process.

Links in the button component to navigate between non-sequential pages or to external sites.

### Choose a pagination display

#### Simple

Use simple pagination when the number of pages is small (2 to 5 pages).

#### List

Use list-styled pagination when:

- The number of pages is large.

- The “Previous” and “Next” links would make navigation cumbersome.

## Design guidance

### Pagination anatomy

#### Simple pagination anatomy

- The **previous link** navigates to the previous page in the flow when selected.
- The **previous chevron** is a visual aid to indicate navigating backward, to previous pages in the page flow.
- The **previous text** is the text that accompanies the previous chevron and indicates that the user would be navigating to the previous page in the flow.
- The **previous label** provides context about the page the user will navigate to next.
- The **next link** navigates to the next page in the flow when selected.
- The **next chevron** is a visual aid to signal forward navigation in the flow.
- The **next text** is the text that accompanies the next chevron and indicates that the user would be navigating to the next page in the flow.
- The **next label** provides context about the page the user will navigate back to.

#### List-styled pagination anatomy

##### Large screens

- The **previous link** navigates to the previous page in the flow when selected.
- The **previous chevron** is a visual aid to indicate navigating backward, to previous pages in the page flow.
- The **previous text** is the text that accompanies the previous chevron and indicates that the user would be navigating to the previous page in the flow.
- A **page link** navigates to it’s corresponding page in the flow.
- The **current page** indicates the page the user is currently on.
- An **ellipsis** replaces any pages that are being skipped.
- The **next text** is the text that accompanies the next chevron and indicates that the user would be navigating to the next page in the flow.
- The **next chevron** is a visual aid indicating that you would be navigating forwards in the page flow.
- The **next link** navigates to the next page in the flow when selected.

##### Small screens

- A **page link** navigates to it’s corresponding page in the flow.
- The **current page** indicates the page the user is currently on.
- An **ellipsis** replaces any pages that are being skipped.
- The **previous link** navigates to the previous page in the flow when selected.
- The **previous chevron** is a visual aid to indicate navigating backward, to previous pages in the page flow.
- The **previous text** is the text that accompanies the previous chevron and indicates that the user would be navigating to the previous page in the flow.
- The **next text** is the text that accompanies the next chevron and indicates that theuser would be navigating to the next page in the flow.
- The **next chevron** is a visual aid indicating that you would be navigating forwards in the page flow.
- The **next link** navigates to the next page in the flow when selected.

### Design and accessibility for pagination

#### Make pagination content findable

- Put the most important information on the first few pages so there is a lower chance of it being missed.

- Place the pagination component immediately under the page content, aligned to the left.

- Display navigation links in a way that supports visual scanning:

- In a single row without wrapping in list-styled pagination.

- Vertically stacked to support users of screen magnification in simple pagination.

#### Help people understand where they are

- In simple pagination, use a label under the “Previous” and “Next” text to let the user know where they are in the flow. Include the page title or the page number and total number of pages to provide context on the adjacent pages.

- Avoid showing the “Previous” link on the first page or the “Next” link on the last page.

#### Show the relevant number of pages

In list-styled pagination, show enough page links to fill the horizontal space.

Always show:

- The current page

- The first and last pages

- The pages immediately before and after the current page

On larger screens, show additional pages before and after the current page when space allows.

## Code guidance and examples

### Coding and accessibility for pagination

#### Set up simple pagination

Use simple pagination for a smaller number of pages.

- Set the `display` attribute to `simple`.

- Set the previous page’s URL with the `previous-href` attribute.

- Set the previous page’s label with the `previous-label` attribute.

- Set the next page’s URL with the `next-href` attribute.

- Set the next page’s label with the `next-label` attribute.

#### Set up list styled pagination

Use list styled pagination when you are dealing with a larger number of pages.

- Set the `display` attribute to `list`.

- Set the total number of pages in your sequence with the `total-pages` attribute using a numeric value.

- Set the current or active page with the `current-page` attribute using a numeric value.

- Opt to use the `url` attribute to provide the component with a group of page links. This can be an object if you are working in a JS environment, or a string if you are using HTML.

##### Apply the URL object for list-style pagination

Use the `url` object to generate the query strings and fragments for the list pagination’s links.

```
url = {
  queryStrings: {
    // Key value pairs
    key: value,
  },
  fragment: string, // Target id
};
```

To modify the value based on the page link being rendered, add the object modifiers `::offset` or `::match` to a key in the queryStrings object.

`::offset` counts up with the given value starting from the second page link.

```
url = {
  queryStrings: {
    'index::offset': 100,
  },
};
```

Using `::match` on the current page link being rendered, multiplies it by the value provided.

```
url = {
  queryStrings: {
    'page::match': 1,
  },
};
```

Using either `::offset` or `::match`, allows a number to be injected into a string. Using `{{#}}`, the modifier will inject the modified number based on the counting method into the string when rendering.

```
url = {
  queryStrings: {
    'index::offset': 'index_{{100}}',
    'page::match': 'page_{{1}}',
  },
};
```

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `label`

The `label` attribute defines the text that labels the pagination navigation landmark, giving it a clear and accessible name.

```html
<gcds-pagination
  label="List pagination example"
  total-pages="15"
  current-page="9"
></gcds-pagination>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `current-page`

The `current-page` attribute sets the current page for a pagination with list display.

```html
<gcds-pagination
  label="List pagination example"
  total-pages="15"
  current-page="9"
></gcds-pagination>
```

##### `display`

The `display` attribute sets the display style of the pagination. The available options are:

- `list` (default)

- `simple`

```html
<gcds-pagination
  label="List pagination example"
  display="list"
  total-pages="15"
  current-page="9"
></gcds-pagination>
```

##### `next-href`

The `next-href` attribute specifies the next page’s URL for a pagination with simple display.

```html
<gcds-pagination
  label="Simple pagination example"
  display="simple"
  previous-href="#"
  next-href="#"
  previous-label="Title of page"
  next-label="3 of 3"
></gcds-pagination>
```

##### `next-label`

The `next-label` attribute defines the next page’s label for a pagination with simple display.

```html
<gcds-pagination
  label="Simple pagination example"
  display="simple"
  previous-href="#"
  next-href="#"
  previous-label="Title of page"
  next-label="3 of 3"
></gcds-pagination>
```

##### `previous-href`

The `previous-href` attribute specifies the previous page’s URL for a pagination with simple display.

```html
<gcds-pagination
  label="Simple pagination example"
  display="simple"
  previous-href="#"
  next-href="#"
  previous-label="Title of page"
  next-label="3 of 3"
></gcds-pagination>
```

##### `previous-label`

The `previous-label` attribute defines the previous page’s label for a pagination with simple display.

```html
<gcds-pagination
  label="Simple pagination example"
  display="simple"
  previous-href="#"
  next-href="#"
  previous-label="Title of page"
  next-label="3 of 3"
></gcds-pagination>
```

##### `total-pages`

The `total-pages` attribute sets the total number of pages for a pagination with list display.

```html
<gcds-pagination
  label="List pagination example"
  total-pages="15"
  current-page="9"
></gcds-pagination>
```

##### `url`

The `url` attribute defines the object (in a JavaScript environment) or string (in HTML) that generates the query strings and fragment for the list-styled pagination links.

```html
<gcds-pagination label="List pagination example" total-pages="15" current-page="9" url='{"queryStrings": { "querystring::offset": 10 }, "fragment": "main" }'>
</gcds-pagination>
```
