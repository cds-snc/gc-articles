# Header

Tags: `<gcds-header>`  
Also called: global header, brand header  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/header/

The header is the responsive Government of Canada branded header landmark.

> **Upstream example issues (found by `gcds_lint.py`; the API table is correct):**
> - <gcds-top-nav alignment="right"> must be one of: end, start

## API (from custom-elements.json, v1.7.1)

### `<gcds-header>`

The header is the responsive Government of Canada branded header landmark.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `lang-href` | `string` |  | **yes** | GcdsLangToggle - The href attribute specifies the URL of the opposite language page |
| `skip-to-href` | `string` |  | **yes** | Top navigation - Skip to content href |
| `signature-has-link` | `boolean` | `true` |  | GcdsSignature - GCDS signature links to Canada.ca |

**Slots:**

- `account` - Slot to add a custom account link in the bottom-right of the header.
- `banner` - Slot to add a banner across the top of the header.
- `breadcrumb` - Slot to add breadcrumbs at the bottom of the header.
- `menu` - Slot to add a menu below the divider line.
- `search` - Slot to add a search field to the right of the header.
- `signature` - Slot to replace Government of Canada signature.
- `skip-to-nav` - Slot to add a hidden skip to content navigation at the top of the header.
- `toggle` - Slot to add a custom language toggle in the top-right of the header.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked. Contains the href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

The header is a required component for Government of Canada websites and has specific requirements for Canada.ca pages.

Take a look at what problems the header solves to see if they fit the problem you’re solving for.

### What problems headers solve

Use this header landmark and brand element for Canada.ca pages, GC websites, and applications to:

- Consistently identify a GC digital service or product.

- Signal the start of a page.

- Support ease of navigation for people using GC services.

- Include the required Federal Identity Program branded Government of Canada signature.

### Related components

Footer to signal the end of a page with a Government of Canada branded landmark.

Signature to identify Government of Canada brand and identity.

## Design guidance

### Header anatomy

Header elements are required for GC sites unless noted.

- The **signature** is a branding element that identifies a page as a Government of Canada space. On Canada.ca the signature links to the Canada.ca homepage.
- The **skip-to-content** link lets a person pass navigation to go directly to the main content. The link is hidden until a person navigates to it with a keyboard (focus visible).
- The **language toggle** is a link that lets a person switch between French and English content by linking to the other Official Language.
- The **search** lets a person enter keywords or phrases to find content. It can be set to a local or global search form. Optional, except on Canada.ca campaign and standard pages.
- The **divider line** visually separates the main header elements and the page content.
- The **top navigation** is a primary navigation component for access to priority pages. Optional and, when used, sits beneath the divider line of the same colour.
- The **breadcrumbs** lay a path from the current location to each higher level page within the site hierarchy. On Canada.ca the first link leads to the Canada.ca homepage.
- The **account button** (optional) is a component for account-related actions.

### Design and accessibility for the header

GC Design System components are designed to adapt to the size of the screen or frame where they’re being viewed. As an exception, the text size in the header and footer is fixed.

#### Check header requirements

Here’s what’s required for the header on GC sites.

- Signature.
- Language toggle.
- Skip-to-content link.
- Always preserve the integrity of the Government of Canada signature. Never modify it in any way, stretch it, or change the colours or text.

**What's required on a Canada.ca standard or campaign page**

Always include the following components in the header of a standard or campaign page and maintain the default settings.

- Signature that links to Canada.ca.
- Language toggle.
- Search.
- Breadcrumbs that link to Canada.ca from the first link.
- Skip-to-content link.
- Divider line.

**Note**: As an exception on legacy applications, the language toggle can be omitted if it results in a destructive action (a person loses their data when language is switched).

**What's optional to include on a Canada.ca standard or campaign page**

Opt to include:

- An approved program or institution specific search form instead of the global search form.
- Account button.
- Top navigation.

#### Improve the accessibility of the header

- Set up a skip-to-content link as a shortcut for people using an assistive technology and improve keyboard navigation. The link skips navigation elements to lead directly to the main page content.

- Avoid putting other elements ahead of the skip-to-content link. It’s more discoverable if it’s the first or second item.

#### Include optional elements in your header

- Consider using the top navigation for:

- services and websites that need a dedicated primary navigation.

- services or products that are self-contained and targets an internal audience, like GC Design System.

- Consider using the account button for account-related actions, like “Sign in” or “Sign out”.

## Code guidance and examples

### Coding and accessibility for headers

GC Design System components are designed to adapt to the size of the screen or frame where they’re being viewed. As an exception, the text size in the header and footer is fixed.

#### Preserve the signature element in the header for GC sites

- Maintain the signature for all GC sites.

- Use the `signature-variant` attribute to set the Government of Canada signature to either `colour` or `white`. For white backgrounds, keep the `colour` default setting for the signature.

- Always preserve the integrity of the Government of Canada signature. Avoid modifying it in any way, stretching, or changing the colours or text.

- On Canada.ca pages, set the `signature-has-link` attribute to `true` to set the Government of Canada signature link to Canada.ca.

#### Include the language toggle on all GC sites

- Include the language toggle by setting the `lang-href` attribute. Setting the `lang-href` also sets the href of the language toggle.

- Use the `lang` attribute to set the site language and the toggle will offer the other Official Language.

#### Set up the skip-to-content to improve accessibility

- Set up a skip-to-content link as a shortcut for people using an assistive technology and improve keyboard navigation.

