# Checkboxes

Tags: `<gcds-checkboxes>`  
Also called: checklist, check list  
Docs: https://design-system.canada.ca/en/components/checkboxes/

Checkboxes provide a set of options for multiple responses.

## API (from custom-elements.json, v1.7.1)

### `<gcds-checkboxes>`

Checkboxes provide a set of options for multiple responses.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `name` | `string` |  | **yes** | Name attribute for a checkboxes element. |
| `options` | `CheckboxObject[] \| string` |  | **yes** | Options to render checkboxes buttons |
| `autofocus` | `boolean` |  |  | If true, the checkbox will be focused on component render |
| `disabled` | `boolean` |  |  | Specifies if the checkboxes are disabled or not. |
| `error-message` | `string` |  |  | Set this to display an error message for invalid <gcds-checkboxes> |
| `form` | `string` |  |  | The ID of the form that the checkboxes belong to. |
| `hide-label` | `boolean` | `false` |  | For single checkbox, specifies if the label is hidden or not. |
| `hide-legend` | `boolean` | `false` |  | For checkbox groups, specifies if the legend is hidden or not. |
| `hint` | `string` |  |  | Hint displayed below the label. |
| `legend` | `string` |  |  | Set the legend for fieldset form group. |
| `required` | `boolean` |  |  | Specifies if the checkboxes are required or not. |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |
| `value` | `string \| string[]` | `[]` |  | Value for checkboxes component. |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `validator`: `(string \| ValidatorEntry \| Validator<string>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the checkboxes, returns a ValidityState object that represents the validity states this element is in.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the checkbox loses focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string[]>` Emitted when a checkbox has been changed. Contains the new value in the event detail.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<void>` Emitted when the checkbox has been clicked.
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when the checkbox has a validation error.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the checkbox has focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string[]>` Emitted when a checkbox has been inputted. Contains the new value in the event detail.
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<object>` Emitted when the checkbox has a validation error.

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-checkboxes
- `getValidationMessage()` - Get validationMessage of gcds-checkboxes
- `validate()` - Call any active validators

## Use case

Take a look at what checkboxes do to see if they fit the problem you’re solving for.

### Problems checkboxes solve

Use checkboxes to make a request for info with a set of options when you are expecting the user to select one or more options from a short list of items to:

- Limit the number of answers to your question.

- Let a person choose one or several answers from a contained list by checking off the right ones.

- Give a person the ability to answer without writing by selecting one or multiple items from a list.

### Related components

Radios to give a single option from a larger set of options.

Select to give a single option from a medium to long sized list in a dropdown format.

Inputs for short, single-line responses.

## Design guidance

### Checkboxes anatomy

- The **legend for checkboxes** acts as a heading to identify the grouped checkboxes. Legend text is left aligned and in sentence case where only the initial letter is capitalized. (Only applies when there are multiple options to choose from.)
- The **hint text for checkboxes** gives specific requirements for filling out the checkboxes successfully. (Only applies when there are multiple options to choose from.)
- The **checkbox** lets people choose or clear an option.
- The **checkmark** is a visual cue that indicates an option is selected.
- The **option label** names or describes the option.
- The **option hint text** gives additional information about the option.

### Design and accessibility for checkboxes

#### Make selections clear

- Limit the number of options to 7.

- Limit text for each option to a few words or a short phrase. Include hint text if more context is needed.

- Order options in a logical way. This could mean alphabetically or from most to least common.

#### Make instructions clear

- Avoid assuming a person will know how to use a checkbox.

- Use the hint text to specify they can make multiple selections. For example, “Select all that apply”.

- Avoid pre-selecting a checkbox. This makes it more likely that users will miss a question and submit the wrong answer.

#### Hide the legend or labels only when the purpose is clear

- Visually hide the legend or labels only when the purpose of the checkboxes is clearly communicated by surrounding context, making the label visually redundant, such as in:

- tables or other space-constrained layouts

- settings and configurations

- Always provide a legend and labels to support assistive technologies.

- Omit hint text if you hide the legend, as it can lack context and be distracting or confusing.

## Code guidance and examples

### Coding and accessibility for checkboxes

#### Apply required attributes

Always use the following required attributes with `gcds-checkboxes`:

- `name`

- `options`

#### Use the `options` attribute

- The `options` attribute uses an array of objects to render each checkbox.

- Within each object in the `options` array, always use the object’s required properties:

- `id`

- `label`

- The rest of the properties are optional.

Example:

```
{
  'id'!: '',
  'label'!: '',
  'value'?: '',
  'checked'?: '',
  'hint'?: '',
}
```

#### Set attributes based on number of checkboxes

##### Single checkbox

- Add a single object in the `options` attribute to render a single checkbox.

- Fieldset is removed from a single checkbox.

##### Group of checkboxes

- Add more than one object in the `options` attribute to render multiple checkboxes.

- Use the `legend` attribute to render the fieldset.

#### Use the `value` attribute

- The `value` attribute provides a quick reference to the values checked inside the `gcds-checkboxes` component.

- `value` is formatted as an array of strings.

Example:

```
[“checkboxOneValue”, checkboxTwoValue”]
```

#### Add hint text

Use the `hint` attribute to add hint text.

#### Hide the legend or labels

- Visually hide the legend or label, while maintaining it for assistive technologies.

- Set the `hide-legend` attribute to true to visually hide the legend for checkbox groups.

- Set the `hide-label` attribute to true to visually hide the label for a single checkbox.

- Avoid adding hint text if the legend and/or labels are hidden.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for checkboxes.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `legend`

The `legend` attribute defines the legend for a group of checkboxes.

```html
<gcds-checkboxes
legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `name`

