# Link

Tags: `<gcds-link>`  
Also called: anchor, hyperlink, call to action  
Docs: https://design-system.canada.ca/en/components/link/

A link is a navigational element that brings a person to a new page, website, file, or section on the current page.

## API (from custom-elements.json, v1.7.1)

### `<gcds-link>`

A link is a navigational element that brings a person to a new page, website, file, or section on the current page.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `href` | `string` |  | **yes** | The href attribute specifies the URL of the page the link goes to |
| `display` | `"block" \| "inline"` | `'inline'` |  | Sets the display behavior of the link |
| `download` | `string` |  |  | The download attribute specifies that the target (the file specified in the href attribute) will be downloaded when a user clicks on the hyperlink |
| `external` | `boolean` | `false` |  | Whether the link is external or not |
| `link-role` | `"default" \| "light"` | `'default'` |  | Sets the main style of the link. |
| `rel` | `string` |  |  | The rel attribute specifies the relationship between the current document and the linked document |
| `size` | `"inherit" \| "regular" \| "small"` | `'inherit'` |  | Set the link size |
| `target` | `string` | `'_self'` |  | The target attribute specifies where to open the linked document |
| `type` | `string` |  |  | The type specifies the media type of the linked document |

**Slots:**

- `default` - Slot for the link content.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked. Contains the href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

A link is a navigational element that takes a person to a website or opens a source, like a pdf.

### Problems component solves

Use links to:

- Allow a person to move fluidly between different pages or sections of a website or application.

- Direct people to external websites, clearly indicating when the link leads outside the current site.

- Let a person skip to a section heading using anchor links in a table of contents or page summary. They can then directly access specific sections or content without scrolling.

- Connect to email addresses or phone numbers, so a person can initiate communication directly by selecting the link.

- Start downloads for files, providing users with direct access to documents, media, or other downloadable content.

- Skip past navigational elements to get to main content.

### Related components

Buttons to emphasize an action.

Details to provide a way to hide or show a section of content.

### Choose a link role

A role sets the main style of the link.

#### Default role

Use the `default` `role` for:

- Most links.

- Links that are placed on a light background.

#### Light role

Use the `light` `role` for:

Links that need to appear on a dark background. Make sure the contrast ratio between the light link and dark background meets (WCAG) Web Content Accessibility Guidelines standards.

## Design guidance

### Link anatomy

- The **link text** navigates to a new page, website, or section on the current page. It specifies the link’s destination or, for downloads, identifies the file type, and size. Note: Opening links in a new tab or window can be disorienting for people and can cause accessibility issues.
The **link icon** is attached to certain link types, like download, phone, and email. Otherwise, it’s optional to add an icon.

- The download icon lets a person know that selecting the link will initiate a download.
- The phone icon identifies the phone number and selecting it initiates a phone call.
- The email icon identifies the email address and initiates a message from the default email application.

### Accessibility and design for links

#### Write specific and descriptive link text

- Make link text clear, descriptive, and specific so a person can predict where the link will take them and what they’ll find there.

- Avoid generic or vague link text, like “click here”, “read more”, or “home”.

- Within link text, place the most important information first, as this is helpful to screen-reader users.

- Provide enough information in the link text on the link’s purpose, rather than relying on the surrounding content. Some assistive technologies present links separately.

- Keep link text short and distinctive so a person is able to scan the links on a page. Generally limit text to 60 characters and avoid going over 80 characters. When writing in English, keep in mind French content will be around 30% longer.

- If the destination’s exact page title is too long for link text, include one or more recognizable keywords from that title.

Tip: Use navigation elements like breadcrumbs or top navigation so a person knows how to return to their original location.

#### Limit the number and density of links in your text

- Limit the number of informational links in your text. Too much information increases cognitive load and can be overwhelming to some readers.

- Consider separating links in a list or other format when you need to share several in the same section. If the links are informational, they may not be essential to the task.

- Avoid link density in unstructured text. Multiple links in a paragraph interrupt the flow of reading and can be distracting and repetitive with some assistive technologies.

#### Provide information for download files

- Provide information in the link text about the file type and size of files for download.

- Avoid placing the download link within paragraph text.

#### Add a skip-to-content link

- A skip-to-content link lets a person skip a cluster of navigation links and jump to the main content.

