# Side navigation

Tags: `<gcds-side-nav>`, `<gcds-nav-group>`, `<gcds-nav-link>`  
Also called: sidebar menu, lefthand nav, vertical navigation  
Docs: https://design-system.canada.ca/en/components/side-navigation/

A side navigation is a vertical list of page links on the left side of the screen.

## API (from custom-elements.json, v1.7.1)

### `<gcds-side-nav>`

A side navigation is a vertical list of page links on the left side of the screen.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `label` | `string` |  | **yes** | Label for navigation landmark |

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

Check out side navigation use cases to see if they fit the problem you’re solving for.

### Problems side navigation solves

It’s optional to use side navigation in products for:

- Making second and third level navigation visible in information architectures that have 1-3 levels of navigation.

- Presenting a consistent navigation across pages with a large number of top level items, like technical documentation with broad category sets.

- Enabling navigation between various categories or sections so a person can scan and compare like objects in a set.

- Providing ready access to global navigation between deep navigation structures.

### Related components

Top navigation for straightforward navigation of simple hierarchies.

## Design guidance

### Side navigation anatomy

- The **home nav link** identifies the site using text or a logo and it links to the home page. It uses a nav link that is added within the `home` slot.
- The **nav link** takes a person to a page. The link is highlighted in blue to indicate the current page state.
- The **nav group** displays the group name and expands and collapses the submenu. The submenu is a list of related nav links that are revealed when the nav group expands. The chevron icon points right when the submenu is hidden and down when it’s revealed.

### Design and accessibility for side navigation

#### Set the home nav link

- The home nav link can be a shorter version of the full name.

- For logos, include descriptive text.

#### Choose meaningful nav link text

- Make link text clear and specific to help a person decide if they should leave the current page. Spell out where a person will go or what they’ll find when they select a link.

- Keep link text short and distinctive so a person can scan the links and find what they’re looking for.

- If using breadcrumbs, align the content hierarchy in both sets of links, so each component reflects a similar path through the site.

Tip: Read nav link text aloud in sequence to note similarities in how link text sounds when read by an assistive device.

#### Add navigation groups to the side navigation

- Opt to include a second or third level with a navigation group to the side navigation.

- Use a hierarchical structure of up to 3 levels to categorize and order nav group items in a logical manner.

- Nest items into categories consistent with the product’s information architecture.

#### Organize nav group items

- Generally, order top level items by importance or highest priority for the person using your service.

- Logically group like items in category sets and base your ordering on the type of information in a set and what it will be used for.

- For known-item searching, order the links alphabetically – or numerically for items represented by numbers.

- Use a sequential order for sets of process-based tasks or information that is time based that could follow a natural chronological order.

- Test category names, divisions, and ordering to use the words and mental models that will be most intuitive to people using them.

## Code guidance and examples

### Coding and accessibility for side navigation

#### Place the side navigation in the page layouts

Implement the side navigation in the product layout so that it’s present on all pages.

#### Use the side navigation with other components

If using breadcrumbs, align the content hierarchy in both sets of links, so the components reflect a similar path through the site.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `label`

The `label` attribute defines the label for the navigation landmark.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
</gcds-side-nav>
```

##### `menu-label` (`gcds-nav-group`)

The `menu-label` attribute of the `gcds-nav-group` subcomponent defines the label for the nav group menu.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features" open-trigger="Features">
  <gcds-nav-link href="#">Review in both official languages side-by-side</gcds-nav-link>
  <gcds-nav-link href="#">Get form responses delivered securely</gcds-nav-link>
</gcds-nav-group>
</gcds-side-nav>
```

##### `open-trigger` (`gcds-nav-group`)

The `open-trigger` attribute of the `gcds-nav-group` subcomponent defines the label for the collapsed button trigger.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features" open-trigger="Features">
  <gcds-nav-link href="#">Review in both official languages side-by-side</gcds-nav-link>
  <gcds-nav-link href="#">Get form responses delivered securely</gcds-nav-link>
</gcds-nav-group>
</gcds-side-nav>
```

##### `href` (`gcds-nav-link`)

The `href` attribute of the `gcds-nav-link` subcomponent specifies the URL of the page the link goes to.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
</gcds-side-nav>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `close-trigger` (`gcds-nav-group`)

The `close-trigger` attribute of the `gcds-nav-group` subcomponent defines the label for the expanded button trigger.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features" open-trigger="Features" close-trigger="Close Features">
  <gcds-nav-link href="#">Review in both official languages side-by-side</gcds-nav-link>
  <gcds-nav-link href="#">Get form responses delivered securely</gcds-nav-link>
</gcds-nav-group>
</gcds-side-nav>
```

##### `open` (`gcds-nav-group`)

The `open` attribute of the `gcds-nav-group` subcomponent controls whether the nav group is expanded or not. By default, it is set to `false`.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features" open-trigger="Features" open>
  <gcds-nav-link href="#">Review in both official languages side-by-side</gcds-nav-link>
  <gcds-nav-link href="#">Get form responses delivered securely</gcds-nav-link>
</gcds-nav-group>
</gcds-side-nav>
```

##### `current` (`gcds-nav-link`)

The `current` attribute of the `gcds-nav-link` subcomponent indicates whether the page is current or not.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link current href="#">Contact us</gcds-nav-link>
</gcds-side-nav>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding navigation groups and navigation links.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Why GC Forms</gcds-nav-link>
<gcds-nav-link href="#">Contact us</gcds-nav-link>
<gcds-nav-group menu-label="Features" open-trigger="Features">
  <gcds-nav-link href="#">Review in both official languages side-by-side</gcds-nav-link>
  <gcds-nav-link href="#">Get form responses delivered securely</gcds-nav-link>
</gcds-nav-group>
</gcds-side-nav>
```

##### `home`

The `home` slot is for adding the home link or site title.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-link href="#" slot="home">GC Forms</gcds-nav-link>
</gcds-side-nav>
```

##### `default` (`gcds-nav-group`)

The `default` slot of the `gcds-nav-group` subcomponent is for adding the list of navigation links.

```html
<gcds-side-nav label="Side navigation component preview">
<gcds-nav-group menu-label="Features" open-trigger="Features">
  <gcds-nav-link href="#">Review in both official languages side-by-side</gcds-nav-link>
  <gcds-nav-link href="#">Get form responses delivered securely</gcds-nav-link>
</gcds-nav-group>
</gcds-side-nav>
```

##### `default` (`gcds-nav-link`)

The `default` slot of the `gcds-nav-link` subcomponent is for adding the navigation link content.

```html
<gcds-nav-link href="#">GC Forms</gcds-nav-link>
```
