# Top navigation

Tags: `<gcds-top-nav>`, `<gcds-nav-group>`, `<gcds-nav-link>`  
Also called: site menu, navigation, navbar  
Docs: https://design-system.canada.ca/en/components/top-navigation/

A top navigation is a horizontal list of page links.

## API (from custom-elements.json, v1.7.1)

### `<gcds-top-nav>`

A top navigation is a horizontal list of page links.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `label` | `string` |  | **yes** | Label for navigation landmark |
| `alignment` | `"end" \| "start"` | `'start'` |  | Nav alignment |

**Slots:**

- `default` - Slot for the navigation groups and navigation links.
- `home` - Slot for the home link or site title.

### `<gcds-nav-group>`

Navigational group with expandable or dropdown functionality, allowing for better organization of navigation links.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `menu-label` | `string` |  | **yes** | Label for the nav group menu |
| `open-trigger` | `string` |  | **yes** | Label for the collapsed button trigger |
| `close-trigger` | `string` |  |  | Label for the expanded button trigger |
| `open` | `boolean` | `false` |  | Has the nav group been expanded |

**Slots:**

- `default` - Slot for the list of navigation links.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the button blurs.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<void>` Emitted when the button has been clicked.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the button has been focus.

**Methods** (async, call on the element):

- `focusTrigger()` - Focus button element
- `toggleNav()` - Toggle the nav open or closed

### `<gcds-nav-link>`

Navigation link within a navigation group or menu, allowing users to navigate to different sections of a website or application.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `href` | `string` |  | **yes** | Link href |
| `current` | `boolean` |  |  | Current page flag |

**Slots:**

- `default` - Slot for the navigation link content.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

**Methods** (async, call on the element):

- `focusLink()` - Focus the link element

## Use case

Check out top navigation use cases to see if they fit the problem you’re solving for.

### Problems top navigation solves

The top navigation helps give visitors a sense of a site’s structure, purpose, and priorities. It’s optional to use for websites to:

- Place popular links in a prominent position on the page.

- Provide up to two levels of site hierarchy.

- Offer a familiar wayfinding pattern.

- Signal the active page and give a cue to the person’s current position on the site.

### Related components

Side navigation for products with up to three levels of navigation when the main content fits a narrow page width.

Header for placing the Government of Canada branded header landmark.

Breadcrumbs for a path from each level of the site’s hierarchy to the current page.

## Design guidance

### Top navigation anatomy – on desktop

- The **home nav link** identifies the site using text or a logo and it links to the home page. It uses a nav link that is added within the `home` slot.
- The **nav link** is a link to a popular page or section on the site. Nav links can be in the top level if they’re standalone.
- The **nav group** can be collapsed and expanded and generally contains a list of 2-5 links.
- The **nav group label** describes the category of links in that group. It acts as a button to expand and collapse the group of links.
- The **nav group chevron icon** points down when the nav group is collapsed and up when it’s expanded.
- The **nav link with active indicator** gives visual prominence to the current page.

### Design and accessibility for top navigation

#### Set the home nav link

- The home nav link can be a shorter version of the full name.

- For logos, include descriptive text.

Note: On mobile, the home nav link does not link to the homepage.

#### Choose meaningful nav link text

- Make link text clear and specific to help a person decide if they should leave the current page. Spell out where a person will go or what they’ll find when they select a link.

- Keep link text short and distinctive so a person can scan the links and find what they’re looking for.

- Limit the number of links in your top navigation. Too much information increases cognitive load and can be overwhelming to some readers.

- If using breadcrumbs, align the content hierarchy in both set of links, so both components reflect a similar path through the site.

Tip: Read top navigation link text aloud in sequence to note similarities in how link text sounds when read by an assistive device.

#### Add navigation groups to the top navigation

- Opt to include a second level with a navigation group, sometimes called stairs or dropdowns, to the top navigation.

- In the second level, limit the group to 7 links. Target 2-5 in each group.

## Code guidance and examples

### Coding and accessibility for top navigation

#### Add the top navigation to each page

- Implement the top navigation in the website layout so that it’s present on every page.

- Opt to add a nav group with a second level of navigation by placing the `<gcds-nav-link>` in a `<gcds-nav-group>`.

#### Use the top navigation with other components

- If using breadcrumbs, align the content hierarchy in both set of links, so both components reflect a similar path through the site.

- If you’re using the header component, add the top navigation directly to the header by passing a child element with the `slot="menu"` attribute into the header. This will place the top nav in the header after the language toggle, signature, and search slots.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `label`

The `label` attribute defines the label for the navigation landmark.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
</gcds-top-nav>
```

