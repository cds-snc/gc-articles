# Button

Tags: `<gcds-button>`  
Also called: call to action, CTA  
Docs: https://design-system.canada.ca/en/components/button/

The button is an interactive object that emphasizes an action.

## API (from custom-elements.json, v1.7.1)

### `<gcds-button>`

The button is an interactive object that emphasizes an action.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `button-id` | `string` |  |  | The buttonId attribute specifies the id for a <button> element. |
| `button-role` | `"danger" \| "primary" \| "secondary" \| "start"` | `'primary'` |  | Set the main style |
| `disabled` | `boolean` |  |  | The disabled attribute for a <button> element. |
| `download` | `string` |  |  | The download attribute specifies that the target (the file specified in the href attribute) will be downloaded when a user clicks on the hyperlink |
| `href` | `string` |  |  | The href attribute specifies the URL of the page the link goes to |
| `name` | `string` |  |  | The name attribute specifies the name for a <button> element. |
| `rel` | `string` |  |  | The rel attribute specifies the relationship between the current document and the linked document |
| `size` | `"regular" \| "small"` | `'regular'` |  | Set the button size |
| `target` | `string` |  |  | The target attribute specifies where to open the linked document |
| `type` | `"button" \| "link" \| "reset" \| "submit"` | `'button'` |  | Set button types |
| `value` | `string` |  |  | The value attribute specifies the value for a <button> element. |

**Slots:**

- `default` - Slot for the button/link label.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the button loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string \| void>` Emitted when the button has been clicked. Contains the value or href in the event detail.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the button has focus.

## Use case

Take a look at what buttons do to see if they fit the problem you’re solving for. Then select the best subtype, called the button role, for the use case you need to meet.

### Problems buttons solve

Use a button to initiate an action, like:

- Start a task or flow.

- Submit, save, or delete form data.

- Make a choice or give consent.

- Sign in to an account.

- Move forward or back in a sequence.

### Related components

Links to navigate to a new page, website, file, or section on the current page.

Details to hide or show a section of content.

### Choose a button type

The button type changes the HTML to do a specific task. Pick the type based on the function you need.

#### Button type

Use the button type when you’re expecting a person to:

- Delete, copy, or download information.

- Make a binary choice.

- Give consent or agreement.

#### Submit type

Use the submit type when you’re expecting a person to:

- Send a form or a request.

- Save information.

- Sign in or sign out of an account.

#### Reset type

Use the reset type when you’re expecting a person to:

- Clear data entered on a form.

Tip: Use the reset type sparingly as it’s a destructive action.

#### Link type

Use the link type when you’re expecting a person to:

- Navigate to other pages.

- Navigate to external sites. An external icon appears at the end of external links.

Note: Use the link component when you need to embed a link in paragraph text or navigation bars.

### Choose a button role

The button role changes the styling of a button to visually signal its action. Pick the role that best fits the action.

Primary button preview

Submit

#### Primary button

- Critical actions in a flow or as the default button.
- Only use a single time.

Note: More than one main call to action on the same page makes it harder for a person to know what to do next.

Start button preview

Start

#### Start button

Formerly called supertask button.

- The start of a task or flow.
- Bigger than other buttons, so use by itself and not beside other buttons.
- Only use once on a page.

Tip: Use either primary button or start button on a page, not both.

Secondary button preview

Cancel

#### Secondary button uses

- Supporting actions.
- To highlight a common action but not the most important one.
- Can use more than once on a page if there are multiple secondary options.

Danger button preview

Delete

#### Danger button uses

- Serious actions like removing, clearing, or deleting information.
- Changes that may be difficult to reverse.

Tip: For permanent actions, ask a second time so a person explicitly agrees before accepting.

## Design guidance

### Button anatomy

- The **container** gives the button its visual prominence.
- The **label** states the action that’ll happen when someone interacts with the button. Text is in sentence case, where only the first letter is capitalized.

### Design and accessibility for buttons

#### Make the button findable

- Limit the number of buttons on a page. Reserve buttons for the most important actions.

- Place buttons in a regular and predictable way.

- Avoid making a person needlessly scroll, tab, or type by providing the path to the next action early.

- Check the button border’s visibility against the surface where you’re placing it.

#### Write short, specific, and descriptive button labels

- Make the button’s action obvious with a short, specific label that has a verb, or a verb and a noun, like “Submit” or “Get estimate”.

- Use sentence case.

- Use unique and specific accompanying text to prevent repetition for screen readers.

- Use literal phrases. Avoid expressions that may be unfamiliar to non-fluent speakers.

## Code guidance and examples

### Coding and accessibility for buttons

#### Check for visual accessibility

Check the button border’s visibility against the surface where you’re placing it.

#### Select the right size

Use the small button attribute for actions when space is limited.

Note: The height of small buttons is WCAG AA compliant, but not AAA compliant.

#### Use disabled button sparingly

- Use the disabled button sparingly when something needs to be done before an action can be initiated.

- Where possible, use other interventions like an error message to block incomplete form submissions.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `button-id`

Use `button-id` to set the `id` for a button.

```html
<gcds-button id="submit-ei-application">Submit</gcds-button>
```

##### `button-role`

Use `button-role` to set the main style for a button. Choose from:

- `primary` (default)

- `secondary`

- `danger`

- `start`

```html
<gcds-button button-role="start">Start</gcds-button>
<gcds-button>Submit</gcds-button>
<gcds-button button-role="secondary">Cancel</gcds-button>
<gcds-button button-role="danger">Delete</gcds-button>
```

##### `disabled`

Set `disabled` to `true` to make the button non-interactive. By default, `disabled` is set to `false`.

```html
<gcds-button button-role="danger" disabled="true">
  Delete
