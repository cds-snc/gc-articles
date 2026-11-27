# Textarea

Tags: `<gcds-textarea>`  
Also called: text box  
Docs: https://design-system.canada.ca/en/components/textarea/

A textarea is a space to enter long-form information in response to a question or instruction.

## API (from custom-elements.json, v1.7.1)

### `<gcds-textarea>`

A text area is a space to enter long-form information in response to a question or instruction.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `label` | `string` |  | **yes** | Form field label |
| `name` | `string` |  | **yes** | Name attribute for a textarea element. |
| `textarea-id` | `string` |  | **yes** | Id attribute for a textarea element. |
| `autofocus` | `boolean` |  |  | If true, the textarea will be focused on component render. |
| `cols` | `number` |  |  | Defines width for textarea cols (the min-width for textarea's is 50%). |
| `disabled` | `boolean` | `false` |  | Specifies if a textarea element is disabled or not. |
| `error-message` | `string` |  |  | Error message for an invalid textarea element. |
| `form` | `string` |  |  | The ID of the form that the textarea belongs to. |
| `hide-label` | `boolean` | `false` |  | Specifies if the label is hidden or not. |
| `hide-limit` | `boolean` | `false` |  | If true, character limit counter will not be displayed under the textarea. |
| `hint` | `string` |  |  | Hint displayed below the label and above the textarea field. |
| `maxlength` | `number` |  |  | The maximum number of characters that the textarea field can accept. |
| `minlength` | `number` |  |  | The minimum number of characters that the textarea field can accept. |
| `required` | `boolean` | `false` |  | Specifies if a form field is required or not. |
| `rows` | `number` | `5` |  | Default value for textarea rows. |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |
| `value` | `string` |  |  | Default value for an input element. |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `validator`: `(string \| ValidatorEntry \| Validator<string>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the textarea, returns a ValidityState object that represents the validity states this element is in.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the textarea loses focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string>` Emitted when the textarea has changed.
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when the textarea has a validation error.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the textarea has focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string>` Emitted when the textarea has received input.
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<object>` Emitted when the textarea has a validation error.

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-textarea
- `getValidationMessage()` - Get validationMessage of gcds-textarea
- `validate()` - Call any active validators

## Use case

Take a look at what textareas do to see if they fit the problem you’re solving for.

### Problems textareas solve

Use a textarea to collect multi-line information when you’re expecting a person will write:

- An individual or unique response.

- To provide space for a long, freeform response.

Note: Only collect information you’re authorized to handle and be sure to safeguard it to Government of Canada standards.

### Related components

Input for short, single-line responses.

## Design guidance

### Textarea anatomy

- The **textarea label** states the information a person should enter in the field. Label text is left aligned and in sentence case (only the initial letter is capitalized).
- The **hint text** (optional) communicates specific requirements for each textarea to support task success.
- The **field** is the container a person interacts with to provide their response.
- The **character count** (optional) indicates the character limit that can be entered in the textarea.

### Design and accessibility for textareas

#### Write a short, specific, unique textarea label

- Ask only for the information you need using as few words as possible. Phrasing as a question can make a label unnecessarily long.

- Choose unique labels for each textarea and input within a page. A person browsing fields will hear the label text in rapid succession without cues to tell which label is for which context.

- Avoid using technical jargon, like “subject email address” or “account ID”. Instead be specific and personal, “full name”, “child’s age”.

- Avoid using colloquialisms and common verb phrases that are unfamiliar to non-fluent speakers.

- Explain any term that may not be clear. For example, “Bio (tell us a bit about your background).

#### Hide the label only when the purpose is clear

- Visually hide the label only when the purpose of the textarea is clearly communicated by surrounding context, making the label visually redundant, such as in:

- chat or messaging interfaces

- search components

- tables or other space-constrained layouts

- Always provide a label to support assistive technologies.

- Omit hint text if you hide the label, as it can lack context and be distracting or confusing.

#### Limit the use of placeholder text

- In forms, avoid using placeholder text to replace a label or to convey instructions or information a person needs to remember, as it can create accessibility issues.

- Outside of forms, use placeholder text only to reinforce a purpose that is already clear from visible context (for example, in search or conversational interfaces).

#### Support task success for textareas with hint text

- Add hint text to help a person filling in a textarea to provide a complete value.

- Write hint text to be instructional, brief, and specific.

#### Add character count to set expectations for text length

- Add character count to limit how many characters can be entered into the textarea.

- The character count shows remaining characters as they are entered, setting expectations and helping people respond with the right level of detail.

#### Write error messages for required textareas

- Use error messages to address missing and invalid entries.

- Write an error messages for all required inputs. If a field is optional, avoid using an error messages.

- Avoid blaming the person using the product. Instead, tell them exactly what to do to move forward.

## Code guidance and examples

### Coding and accessibility for textareas

#### Apply required attributes

Always use the following required attributes with `gcds-textarea`:

- `name`

- `label`

- `textarea-id`

#### Size the field to fit the response

- Use the `rows` attribute to set the height of a textarea to match the amount of text you expect someone to enter.

- Use input for responses that need less than 75 characters.

- Avoid setting the width lower than 50%.

- Use the maximum height of 7 rows for responses without a fixed length.

#### Add hint text

- Use the `hint` attribute to add hint text.
- Avoid adding hint text in the field where it will disappear once the field is selected or a response starts to be entered.

#### Add character limits

- Use the `max-length` attribute to enable character limits. This automatically adds “Characters left:” text below the textarea field.

- Use the `hide-limit` attribute to visually hide the text.

#### Hide the label

- Set the `hide-label` attribute to true to visually hide the label while maintaining it for assistive technologies.
- Avoid adding hint text when the label is hidden.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for textarea.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `label`

The `label` attribute defines the label for the textarea.

```html
<gcds-textarea
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `name`

The `name` attribute identifies the textarea component within a form. It is used to reference the textarea’s value when a form is submitted.

```html
<gcds-textarea
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `textarea-id`

The `textarea-id` attribute specifies the unique identifier for the textarea.

```html
<gcds-textarea
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `autofocus`

The `autofocus` attribute controls whether the textarea will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the textarea is focused on render.

```html
<gcds-textarea
  autofocus="true"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `cols`

The `cols` attribute defines the visible width of the textarea in average character widths. The minimum width is 50% of its container.

```html
<gcds-textarea
  cols="8"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `disabled`

The `disabled` attribute controls whether the textarea is disabled or not. By default, it is set to `false`.

```html
<gcds-textarea
  disabled="true"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `error-message`

The `error-message` attribute defines the error message to display for an invalid textarea.

```html
<gcds-textarea
  error-message="Error message"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `form`

The `form` attribute specifies the ID of the form that the textarea belongs to.

```html
<gcds-textarea
  form="form-id"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `hide-label`

The `hide-label` attribute controls whether the label is hidden or not.

```html
<gcds-textarea
  hide-label="true"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `hide-limit`

The `hide-limit` attribute controls whether the character limit counter is displayed or not. When set to `true`, the character limit counter is not displayed. By default, it is set to `false`.

```html
<gcds-textarea
  hide-limit="true"
  maxlength="20"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the label.

```html
<gcds-textarea
  hint="Hint / Example message."
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `maxlength`

The `maxlength` attribute defines the maximum number of characters that the textarea field can accept.

```html
<gcds-textarea
  maxlength="10"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `minlength`

The `minlength` attribute defines the minimum number of characters that the textarea field can accept.

```html
<gcds-textarea
  minlength="5"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `required`

The `required` attribute controls whether the textarea field is required or not. When set to `true`, it adds “required” after the label, and activates the required validator.

```html
<gcds-textarea
  required="true"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `rows`

The `rows` attribute defines the number of visible text rows in the textarea, which determines its height.

```html
<gcds-textarea
  rows="3"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the textarea.

```html
<gcds-textarea
  validate-on="other"
  required="true"
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```

##### `value`

The `value` attribute sets the initial content of the textarea. It updates to reflect the value entered by the user.

```html
<gcds-textarea
  value="Textarea content."
  textarea-id="textarea-example"
  label="Label"
  name="textarea-example"
></gcds-textarea>
```