- To avoid covering up content, configure the link to push down content so it’s not floating. For desktop, place skip-to-content button at top left of the page so it doesn’t interrupt the flow.

#### Avoid external links when possible

- Avoid adding external links:

- In the middle of a person’s progress, like when a person’s filling out a form.
- If changing pages would make them lose unsaved progress.
- When starting a process would stop if the person follows a link, like when they’re watching a video or completing a main task.
- **When they’re invasive and disruptive to the flow of the page.**
- When it could be a surprise to a person to find themselves somewhere else unexpectedly.

- Make it obvious where a link will lead, so a person can decide if they should leave the current page.

- Communicate clearly when links have special conditions. For example, when they’re only available inside a closed network because of a firewall.

#### When to use buttons instead of links

- Choose links for most navigation, including within an application, for external sites, jumping to on-page elements, and for email addresses or phone numbers.

- Choose buttons for actions that modify data, change states, or initiate actions, like submitting a form, playing a video, or saving.

## Code guidance and examples

### Coding and accessibility for links

#### Provide information for download files

- Provide information in the link text about the file type and size of files for download: `<gcds-link href="application.pdf" download>Application 2023 (PDF, 1.5 MB)</gcds-link>`.

- Avoid placing the download link within paragraph text.

Note: Only files with URLs of the same origin as the website will be downloaded to the user’s device.

#### Add a skip-to-content link

- A skip-to-content link lets a person skip a cluster of navigation links and jump to the main content.

- To avoid covering up content, configure the link to push down content so it’s not floating, For desktop, place skip-to-content link at top left of the page so it doesn’t interrupt the flow.

#### Avoid external links when possible

- Add an external link by setting the `external` attribute to `true`.

- Avoid adding external links:

- In the middle of a person’s progress, like when a person’s filling out a form.
- If changing pages would make them lose unsaved progress.
- When starting a process would stop if the person follows a link, like when they’re watching a video or completing a main task.
- **When they’re invasive and disruptive to the flow of the page.**
- When it could be a surprise to a person to find themselves somewhere else unexpectedly.

- Make it obvious where a link will lead, so a person can decide if they should leave the current page.

- Communicate clearly when links have special conditions. For example, when they’re only available inside a closed network because of a firewall.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `href`

The `href` attribute specifies the URL of the page the link goes to.

```html
<gcds-link href="#">Navigation link</gcds-link>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `display`

The `display` attribute sets the display behaviour of the link. The available options are:

- `inline` (default)

- `block`

```html
<gcds-link display="block" href="#">
  Navigation link
</gcds-link>
```

##### `download`

The `download` attribute specifies that the target (the file specified in the href attribute) will be downloaded when a user clicks on the hyperlink.

```html
<gcds-link href="download.pdf" download="file.pdf" type="application/pdf">
  Download document (PDF, 1.5 MB)
</gcds-link>
```

##### `external`

The `external` attribute controls whether the link is external or not. When set to `true`, an external icon is added after the link.

```html
<gcds-link external="true" href="#">
  Navigation link
</gcds-link>
```

##### `link-role`

The `link-role` sets the main style of the link. The available options are:

- `default` (default)

- `light`

```html
<div class="bg-dark p-300">
  <gcds-link link-role="light" href="#">
    Navigation link
  </gcds-link>
</div>
```

##### `rel`

The `rel` attribute specifies the relationship between the current document and the linked document. It is commonly used for security and privacy purposes, especially when links open in a new tab or window.

```html
<gcds-link rel="license" href="#license">
  Navigation link
</gcds-link>
```

##### `size`

The `size` attribute sets the link size. The available options are:

- `inherit` (default)

- `regular`

- `small`

```html
<gcds-link size="small" href="#">Navigation link</gcds-link>
```

##### `target`

The `target` attribute specifies where the linked resource is opened. For example, it can open the link in the same browsing context or in a new tab or window.

```html
<gcds-link target="_blank" href="#">
  Navigation link
</gcds-link>
```

##### `type`

The `type` attribute specifies the media type of the linked document.

```html
<gcds-link href="download.pdf" download="file.pdf" type="application/pdf">
  Download document (PDF, 1.5 MB)
</gcds-link>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing the link text.

```html
<gcds-link href="#">Navigation link</gcds-link>
```