</gcds-button>
```

##### `name`

Use `name` to specify the name of the button when submitting form data. This attribute helps identify which button triggered a form submission, especially when multiple buttons exist in a form.

```html
<form action="..." method="post">
  <gcds-button button-role="secondary" name="save-draft" type="submit">
    Save draft
  </gcds-button>
  <gcds-button button-role="secondary" name="submit-application" type="submit">
    Submit application
  </gcds-button>
</form>
```

##### `size`

Use `size` to set the button size. Choose from:

- `regular` (default)

- `small`

```html
<gcds-button>Default size</gcds-button>
<gcds-button size="small">Small size</gcds-button>
```

##### `type`

Use `type` to define the button’s function. Choose from:

- `button` (default)

- `link`

- `reset`

- `submit`

```html
<gcds-button>Button</gcds-button>
<gcds-button type="link">Link</gcds-button>
<gcds-button type="reset">Reset</gcds-button>
<gcds-button type="submit">Submit</gcds-button>
```

##### `value`

Use `value` to define the data sent to the server when the button submits a form. The button only sends this `value` if it has a `name` attribute and if it triggered the submission. If multiple submit buttons exist, only the clicked button sends its value.

```html
<form action="..." method="post">
  <gcds-button button-role="secondary" type="submit" name="applicationAction" value="save">
    Save draft
  </gcds-button>
  <gcds-button button-role="secondary" type="submit" name="applicationAction" value="submit">
    Submit application
  </gcds-button>
</form>
```

##### `download`

Use `download` to make the button download the file referenced by the `href` attribute, rather than navigate to it.

**This attribute is only supported when the button `type` is set to `link`**.

```html
<gcds-button type="link" href="/files/application-receipt.pdf" download="true">
  Download receipt
</gcds-button>
```

##### `href`

Use `href` to define the destination of the link.

**This attribute is only supported when the button `type` is set to `link`**.

```html
<gcds-button type="link" href="https://www.canada.ca/en/services/benefits.html">
  View benefits information
</gcds-button>
```

##### `rel`

Use `rel` to specify the relationship between the current document and the linked document. Use it for security and privacy purposes, especially when links open in a new tab or window.

**This attribute is only supported when the button `type` is set to `link`**.

```html
<gcds-button type="link" href="https://www.canada.ca/en/services/benefits.html" target="_blank" rel="noopener noreferrer">
  View benefits information
</gcds-button>
```

##### `target`

Use `target` to specify where the linked resource opens: in the same browsing context, or in a new tab or window.

**This attribute is only supported when the button `type` is set to `link`**.

```html
<gcds-button type="link" href="https://www.canada.ca/en/services/benefits.html" target="_blank" rel="noopener noreferrer">
  View benefits information
</gcds-button>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

Use the `default` slot to add the button or link label text.

```html
<gcds-button>Submit</gcds-button>
```