##### `menu-label` (`gcds-nav-group`)

The `menu-label` attribute of the `gcds-nav-group` subcomponent defines the label for the nav group menu.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features submenu" open-trigger="Features">
  <gcds-nav-link href="#">Personalize messages</gcds-nav-link>
  <gcds-nav-link href="#">Schedule messages</gcds-nav-link>
</gcds-nav-group>
</gcds-top-nav>
```

##### `open-trigger` (`gcds-nav-group`)

The `open-trigger` attribute of the `gcds-nav-group` subcomponent defines the label for the collapsed button trigger.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features submenu" open-trigger="Features">
  <gcds-nav-link href="#">Personalize messages</gcds-nav-link>
  <gcds-nav-link href="#">Schedule messages</gcds-nav-link>
</gcds-nav-group>
</gcds-top-nav>
```

##### `href` (`gcds-nav-link`)

The `href` attribute of the `gcds-nav-link` subcomponent specifies the URL of the page the link goes to.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
</gcds-top-nav>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `alignment`

The `alignment` attribute defines the alignment of the top navigation. The available options are:

- `start` (default)

- `end`

```html
<gcds-top-nav alignment="end" label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
</gcds-top-nav>
```

##### `close-trigger` (`gcds-nav-group`)

The `close-trigger` attribute of the `gcds-nav-group` subcomponent defines the label for the expanded button trigger.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features submenu" open-trigger="Features" close-trigger="Close Features">
  <gcds-nav-link href="#">Personalize messages</gcds-nav-link>
  <gcds-nav-link href="#">Schedule messages</gcds-nav-link>
</gcds-nav-group>
</gcds-top-nav>
```

##### `open` (`gcds-nav-group`)

The `open` attribute of the `gcds-nav-group` subcomponent controls whether the nav group is expanded or not. By default, it is set to `false`.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features submenu" open-trigger="Features" open>
  <gcds-nav-link href="#">Personalize messages</gcds-nav-link>
  <gcds-nav-link href="#">Schedule messages</gcds-nav-link>
</gcds-nav-group>
</gcds-top-nav>
```

##### `current` (`gcds-nav-link`)

The `current` attribute of the `gcds-nav-link` subcomponent indicates whether the page is current or not.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link current href="#">Contact us</gcds-nav-link>
</gcds-top-nav>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding navigation groups and navigation links.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features submenu" open-trigger="Features">
  <gcds-nav-link href="#">Personalize messages</gcds-nav-link>
  <gcds-nav-link href="#">Schedule messages</gcds-nav-link>
</gcds-nav-group>
</gcds-top-nav>
```

##### `home`

The `home` slot is for adding the home link or site title.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-link href="#" slot="home">GC Notify</gcds-nav-link>
</gcds-top-nav>
```

##### `default` (`gcds-nav-group`)

The `default` slot of the `gcds-nav-group` subcomponent is for adding the list of navigation links.

```html
<gcds-top-nav label="Top navigation component preview">
<gcds-nav-group menu-label="Features submenu" open-trigger="Features">
  <gcds-nav-link href="#">Personalize messages</gcds-nav-link>
  <gcds-nav-link href="#">Schedule messages</gcds-nav-link>
</gcds-nav-group>
</gcds-top-nav>
```

##### `default` (`gcds-nav-link`)

The `default` slot of the `gcds-nav-link` subcomponent is for adding the navigation link content.

```html
<gcds-nav-link href="#">GC Notify</gcds-nav-link>
```