The `name` attribute identifies the checkboxes component within a form. It is used to reference the component after a form is submitted.

```html
<gcds-checkboxes
legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `options`

The `options` attribute defines the checkboxes to render by taking an array of objects, one per checkbox.

```html
<gcds-checkboxes
legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `autofocus`

The `autofocus` attribute controls whether the first checkbox will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the checkbox is focused on render.

```html
<gcds-checkboxes
autofocus
legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `disabled`

The `disabled` attribute controls whether the checkboxes are disabled or not. By default, it is set to `false`.

```html
<gcds-checkboxes
disabled legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `error-message`

The `error-message` attribute defines the error message to display for invalid checkboxes.

```html
<gcds-checkboxes
error-message="Error message"
legend="Legend" name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `form`

The `form` attribute specifies the ID of the form that the checkboxes belong to.

```html
<gcds-checkboxes
form="form-id"
legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `hide-label`

The `hide-label` attribute controls whether the label is hidden or not.

```html
<gcds-checkboxes
hide-label name="checkbox"
options='[
  { "label": "Label for checkbox 1", "id": "checkbox1", "value": "checkbox1"}
]'>
</gcds-checkboxes>
```

##### `hide-legend`

The `hide-legend` attribute controls whether the legend is hidden or not.

```html
<gcds-checkboxes
hide-legend legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the label or legend.

```html
<gcds-checkboxes
hint="Hint / Example message."
legend="Legend" name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `required`

The `required` attribute controls whether the checkbox fieldset is required or not. When set to `true`, it adds “required” after the label or legend, and activates the required validator.

```html
<gcds-checkboxes
required legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the checkboxes.

```html
<gcds-checkboxes
validate-on="other"
required legend="Legend"
name="checkexample"
options='[
  {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
  {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
]'>
</gcds-checkboxes>
```

##### `value`

The `value` attribute contains an array of the values of all currently checked checkboxes. It updates as selections change.

```html
<gcds-checkboxes
  value='["check2"]'
  legend="Legend"
  name="checkexample"
  options='[
    {"id": "form-check-1", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check1"},
    {"id": "form-check-2", "label": "Label", "hint": "Description or example to make the option clearer.", "value": "check2"}
  ]'>
</gcds-checkboxes>
```
