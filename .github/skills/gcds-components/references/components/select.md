# Select

Tags: `<gcds-select>`  
Also called: dropdown, select menu  
Docs: https://design-system.canada.ca/en/components/select/

A select provides a large list of options for single selection.

## API (from custom-elements.json, v1.7.1)

### `<gcds-select>`

A select provides a large list of options for single selection.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `label` | `string` |  | **yes** | Form field label. |
| `name` | `string` |  | **yes** | Name attribute for select form element. |
| `select-id` | `string` |  | **yes** | Id attribute for a select element. |
| `autocomplete` | `string` |  |  | String to have autocomplete enabled. |
| `autofocus` | `boolean` |  |  | If true, the select will be focused on component render |
| `default-value` | `string` |  |  | The default value is an optional value that gets displayed before the user selects an option. |
| `disabled` | `boolean` | `false` |  | Specifies if a select element is disabled or not. |
| `error-message` | `string` |  |  | Error message for an invalid select element. |
| `form` | `string` |  |  | The ID of the form that the select field belongs to. |
| `hide-label` | `boolean` | `false` |  | Specifies if the label is hidden or not. |
| `hint` | `string` |  |  | Hint displayed below the label. |
| `required` | `boolean` | `false` |  | Specifies if a form field is required or not. |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |
| `value` | `string` |  |  | Value for a select element. |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `validator`: `(string \| ValidatorEntry \| Validator<string>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the select, returns a ValidityState object that represents the validity states this element is in.

**Slots:**

- `default` - Slot for options and option groups.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the select loses focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string>` Emitted when the select value has changed.
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when the select has a validation error.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the select has focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string>` Emitted when the select has received input.
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<object>` Emitted when the select has a validation error.

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-select
- `getValidationMessage()` - Get validationMessage of gcds-select
- `validate()` - Call any active validators

**CSS parts:** `::part(wrapper)`

## Use case

### Problems select solves

Use select within a form to:

- Specify more than 7 possible answers in a predefined scrollable list.

- Limit the number of possible answers to a question.

- Give a person the ability to answer without writing, by selecting one item from a list.

### Related components

Checkboxes when you are expecting the user to select 1 or more options from a list of items.

Radios when you can give someone a small set of options for a known single selection with less than 7 options.

## Design guidance

### Select anatomy

- The **label** states the choice the select represents.
- The **hint text** communicates additional context or requirements about the question and expected response.
- The **default value** can be used as a placeholder for the select.
- The **icon** is used as a visual aid to indicate that the field can be expanded.

### Design and accessibility for select

#### Make select options clear

- Limit text for each option to a few words or a short phrase.

- Generally, order options alphabetically. In some cases, it can be helpful for you to order them from most to least common.

- When possible, include an option like “None” or “None of the above”.

#### Help a person understand how to use selects

- Avoid assuming a person will know how to use a select.

- Use hint text to include a cue for users unfamiliar with selects so they know they can only make a single selection. For example, “Select the response that best applies”.

- Avoid pre-selecting a select option. This makes it more likely that users will miss a question and submit a wrong answer.

- When possible, include an option like “None” or “None of the above”. Once selected, the option cannot be de-selected, or reversed, except by choosing another option within the group. When this happens, the respondent is forced to choose an incorrect response or abandon the task.

#### Use other questions to reduce select option size

- Try asking the users other questions before using the select in order to reduce the amount of options.

- If the list of items is smaller (6-7 items or less), consider using radio buttons instead.

#### Hide the label only when the purpose is clear

- Visually hide the label only when the purpose of the select is clearly communicated by surrounding context, making the label visually redundant, such as in:

- tables or other space-constrained layouts

- settings and configurations

- Always provide a label to support assistive technologies.

- Omit hint text if you hide the label, as it can lack context and be distracting or confusing.

## Code guidance and examples

### Coding and accessibility for select

#### Apply required attributes

Always use the following required attributes with `gcds-select`:

- `name`

- `label`

- `select-id`

#### Set the default value

Use the `default-value` attribute to set the first option in the select list. This avoids pre-selection of an option.

#### Add hint text

- Use the `hint` attribute to add hint text.
- Avoid adding hint text in the field where it will disappear once the field is selected or a response starts to be entered.

#### Hide the label

- Set the `hide-label` attribute to true to visually hide the label while maintaining it for assistive technologies.
- Avoid adding hint text when the label is hidden.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for select.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `label`

The `label` attribute defines the label for the select.

```html
<gcds-select select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `name`

The `name` attribute identifies the select component within a form. It is used to reference the component after a form is submitted.

```html
<gcds-select select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `select-id`

The `select-id` attribute specifies the unique identifier for the select component.

```html
<gcds-select select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `autocomplete`

The `autocomplete` attribute controls whether the browser can suggest previously entered values for the select.

```html
<gcds-select autocomplete="on" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `autofocus`

The `autofocus` attribute controls whether the select will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the select is focused on render.

```html
<gcds-select autofocus="true" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `default-value`

The `default-value` attribute defines an optional value that gets displayed before the user selects an option.

```html
<gcds-select select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `disabled`

The `disabled` attribute controls whether the radios are disabled or not. By default, it is set to `false`.

```html
<gcds-select disabled="true" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `error-message`

The `error-message` attribute defines the error message to display for invalid radios.

```html
<gcds-select error-message="Error message" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `form`

The `form` attribute specifies the ID of the form that the radios belong to.

```html
<gcds-select form="form-id" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `hide-label`

The `hide-legend` attribute controls whether the legend is hidden or not.

```html
<gcds-select hide-label="true" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the label or legend.

```html
<gcds-select hint="Hint / Example message." select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `required`

The `required` attribute controls whether the radio fieldset is required or not. When set to `true`, it adds “required” after the legend, and activates the required validator.

```html
<gcds-select required="true" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the radios.

```html
<gcds-select validate-on="other" required="true" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

##### `value`

The `value` attribute contains the value of the currently selected radio button. It updates when the selection changes.

```html
<gcds-select value="2" select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding options and option groups.

```html
<gcds-select select-id="select-preview" label="Label" name="select-preview" default-value="Select option">
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3">Option 3</option>
</gcds-select>
```
