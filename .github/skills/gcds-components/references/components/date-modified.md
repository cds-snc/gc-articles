# Date modified

Tags: `<gcds-date-modified>`  
Also called: Last updated, version date  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/date-modified/

Date modified is an indicator of the last update to a webpage or application.

## API (from custom-elements.json, v1.7.1)

### `<gcds-date-modified>`

Date modified is an indicator of the last update to a webpage or application.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `type` | `"date" \| "version"` | `'date'` |  | Set date modified type. Default is date. |

**Slots:**

- `default` - Slot for the date/version number.

## Use case

Date modified is a required component for Government of Canada websites and has specific requirements for Canada.ca pages.

### Problems date modified solves

Use date modified to:

- Log the date that a web page was last changed.

- Identify an application’s current version.

### Related components

Footer for placing the Government of Canada branded footer landmark.

### Component types

Date type preview

2023-08-22

#### Date type

Use the date type to:

- Identify the date a web page or website was last changed.

Version type preview

1.0.0

#### Version type

Use the Version type to:

- Identify the current version of an application.

## Design guidance

### Date modified anatomy

- The **label** describes the type specified in the value. The label reads either: “Date modified” or “Version” depending on the component type.
- The **value** is either a numerical date formatted as YYYY-MM-DD or a version number.

### Design and accessibility for date modified

#### Check date modified requirements

The date modified is required on Canada.ca pages and GC sites.

**What's required on Canada.ca and GC sites**

Always include the date modified and maintain default settings.

- Place the date modified component on the left, between the main content of the page and the footer.
- Use the same placement of the date modified component across all web pages to make it findable.
- If your page includes the page feedback tool, place the date modified component below it.

**What's optional to include on Canada.ca pages**

For applications, use a version number in place of a date.

#### Keep the format consistent

- Use the YYYY-MM-DD date format across all web pages.

- If using the version type, apply a standard version number format across all products.

#### When to update the date modified

- Only update the date modified when you make a significant content change.

- Avoid updating for minor corrections, like typos, or small changes to the code.

## Code guidance and examples

### Coding and accessibility for date modified

#### Keep the format consistent

- Use the YYYY-MM-DD date format across all web pages.

- If using the `type: version`, consider using a standard version number format across all products.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `type`

The `type` attribute controls how the date-modified information is presented. By default, it is set to `date`, which displays the last modified date of the content.

```html
<gcds-date-modified>2023-01-26</gcds-date-modified>
```

When set to `version`, the component displays a version number instead.

```html
<gcds-date-modified type="version">
  v1.0.0
</gcds-date-modified>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding the date modified or version text.

```html
<gcds-date-modified>2023-01-26</gcds-date-modified>
```
