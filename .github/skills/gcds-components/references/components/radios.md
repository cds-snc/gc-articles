# Radios

Tags: `<gcds-radios>`  
Also called: radio button, radio  
Docs: https://design-system.canada.ca/en/components/radios/

Radios provide a set of options for a single response.

## API (from custom-elements.json, v1.7.1)

### `<gcds-radios>`

Radios provide a set of options for a single response.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `legend` | `string` |  | **yes** | Label or legend for the group of radio elements |
| `name` | `string` |  | **yes** | The `name` attribute for the radios, used to group radio elements together |
| `options` | `RadioObject[] \| string` |  | **yes** | Options to render radio buttons |
| `autofocus` | `boolean` |  |  | If true, the input will be focused on component render |
| `disabled` | `boolean` |  |  | Specifies if an input element is disabled or not. |
| `error-message` | `string` |  |  | Set this to display an error message for invalid radios |
| `form` | `string` |  |  | The ID of the form that the radios belong to. |
| `hide-legend` | `boolean` | `false` |  | Specifies if the legend is hidden or not. |
| `hint` | `string` |  |  | Hint displayed below the label and above the radio elements |
| `required` | `boolean` |  |  | Specifies if a form field is required or not. |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |
| `value` | `string` |  |  | Default value for the element |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `validator`: `(string \| ValidatorEntry \| Validator<string>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the input, returns a ValidityState object that represents the validity states this element is in.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the radios has lost focus
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string>` Emitted when a radios option is checked (but not when unchecked). Contains new value in event detail
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when radios has a validation error
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when radios has received focus
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string>` Emitted when radios has been changed as a direct result of a user action (a radio option has been selected). Contains new value in event detail
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<void>` Emitted when radios has passed validation

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-radios
- `getValidationMessage()` - Get validationMessage of gcds-radios
- `validate()` - Call any active validators

## Use case

Take a look at what problems radios solve to see if they fit the problem you’re solving for.

### Problems radios solves

Use radios to request a single-option response to:

- Limit the number of answers to your question.

- Specify the possible answers to your question.

- Give a person the ability to answer without writing by selecting one item from a list.

### Related components

Checkboxes when you are expecting the user to select multiple options from a list of items.

Select when you can give someone a medium to large set of options for a known single selection.

## Design guidance

### Radio group anatomy - with fieldset

- The **legend for radios** acts as a heading to identify the grouped radios. Legend text is left-aligned and in sentence case (only the initial letter is capitalized).
- The **hint text for radios** gives specific requirements for selecting the appropriate response.
- The **radio button** indicates the state of the selection.
- The **checked button** shows what option has been selected.
- The **option label** describes the selection. Label text is in sentence case.
- The **option hint text** gives additional information about the selection.

### Design and accessibility for radios

#### Make selections clear

- Limit the number of options to 7.

- Limit text for each option to a few words or a short phrase. Include hint text if more context is needed.

- Order options in a logical way. This could mean alphabetically or from most to least common.

- When possible, include a “None of the above” option to avoid forcing an incorrect selection. A selection can’t be unselected unless another option is selected.

#### Make instructions clear

- Don’t assume a person will know they can only select one answer.

- Use the hint text to specify they can only make one selection. For example, “Select the response that best applies”.

- Avoid pre-selecting a radio option. This makes it more likely that a person will miss a question and submit the wrong answer.

## Code guidance and examples

### Coding and accessibility for radios

#### Apply required attributes

Always use the following required attributes with `gcds-radios`:

- `name`

- `legend`

- `options`

#### Use the `options` attribute

- The `options` attribute uses an array of objects to render each radio button.

- Within the `options` attribute of each radio button, always use the object’s required properties:

- id

- label

- The rest of the properties are optional.

Example:

```
{
  'id'!: '',
  'label'!: '',
  'value'!: '',
  'checked'?: '',
  'hint'?: '',
}
```

#### Use the `value` attribute

The `value` attribute provides a quick reference of the value selected inside the `gcds-radios` component.

#### Add hint text

Use the `hint` attribute to add hint text.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for radios.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `legend`

The `legend` attribute defines the legend for a group of radios.

```html
<gcds-radios
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `name`

The `name` attribute identifies the radios component within a form. It is used to reference the component after a form is submitted.

```html
<gcds-radios
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `options`

The `options` attribute defines the radios to render by taking an array of objects, one per radio button.

```html
<gcds-radios
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `autofocus`

The `autofocus` attribute controls whether the first radio button will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the radio button is focused on render.

```html
<gcds-radios
autofocus
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `disabled`

The `disabled` attribute controls whether the radios are disabled or not. By default, it is set to `false`.

```html
<gcds-radios
disabled
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `error-message`

The `error-message` attribute defines the error message to display for invalid radios.

```html
<gcds-radios
error-message="Error message"
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `form`

The `form` attribute specifies the ID of the form that the radios belong to.

```html
<gcds-radios
form="form-id"
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `hide-legend`

The `hide-legend` attribute controls whether the legend is hidden or not.

```html
<gcds-radios
hide-legend
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the label or legend.

```html
<gcds-radios
hint="Hint / Example message."
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `required`

The `required` attribute controls whether the radio fieldset is required or not. When set to `true`, it adds “required” after the legend, and activates the required validator.

```html
<gcds-radios
required
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the radios.

```html
<gcds-radios
validate-on="other"
required
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```

##### `value`

The `value` attribute contains the value of the currently selected radio button. It updates when the selection changes.

```html
<gcds-radios
value="radio1"
legend="Legend"
name="radio"
options='[
  {"id":"form-radio-1","label":"Label 1","hint":"Description or example to make the option clearer", "value": "radio1"},
  {"id":"form-radio-r","label":"Label 2","hint":"Description or example to make the option clearer", "value": "radio2"}
]'>
</gcds-radios>
```
