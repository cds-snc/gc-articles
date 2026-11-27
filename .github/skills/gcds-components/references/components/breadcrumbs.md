# Breadcrumbs

Tags: `<gcds-breadcrumbs>`, `<gcds-breadcrumbs-item>`  
Also called: breadcrumb trail  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/breadcrumbs/

Breadcrumbs is a path to the current page from each preceding level of the site's hierarchy.

## API (from custom-elements.json, v1.7.1)

### `<gcds-breadcrumbs>`

Breadcrumbs is a path to the current page from each preceding level of the site's hierarchy.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `hide-canada-link` | `boolean` | `false` |  | Defines if the default canada.ca link is displayed or omitted. |

**Slots:**

- `default` - Slot for the breadcrumb items.

### `<gcds-breadcrumbs-item>`

Breadcrumbs item represents a single link in the breadcrumbs navigation.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `href` | `string` |  | **yes** | Specifies the href of the breadcrumb item. |

**Slots:**

- `default` - Slot for the breadcrumb item link label.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked. Contains the href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

Breadcrumbs is a required component for standard and campaign pages on Canada.ca.

Check out use cases for breadcrumbs to see if they fit the problem you’re solving for.

### Problems breadcrumbs solve

Use breadcrumbs to signal a visitor’s current location and give them a sense of place in the navigation model, and to:

- Provide a linear way to move between multiple levels of a website.

- Surface part of the site’s structure to help people understand where to find what they need.

- Situate the current page in relation to its parent in the site structure.

- Organize websites in a hierarchy of more than two levels.

### Related components

Footer for placing the Government of Canada branded footer landmark.

Header for placing the Government of Canada branded header landmark.

Top navigation for guiding navigation through a website using a landmark.

## Design guidance

### Breadcrumbs anatomy

- The **first link** in the breadcrumbs defaults to the Canada.ca home page. For other sites, the link can be changed.
- The **chevron** separates each link and points to a child link.
- The **link** navigates to a parent-level page.

### Design and accessibility for breadcrumbs

#### Check breadcrumbs requirements

Here’s what’s required for breadcrumbs on GC sites.

- Breadcrumbs are required in the header on Canada.ca standard and campaign pages.

- Breadcrumbs are optional for other Canada.ca pages and GC sites.

**What's required on a Canada.ca standard or campaign page**

Always include the breadcrumbs in the header of standard and campaign pages on Canada.ca and maintain default settings.

- Keep the placement aligned to the left, directly below the divider line.
- Keep the Canada.ca homepage as the first link in the breadcrumbs.
- Leave out the current page at the end of the breadcrumb trail.

**What's optional to include on a Canada.ca standard or campaign page**

- Shorten the link text to improve readability and reduce space.
- On a campaign page you can link to a specific landing page as the first link. This could be the Canada.ca home page, the topic tree (Canada.ca topic categories), the institutional/organizational profile, or a campaign index page.

#### Include the right links in your breadcrumbs

- Include only parent page links and leave out the current page. It’s stated in the page title, just beneath the breadcrumbs.

- Add up to 3 parent page links if using both the Canada.ca and home page links, for a maximum of 5 breadcrumbs links.

- Avoid duplication by linking each breadcrumb to a unique page.

#### Structure breadcrumb levels based on site navigation

Breadcrumbs represent the location of the current page in relation to the site’s navigation model.

- Order breadcrumb levels by their corresponding level in the site’s hierarchy.

- Avoid using the breadcrumbs component to show progress through the user journey.

- Limit breadcrumbs to 5 levels. Displaying too many levels of breadcrumbs items can be overwhelming.

Tip: If using with other navigation components, like the top navigation and side navigation, align both sets of links, so elements reflect a similar path through the site. This provides a consistent navigation experience and helps visitors understand their current location.

#### Write specific parent page link text

- Make link text clear and specific to help a person decide if they should leave the current page. Spell out where a person will go or what they’ll find when they select a link.

- Keep parent link text short (ideally under 20 characters) and distinctive. This helps people scan the links in the breadcrumbs and find what they’re looking for.

- Shorten parent page link text to improve readability and reduce space. Where possible, reflect the title of the page in the breadcrumb without including the full page name.

#### Place breadcrumbs before the main element

Place breadcrumbs at the top of a page, before the main content. This way a skip-to-content link can work to let a person skip all navigation links, including breadcrumbs.

## Code guidance and examples

### Coding and accessibility for breadcrumbs

#### Start with the Canada.ca link and the homepage link

- Maintain the Canada.ca link as the default first breadcrumbs link for most websites and pages.

- Opt to replace the Canada.ca link with the homepage link on transactional pages and stand alone websites or products. To remove the Canada.ca link at the head of the breadcrumbs, set the `hide-canada-link` attribute to `true`.

Tip: For a process that does not have a traditional landing page, link to the starting page of the process.

#### Place breadcrumbs before the `<main>` element

Place breadcrumbs at the top of a page, before the `<main>` element. This way a skip-to-content link can work to let a person skip all navigation links, including breadcrumbs.

#### Use breadcrumb items for breadcrumbs links

Add a new breadcrumbs link to the breadcrumbs component by using the `<gcds-breadcrumbs-item>` component. Add it through the `href` property.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### Breadcrumbs item `href`

Use `href` to define the destination URL for a `gcds-breadcrumbs-item`. This attribute is required for the breadcrumb item to function as a link.

```html
<gcds-breadcrumbs>
  <gcds-breadcrumbs-item href="#">
    Home page
  </gcds-breadcrumbs-item>
  <gcds-breadcrumbs-item href="#">
    Parent page link
  </gcds-breadcrumbs-item>
</gcds-breadcrumbs>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `hide-canada-link`

Use `hide-canada-link` to show or hide the default canada.ca link in the breadcrumbs. By default, it’s set to `false`, which means the canada.ca link appears as the first breadcrumb item.

```html
<gcds-breadcrumbs>
  <gcds-breadcrumbs-item href="#">
    Home page
  </gcds-breadcrumbs-item>
  <gcds-breadcrumbs-item href="#">
    Parent page link
  </gcds-breadcrumbs-item>
</gcds-breadcrumbs>
```

When set to `true`, the default canada.ca link is hidden, and only the breadcrumb items provided are displayed.

```html
<gcds-breadcrumbs hide-canada-link>
  <gcds-breadcrumbs-item href="#">Home page</gcds-breadcrumbs-item>
  <gcds-breadcrumbs-item href="#">Parent page link</gcds-breadcrumbs-item>
</gcds-breadcrumbs>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

Use the `default` slot to add `gcds-breadcrumbs-item` subcomponents. Use the `default` slot of the breadcrumbs item to add the item’s text.

```html
<gcds-breadcrumbs hide-canada-link>
<gcds-breadcrumbs-item href="#">Home page</gcds-breadcrumbs-item>
<gcds-breadcrumbs-item href="#">Parent page link</gcds-breadcrumbs-item>
</gcds-breadcrumbs>
```