- Set the href of the `skip-to-content` link in the header’s top navigation using the `skip-to-href` attribute.

- Use the `skip-to-nav` slot to replace the default top navigation with the `skip-to-content` link.

- Pass a child element with the `slot="skip-to-nav"` attribute to place the element first in the header.

#### Include the breadcrumbs and search on Canada.ca sites

- On Canada.ca pages, keep the default setting of `hide-canada-link` to `false`.

- Add the breadcrumbs component by passing a child element with the `slot="breadcrumb"` attribute. This will place the breadcrumbs in the header below the language toggle, signature and search slot.

- Add a new breadcrumbs link to the breadcrumbs component by using the `gcds-breadcrumbs-item` component. The link can be added through the `href` property.

- Include a search form by adding by adding `<gcds-search slot="search"></gcds-search>` or passing a child element with the `slot="search"` attribute. This will place the element under the language toggle and beside the signature in the header.

- Set the search to a local or global search form. By default, the component is set to search Canada.ca.

#### Set up a top navigation

- Add a top navigation by passing a child element with the `slot="menu"` attribute. This will place the element in the header below the language toggle, signature, and search slots.

- Opt to add a banner by passing a child element with the `slot="banner"` attribute. This will place the element across the top of the header under the `skip-to-nav` element.

#### Set up account controls

- Add an account button by passing a `gcds-button` with the `slot=”account”` attribute. This will place the button element, styled as the Canada.ca contextual sign in button, across from the breadcrumbs.

- Configure the button label to reflect the account-related action, like “Sign in” or “Sign out”.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `lang-href`

The `lang-href` attribute specifies the URL of the same page in another language, typically English or French. Use this attribute to provide a link that allows users to switch between available language versions.

```html
<gcds-header
  lang-href="fr-url-here"
  skip-to-href="#id-main-content-here"
></gcds-header>
```

##### `skip-to-href`

The `skip-to-href` attribute specifies the URL or anchor for the “Skip to content” link. This link allows users, especially those using assistive technologies, to quickly navigate to the main content of the page, improving accessibility.

```html
<gcds-header
  lang-href="fr-url-here"
  skip-to-href="#id-main-content-here"
></gcds-header>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `signature-has-link`

The `signature-has-link` attribute determines whether the header signature links to Canada.ca. By default, it’s set to `true`, which means the signature is clickable.

```html
<gcds-header
  lang-href="fr-url-here"
  skip-to-href="#id-main-content-here"
></gcds-header>
```

When it’s set to `false`, a non-clickable signature is displayed.

```html
<gcds-header
  lang-href="fr-url-here"
  skip-to-href="#id-main-content-here"
  signature-has-link="false"
></gcds-header>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `account`

The `account` slot is for adding an account-related button below the header. Use this slot with a `gcds-button` to include a button element, styled as the Canada.ca contextual sign in button, for actions like “Sign in” and “Sign out”.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-button type="link" href="#" slot="account">
    Sign in
  </gcds-button>
</gcds-header>
```

##### `banner`

The `banner` slot is for adding a banner across the top of the header. Use this slot to display site-wide alerts or promotional messages.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <div slot="banner">Banner slot</div>
</gcds-header>
```

##### `breadcrumb`

The `breadcrumb` slot is for adding breadcrumbs below the header. Use this slot to show the user’s current page location and navigation path.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-breadcrumbs slot="breadcrumb">
    <gcds-breadcrumbs-item href="#">
      Home page
    </gcds-breadcrumbs-item>
    <gcds-breadcrumbs-item href="#">
      Parent page link
    </gcds-breadcrumbs-item>
  </gcds-breadcrumbs>
</gcds-header>
```

##### `menu`

The `menu` slot is for adding a navigation menu below the divider line. For instance, you can use the top-nav component within this slot to provide the site’s primary navigation links.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-top-nav label="Top navigation" alignment="right" slot="menu">
    <gcds-nav-link href="#home" slot="home">
      GC Notify
    </gcds-nav-link>
    <gcds-nav-link href="#">Why GC Notify</gcds-nav-link>
    <gcds-nav-link href="#">Contact us</gcds-nav-link>
  </gcds-top-nav>
</gcds-header>
```

##### `search`

The `search` slot is for adding a search field to the header. Use this slot to allow users to search site content directly from the header.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-search slot="search"></gcds-search>
</gcds-header>
```

##### `signature`

The `signature` slot is for replacing the default Government of Canada signature. Use this slot to display a custom signature or logo if needed.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-signature slot="signature"></gcds-signature>
</gcds-header>
```

##### `skip-to-nav`

The `skip-to-nav` slot is for adding a “skip to content” link at the top of the header. By default, the header includes a “skip to main content” link to improve accessibility for users of assistive technologies. The target for this default link can be set using the `skip-to-href` prop. Use this slot to add additional skip links or a custom “skip to content” link.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-link href="#main-content" slot="skip-to-nav">
    Skip to main content
  </gcds-link>
</gcds-header>
```

##### `toggle`

The `toggle` slot is for adding a custom language toggle in the header. By default, the header includes a language toggle that includes English and French. The target for this default language toggle can be set using the `lang-href` prop. Use this slot to add additional available languages.

```html
<gcds-header lang-href="fr-url-here" skip-to-href="#id-main-content-here">
  <gcds-lang-toggle
    href="url-here"
    slot="toggle"
  ></gcds-lang-toggle>
</gcds-header>
```
