# Theme and topic menu

Tags: `<gcds-topic-menu>`  
Also called: global navigation, theme-based navigation  
Docs: https://design-system.canada.ca/en/components/theme-and-topic-menu/

The theme and topic menu is a navigation to the top tasks of Government of Canada websites.

## API (from custom-elements.json, v1.7.1)

### `<gcds-topic-menu>`

The theme and topic menu is a navigation to the top tasks of Government of Canada websites.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `home` | `boolean` | `false` |  | Sets the homepage styling |

**Methods** (async, call on the element):

- `closeAllMenus()` - Close all theme menus
- `toggleNav()` - Toggle open theme and topic menu
- `updateNavItemQueue()` - Update keyboard focus queue

## Use case

### Problems the theme and topic menu solves

Use theme and topic menu to:

- Provide global navigation across Government of Canada web pages, when people commonly need to access these pages from your website.

### Related components

Header for placing the Government of Canada branded header landmark.

Footer for placing the Government of Canada branded footer landmark.

Top navigation and side navigation for a more customized navigation.

## Design guidance

### Theme and topic menu anatomy

- The **menu button** toggles the Theme and topic menu open or closed.
- The **theme and topic list** includes the pre-set main themes for Canada.ca.
- The **flyout** is a list of relevant links to each topic that appears on hover. All links will load and update automatically.
- **Most requested links** is a list of the most frequent actions taken in each theme. All links will load and update automatically.

### Accessibility and design for the theme and topic menu

- Style the menu button label as capital letters, but write the label in sentence case (Menu), so screen readers don’t read each letter individually.

- Place the theme and topic menu in the header underneath the divider line.

## Code guidance and examples

### Coding and accessibility for theme and topic menu

#### Use the theme and topic menu with other components

Add the theme and topic menu directly to the header component by passing a child element with the `slot="menu"` attribute in the header. This will place the theme and topic menu in the header after the language toggle, signature, and search slots.

**Note**: If applying the theme and topic menu to the front page of Canada.ca, use the `home` attribute to render with the correct styling.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `home`

The `home` attribute sets the homepage styling.

```html
<gcds-topic-menu home>
</gcds-topic-menu>
```
