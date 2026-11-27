# Signature

Tags: `<gcds-signature>`  
Also called: wordmark  
**Canada.ca required component.**  
Docs: https://design-system.canada.ca/en/components/signature/

The signature is the Government of Canada landmark identifier found in the header or footer.

## API (from custom-elements.json, v1.7.1)

### `<gcds-signature>`

The signature is the Government of Canada landmark identifier found in the header or footer.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `has-link` | `boolean` | `false` |  | Has link to canada.ca. Only applies to signature |
| `type` | `"signature" \| "wordmark"` | `'signature'` |  | The type of graphic to render |
| `variant` | `"colour" \| "white"` | `'colour'` |  | The colour variant to render |

## Use case

Signature is a required component for Government of Canada websites.

Check out use cases for the signature to see if they fit the problem you’re solving for.

### Problems the signature solves

Use the signature landmark as part of a trusted brand to:

- Provide site visitors with a recognizable corporate identity.

- Inform a person they’re reading content published by the Government of Canada.

- Offer a global link to the site’s home page in both Official Languages.

### Related components

Header for placing the Government of Canada branded header landmark.

Footer for placing the Government of Canada branded footer landmark.

### Component types

The signature and wordmark types appear together on Government of Canada sites and products.

#### Signature type

Use the **signature** type in the header beside the language toggle component to:

- Present the corporate signature of the Government of Canada.

- Provide users with a landmark for navigating back to the site’s homepage.

#### Wordmark type

Use the **wordmark** type in the in the bottom right corner of the footer to:

- Accompany the signature or another approved corporate signature of the Government of Canada on public-facing and internal sites and products.

- Present this official symbol and global identifier of the Government of Canada.

## Design guidance

### Signature anatomy

- The **Government of Canada Signature** is a brand identifier found in the site’s header. The signature is used as a global link to the site’s homepage.
- The **Canada Wordmark** is a brand identifier found in the site’s footer. It reinforces the brand by communicating to a site visitor that they’re reading content from the Government of Canada.

### Design and accessibility for the signature

#### Check signature requirements

The signature is required in the header and the wordmark is required in the footer on Canada.ca pages and GC sites.

**What's required on Canada.ca**

Always include the signature in the header and maintain default settings.

- Use black text, do not select white text.
- Keep placement in the top-left corner of the header on both desktop and mobile.
- Link to the Canada.ca homepage.

Always include the wordmark in the sub-footer band and maintain default settings.

- Use black text, do not select white text.
- Keep placement in the bottom-right corner of the footer.

**Note:** Default settings for the Signature and Wordmark follow the Design Standard for the Federal Identity Program.

#### Set the language order in the signature

- Display the French-first signature on French pages and the English-first on English pages.

- Maintain “Government of Canada” for the English descriptive (alt) text and “Gouvernement du Canada” for the French.

**Note:** The image is linked to the Canada.ca homepage in the same Official Language as the current page.

#### Apply non-standard colours accessibly

- Opt to apply non-standard colours when a higher level of contrast with the background is needed, like for dark mode.

- Opt to apply non-standard colours when a single colour is used in a product, like in black and white.

- When making changes to the flag colour, always match the colour of the flag symbol in the signature and in the wordmark.

- Always apply the same colour combination in the signature and wordmark. For example, if one is black and white, the other needs to be as well.

- For single colour, check the contrast ratio between the signature elements for, at minimum, WCAG AA compliance.

Caption: The standard colour style uses black text and a red flag applied to a white backdrop.
Caption: The reversed colour style uses white text and a red flag applied to a black backdrop.
Caption: The black and white style uses all black or all white. All black is the more common use.
Caption: Single colour style uses another colour if that colour is the only colour used in the product.

## Code guidance and examples

### Coding and accessibility for signature

#### Apply the signature or wordmark types

Use the signature type in the site’s header and the wordmark type in the site’s footer.

- Set the `type` attribute to `signature`.

- Set the `type` attribute to `wordmark`.

#### Set the language and colour

- Set the page’s language settings using the `lang` attribute. `En` will render the English version for an English page and `Fr` will render the French version for a French page.

- Link the signature to the Canada.ca homepage in the same Official Language as the current page. Set `has-link` attribute to `true` to link to Canada.ca.

- Set the component to either `colour` or `white` using the `variant` attribute.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `has-link`

The `has-link` attribute controls whether the signature links to Canada.ca. By default, it’s set to `false`, meaning the signature is displayed as static content and isn’t clickable.

```html
<gcds-signature></gcds-signature>
```

When it’s set to `true`, the signature becomes clickable and links to Canada.ca.

```html
<gcds-signature has-link="true"></gcds-signature>
```

##### `type`

The `type` attribute determines which graphic is displayed in the signature component. By default, it is set to `signature`, rendering the signature graphic.

```html
<gcds-signature></gcds-signature>
```

When it’s set to `wordmark`, the component displays the wordmark graphic instead.

```html
<gcds-signature type="wordmark"></gcds-signature>
```

##### `variant`

The `variant` attribute controls the colour of the signature component. By default, it is set to `colour`, rendering the coloured version of the signature.

```html
<gcds-signature></gcds-signature>
```

When it’s set to `white`, the component displays the white version of the signature, suitable for dark backgrounds.

```html
<div class="bg-dark p-300">
<gcds-signature variant="white"></gcds-signature>
</div>
```
