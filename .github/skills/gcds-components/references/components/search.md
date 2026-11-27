# Search

Tags: `<gcds-search>`  
Also called: search bar, site search box, search field  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/search/

Search is a space for entering keywords to find relevant information.

> **Upstream example issues (found by `gcds_lint.py`; the API table is correct):**
> - <gcds-search suggested> is not valid JSON: Expecting value

## API (from custom-elements.json, v1.7.1)

### `<gcds-search>`

Search is a space for entering keywords to find relevant information.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `action` | `string` | `'/sr/srb.html'` |  | Sets the action for the search form. Default will be canada.ca global search |
| `method` | `"get" \| "post"` | `'get'` |  | Set the form method of the search form |
| `name` | `string` | `'q'` |  | Set the name of the search input |
| `placeholder` | `string` | `'Canada.ca'` |  | Set the placeholder and label for the search input. Becomes "Search [placeholder]" |
| `search-id` | `string` | `'search'` |  | Set the id of the search input |
| `suggested` | `string \| string[]` |  |  | Set a list of predefined search terms |
| `value` | `string` |  |  | Set the value of the search input |

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the search input has lost focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string>` Emitted when the search input value has changed.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the search input has gained focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string>` Emitted when the search element has received input.
- `gcdsSubmit` / `onGcdsSubmit` / `@gcdsSubmit` / `(gcdsSubmit)` - `CustomEvent<string>` Emitted when the search form has submitted.

## Use case

Search is a required component for standard and campaign pages on Canada.ca.

Take a look at what problems search solves to see if they fit the problem you’re solving for.

### Problems search solves

Use search to help a person on your website or page find information:

- Indexed on Canada.ca, across all Government of Canada content or at an institution or program level.

- From a subset of content you have locally indexed, using your own data set.

### Related components

Header for placing the Government of Canada branded header landmark.

Input for requesting a short written response from a person.

## Design guidance

### Search anatomy

- The **input field** is a space to enter search keywords.
- The **placeholder text** is a prompt visible before a person types. It identifies the search area.
- The **button**, labelled with a magnifying glass, initiates a search.

### Design and accessibility for search

#### Check search requirements

Here’s what’s required for search on GC sites.

- Search is required in the header on Canada.ca standard and campaign pages.

- It’s optional for other Canada.ca pages and GC sites.

**What’s required on a Canada.ca standard or campaign page**

Always include the search in the header of a standard or campaign page and maintain the default settings.

- Use default GC Search indexing.
- Set indexing across Canada.ca content or at an institution or program level.

Canada.ca site-wide search

- Use “Search Canada.ca” as the search prompt text in English.
- Use “Rechercher dans Canada.ca” as the search prompt text in French.

Institution, program, or product specific search

- Use “Search [institution/program/product]” as the search prompt text in English.
- Use “Rechercher dans [institution/programme/produit]” as the search prompt text in French.
- Avoid acronyms unless necessary to shorten text for visual accessibility.

**Note:** As an exception, pages solely intended for a public service audience may apply a customized search indexation in particular contexts.

**What’s optional to include on a Canada.ca standard or campaign page**

Opt to add a search for other datasets within the content area of the page.

#### Opt to set up an additional search

- If a secondary search is needed with other datasets, place that search within the content area of the page.

- Clearly identify the search area, so a person searching understands the constraints ahead of time.

## Code guidance and examples

### Coding and accessibility for search

#### Place the search in the header

- Place the search component in the header below the language toggle and in line with the Government of Canada signature.

- Ensure the header is responsive so that the search appears below both the signature and the language toggle on mobile devices.

#### Set up search for content on Canada.ca

- Use the search component to perform a global search on Canada.ca. By default, the component is set to search Canada.ca.

- Opt to set the search to an institution or program specific area.

#### Opt to replace the search endpoint for applications or transactional sites

- Opt to replace the search endpoint for an application or a transactional site when leaving the site would interrupt a person’s task or flow.

- Use the component’s default HTTP request method, `GET`, or opt to use `POST` by setting the `method` attribute.

- Set the `action` attribute to an endpoint of your choice and set the placeholder attribute, so the text of the search prompt reflects the scope of search. The set placeholder text will also populate the label text.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `action`

The `action` attribute controls where the form will send the form data.

```html
<gcds-search action="search.html"></gcds-search>
```

##### `method`

The `method` attribute controls how the form will send the form data. The available options are:

- `get`

- `post`

```html
<gcds-search method="post"></gcds-search>
```

##### `name`

The `name` attribute specifies the name of the search input when submitting the form data.

```html
<gcds-search name="search"></gcds-search>
```

##### `placeholder`

The `placeholder` attribute specifies the placeholder and label for the input. The input will format the label and placeholder like “Search [`placeholder`]”.

```html
<gcds-search placeholder="GC Design System"></gcds-search>
```

##### `search-id`

The `search-id` attribute specifies the id for the input.

```html
<gcds-search search-id="searchform"></gcds-search>
```

##### `suggested`

The `suggested` attribute specifies a list of predefined search times. The list will appear as a dropdown list on the input when it is focused.

```html
<gcds-search
  suggested="['red', 'green', 'blue']"
></gcds-search>
```

##### `value`

The `value` attribute specifies the `value` of the input. Setting the value will prepopulate the search input.

```html
<gcds-search value="Design system"></gcds-search>
```
