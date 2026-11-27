# Date input

Tags: `<gcds-date-input>`  
Also called: dates, dateinput, date, memorable date  
Docs: https://design-system.canada.ca/en/components/date-input/

A date input is a space to enter a known date.

## API (from custom-elements.json, v1.7.1)

### `<gcds-date-input>`

A date input is a space to enter a known date.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `format` | `"compact" \| "full" \| "iso"` |  | **yes** | Set this property to full to show month, day, and year form elements. Set it to compact to show only the month and year form elements.  Set it to `iso` to use numeric month input and `YYYY-MM-DD` entry order. |
| `legend` | `string` |  | **yes** | Fieldset legend |
| `name` | `string` |  | **yes** | Name attribute for the date input. |
| `autocomplete` | `"bday" \| "cc-exp" \| "off" \| "on"` |  |  | String to have autocomplete enabled. |
| `autofocus` | `boolean` |  |  | If true, the date-input will be focused on component render |
| `disabled` | `boolean` | `false` |  | Specifies if the date input is disabled or not. |
| `error-message` | `string` |  |  | Error message displayed below the legend and above form fields. |
| `form` | `string` |  |  | The ID of the form that the date-input field belongs to. |
| `hint` | `string` |  |  | Hint displayed below the legend and above form fields. |
| `max` | `string` |  |  | The maximum date that the date-input field can accept. Format: YYYY-MM-DD or YYYY-MM |
| `min` | `string` |  |  | The minimum date that the date-input field can accept. Format: YYYY-MM-DD or YYYY-MM |
| `required` | `boolean` | `false` |  | Specifies if a form field is required or not. |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |
| `value` | `string` |  |  | Combined date value from the two/three form elements. Format: YYYY-MM-DD or YYYY-MM |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `validator`: `(string \| ValidatorEntry \| Validator<string>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the date-input, returns a ValidityState object that represents the validity states this element is in.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when a date-input loses focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string>` Emitted when a date-input has changed. Contains the new value in the event detail.
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when a date-input has a validation error.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when a date-input has focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string>` Emitted when the date-input has received input. Contains the new value in the event detail.
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<object>` Emitted when a date-input has validated.

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-date-input
- `getValidationMessage()` - Get validationMessage of gcds-date-input
- `validate()` - Call any active validators

## Use case

### Problems date inputs solves

Take a look at what date inputs do to see if they fit the problem you’re solving for. Then select the best date input type for the use case you need to meet.

Use a date input to gather a date from a person when you’re expecting them to write:

- A date they already know, like their date of birth.

- A date they can easily find, like a health card expiry date.

- A specific date, like the date they moved to a new home.

- A date that doesn’t include the specific day of the week.

- Avoid using a date input for the selection of an unknown date. Use a date picker instead.

### Related components

Input when you want someone to input only a year or only a day of the month.

Select when you want someone to input only a month.

Date picker when you want someone to choose a not-yet-known date, like for appointment scheduling.

### Component formats

#### Month, day, year (English only)

- A specific date the person will know or can easily find.

- Used in English for written dates.

#### Day, month, year (French only)

- A specific date the person will know or can easily find.

- Used in French for written dates.

#### Year, month, day

- A specific date the person will know or can easily find.

- Used internationally for numeric dates.

#### Month, year

- For an approximate date.

## Design guidance

### Date input anatomy

- The **fieldset legend** states the information a person should enter in the date input field. Text is left-aligned and in sentence case (only the initial letter is capitalized). For the date input component, the fieldset is typically labelled as “date” or with the specific date type sought. The hint text shows the format of the date.
- The **month label** identifies the month field.
- The **month input** field can be a dropdown of written months to select from, or an input to enter 1-2 digits.
- The **day label** identifies the day input.
- The **day input** field can be 1 or 2 digits.
- The **year label** identifies the year input field.
- The **year input** field is limited to 4 digits.

### Design and accessibility for date inputs

#### Select a format that assists with data entry

Use a format that matches the format of the information you’re asking for. For example, if you are asking for a birth date from a health card, use the Y/M/D format.

#### Support task success with hint text

Use the hint text in the fieldset legend to help a person understand the format they can use to enter the date. For example, showing a single digit number without a leading zero, like September 7, rather than September 07 shows that a leading zero is not needed.

#### Write specific error messages

Write error messages for each field of the date input. Error messages must provide information on what is missing or on incorrect formatting. For example, if the year was input using 2 digits, state that the year should be 4 digits.

## Code guidance and examples

### Coding and accessibility for date inputs

#### Apply required attributes

Always use the following required attributes with `gcds-date-input`:

- `name`

- `legend`

- `format`

#### Choose a format

Use `full`, `compact`, or `iso` in the format attribute of date input.

- The `full` value will render with a year, month, and day form field.

- In this format, the month is a word.

- In English, the order is month, day, year.

- In French, the order is day, month, year.

- It will output a value formatted `YYYY-MM-DD`.

- The `compact` value will render with a year and month form fields.

- In this format, the month is a word.

- In English and French the order is month, year.

- It will output a value formatted `YYYY-MM`.

- The `iso` value will render the date input with a year, month, and day form field in that order.

- In this format, all inputs are numeric, following ISO standards.

- It will output a value formatted `YYYY-MM-DD`.

#### Use valid properties

Component properties have built-in validation. Required properties or properties prone to misuse will be validated. If there are any validation errors, the component will be non-functional and won't render. Review the browser's console for details and adjust the properties accordingly to ensure proper functionality.

#### Add hint text

- Use the `hint` attribute to add hint text.
- Avoid adding hint text in the field where it will disappear once the field is selected or a response starts to be entered.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for date input.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `format`

The `format` attribute sets how the date is formatted.

```html
<gcds-date-input
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `legend`

The `legend` attribute defines the legend for the date input.

```html
<gcds-date-input
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `name`

The `name` attribute identifies the date input component within a form. It is used to reference the component after a form is submitted.

```html
<gcds-date-input
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `autofocus`

The `autofocus` attribute controls whether the date input will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the date input is focused on render.

```html
<gcds-date-input
  autofocus="true"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `disabled`

The `disabled` attribute controls whether the date input is disabled or not. By default, it is set to `false`.

```html
<gcds-date-input
  disabled="true"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `error-message`

The `error-message` attribute defines the error message to display for invalid date inputs.

```html
<gcds-date-input
  error-message="Error message"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `form`

The `form` attribute specifies the ID of the form that the date input belongs to.

```html
<gcds-date-input
  form="form-id"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the legend.

```html
<gcds-date-input
  hint="Day can be 1 or 2 digits. Year must be 4 digits."
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `max`

The `max` attribute defines the maximum date that the date input can accept. Format: `YYYY-MM-DD` or `YYYY-MM`.

```html
<gcds-date-input
  max="2000-01-01"
  hint="The date must be before 2000-01-01"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `min`

The `min` attribute defines the minimum date that the date input can accept. Format: `YYYY-MM-DD` or `YYYY-MM`.

```html
<gcds-date-input
  min="2000-01-01"
  hint="The date must be after 2000-01-01"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `required`

The `required` attribute controls whether the date input field is required or not. When set to `true`, it adds “required” after the legend, and activates the required validator.

```html
<gcds-date-input
  required="true"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the date input.

```html
<gcds-date-input
  validate-on="other"
  required="true"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```

##### `value`

The `value` attribute sets the initial date displayed in the component. It also provides a reference to the entered date. Format: `YYYY-MM-DD` or `YYYY-MM`.

```html
<gcds-date-input
  value="2026-02-22"
  format="full"
  legend="Legend"
  name="example-default"
></gcds-date-input>
```
