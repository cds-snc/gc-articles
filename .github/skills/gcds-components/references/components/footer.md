# Footer

Tags: `<gcds-footer>`  
Also called: site footer  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/footer/

The footer is the responsive Government of Canada branded footer landmark.

## API (from custom-elements.json, v1.7.1)

### `<gcds-footer>`

The footer is the responsive Government of Canada branded footer landmark.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `contextual-heading` | `string` |  |  | Heading for contextual slot and nav landmark |
| `contextual-links` | `object \| string` |  |  | Object of list items for contextual band. Format: { link-label: link-href } |
| `display` | `"compact" \| "full"` | `'compact'` |  | Display mode of the footer |
| `sub-links` | `object \| string` |  |  | Object of list items for sub-footer. Format: { link-label: link-href } |

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked. Contains the href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

Footer is a required component for Canada.ca pages.

Take a look at what problems the footer solves to see if they fit the problem you’re solving for.

### Problems footers solve

Use this footer landmark – as part of a trusted brand – for apps, forms, or other transactional digital services to communicate:

- The end of a product or site page.

- A Government of Canada digital service or product offering a broad range of services and information.

- The Government of Canada identity through the wordmark.

### Related components

Header for placing the Government of Canada branded header landmark.

Top navigation for guiding navigation through a website using a landmark.

## Design guidance

### Footer anatomy

Footer elements are required for all pages unless noted.

- The **contextual band** contains three links specific to your site or product. You can choose which links go there. Optional for all pages.
- The **main band** contains links to common Government of Canada themes. These links cannot be changed. Optional for pages that aren’t standard pages.
The **GC footer links band** contains the official Government of Canada wordmark and up to five links. Some of the links are optional and some can be modified.

Optional for pages that aren’t standard pages:

- Social media directory for the GC.
- Mobile app directory for the GC.
- About Canada.ca.

Can be modified to your specific program:

- Terms and conditions.
- Privacy statement.
- The **Canada wordmark** is the global identifier of the Government of Canada. Always preserve the integrity of the Government of Canada signature. Never modify it in any way, stretch it, or change the colours or text.

### Design and accessibility for footers

GC Design System components are designed to adapt to the size of the screen or frame where they’re being viewed. As an exception, the text size in the header and footer is fixed.

#### Check footer requirements

**What’s required on a Canada.ca standard page**

Always include the following components on a standard page:

- Main band.
GC footer links band.

- Social media link.
- Mobile app link.
- About Canada.ca link.
- Terms and conditions link.
- Privacy statement link.
- Canada wordmark.

Always preserve the integrity of the Government of Canada signature. Never modify it in any way, stretch it, or change the colours or text.

It’s optional to include the following components on a standard page:

- Contextual band.

**What’s optional on all other Canada.ca pages**

It’s optional to include the following components on pages that are not standard pages.

- Contextual band.
- Main band.
- Social media link in the GC footer links band.
- Mobile app link in the GC footer links band.
- About Canada.ca link in the GC footer links band.

#### Add optional bands

- Consider using the contextual band to give people specific actions or information related to your site.

- While required on all standard pages, consider using the main band for any pages on Canada.ca.

#### Modify links

- In the contextual band, modify the three links to your specific site. Choose links that people will expect to find in a footer, like “Contact us”, “Careers” or “News”.

- In the GC footer links band, modify the “Terms and conditions” and “Privacy” links to your specific site.

- Make link text clear and specific to help a person decide if they should leave the current page. Spell out where a person will go or what they’ll find when they select a link.

## Code guidance and examples

### Coding and accessibility for footers

#### Choose a display mode using the display property

Use the `display` property to choose one of the two display modes: `compact` or `full`.

Choose the **compact display** to include:

- The footer links band and the Government of Canada wordmark.

Include the **full display** if you need to include:

- The main band with a large selection of Government of Canada corporate links.

- The footer links band and the Government of Canada wordmark.

Opt to include the contextual band to add up three specific links for your site.

#### Set up the GC footer links band

- Always maintain the integrity of the Government of Canada wordmark. Do not modify the wordmark in any way.

- Keep the Canada wordmark, privacy link, and terms and conditions link. The other links are only required on standard pages, otherwise you can remove.

#### Add optional elements

- Add content to the contextual band by using both the `contextual-heading` and `contextual-links` attributes.

- Use the `contextual-heading` attribute to assign heading text and the navigational landmark label of the contextual band.

- Use the `contextual-links` attribute to set a maximum of three links in the contextual band. Add support links for your product or service, such as contact details for a program.

- For the contextual band, set the `contextual-links`, by passing an object or an object in a string in the following format:

```
{
“1st link label”: “href”
“2nd link label”: “href”
“3rd link label”: “href”
}
```

- For the footer links band, set the `sub-links`, by passing an object or an object in a string in the following format:

```
{
“1st link label”: “href”
“2nd link label”: “href”
“3rd link label”: “href”
}
```

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `contextual-heading`

The `contextual-heading` attribute defines the heading text for the contextual navigation section in the footer. This heading is also used to label the footer’s navigation landmark, supporting clearer structure and accessibility.

```html
<gcds-footer contextual-heading="Canadian Digital Service" contextual-links='{ "Why GC Notify": "#", "Features": "#", "Activity on GC Notify": "#" }'>
</gcds-footer>
```

##### `contextual-links`

The `contextual-links` attribute defines the set of links displayed in the footer’s contextual navigation section. It accepts an object where each key represents the link label and each value represents the corresponding URL. Format: `{ link-label: link-href }`.

```html
<gcds-footer contextual-heading="Canadian Digital Service" contextual-links='{ "Why GC Notify": "#", "Features": "#", "Activity on GC Notify": "#" }'>
</gcds-footer>
```

##### `display`

The `display` attribute controls which sections of the footer are rendered. By default, it is set to `compact`, displaying only the sub-footer section.

```html
<gcds-footer></gcds-footer>
```

When it is set to `full`, both the main footer and sub-footer sections are displayed.

```html
<gcds-footer display="full"></gcds-footer>
```

##### `sub-links`

The `sub-links` attribute defines the set of links displayed in the sub-footer section. It accepts an object where each key represents the link label and each value represents the corresponding URL. Format: `{ link-label: link-href }`.

```html
<gcds-footer sub-links='{ "Terms and conditions": "#", "Privacy": "#" }'>
</gcds-footer>
```
