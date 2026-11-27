# Input

Tags: `<gcds-input>`  
Also called: text input, input field  
Docs: https://design-system.canada.ca/en/components/input/

An input is a space to enter short-form information in response to a question or instruction.

## API (from custom-elements.json, v1.7.1)

### `<gcds-input>`

An input is a space to enter short-form information in response to a question or instruction.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `input-id` | `string` |  | **yes** | Id  attribute for an input element. |
| `label` | `string` |  | **yes** | Form field label |
| `name` | `string` |  | **yes** | Name attribute for an input element. |
| `autocomplete` | `string` |  |  | String to have autocomplete enabled. |
| `autofocus` | `boolean` |  |  | If true, the input will be focused on component render |
| `disabled` | `boolean` | `false` |  | Specifies if an input element is disabled or not. |
| `error-message` | `string` |  |  | Error message for an invalid input element. |
| `form` | `string` |  |  | The ID of the form that the input field belongs to. |
| `hide-label` | `boolean` | `false` |  | Specifies if the label is hidden or not. |
| `hint` | `string` |  |  | Hint displayed below the label and above the input field. |
| `inputmode` | `"decimal" \| "email" \| "none" \| "numeric" \| "search" \| "tel" \| "text" \| "url"` |  |  |  |
| `max` | `number \| string` |  |  | The maximum value that the input field can accept. Only applies to number input type. |
| `maxlength` | `number` |  |  | The maximum number of characters that the input field can accept. |
| `min` | `number \| string` |  |  | The minimum value that the input field can accept. Only applies to number input type. |
| `minlength` | `number` |  |  | The minimum number of characters that the input field can accept. |
| `pattern` | `string` |  |  | Specifies a regular expression the form control's value should match. See: https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/pattern |
| `readonly` | `boolean` |  |  | If true, the input field cannot be modified. |
| `required` | `boolean` | `false` |  | Specifies if a form field is required or not. |
| `size` | `number` |  |  | Size attribute for an input element to provide a visual indication of the expected text length to the user. |
| `step` | `"any" \| number` |  |  | A number that specifies the granularity that the value must adhere to. Valid for number type. See: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#step |
| `suggestions` | `SuggestionOption[] \| string` |  |  | Array of suggestion options. This creates a datalist element with options to represent permissible or recommended options available to choose from. |
| `type` | `"email" \| "number" \| "password" \| "search" \| "tel" \| "text" \| "url"` | `'text'` |  | Set Input types |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |
| `value` | `string` |  |  | Default value for an input element. |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `validator`: `(string \| ValidatorEntry \| Validator<string>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the input, returns a ValidityState object that represents the validity states this element is in.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the input loses focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string>` Emitted when the input has changed.
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when the input has a validation error.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the input has focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string>` Emitted when the element has received input.
- `gcdsSuggestionSelected` / `onGcdsSuggestionSelected` / `@gcdsSuggestionSelected` / `(gcdsSuggestionSelected)` - `CustomEvent<string>` Emitted when a suggestion is selected.
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<object>` Emitted when the input has a validation error.

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-input
- `getValidationMessage()` - Get validationMessage of gcds-input
- `validate()` - Call any active validators

## Use case

Take a look at what inputs do to see if they fit the problem you’re solving for. Then select the best input type for the use case you need to meet.

### Problems inputs solve

Use an input to gather single-line information from a person when you’re expecting a person will write:

- An individual or unique response.

- A short freeform response.

- A response that needs constraints around text length or the format of the response.

- Essential identifying or contact information.

- A date they already know, like a date of birth (for selection of an unknown date, use a date picker).

Note: Only collect information you’re authorized to handle and be sure to safeguard it to Government of Canada standards.

### Related components

Textareas for multi-line freeform responses.

Date selectors when you want someone to choose a not-yet-known date, like for appointment scheduling.

### Choose an input type

Set the input type based on how the input will validate the values a person enters.
The input `type` attribute accepts the following options:

#### Text input

Use when no other specfic input type is available for single-line entry.

- To collect a person’s name, in most cases by full name.

- Instead of the number input, for things like:

- Estimates that can have non-numerical values.

- Values that are not true numbers, like account numbers, credit card numbers, or authentication codes.

- Numbers with decimals.

#### Email input

- For email address collection.

- To require an email address format.

#### Tel input

- To collect phone numbers.

- To enable the phone keyboard on mobile devices.

#### Password input

- For password collection and validation.

- To set specific password constraints.

Tip: Communicate any password constraints in the hint text for the input so the person choosing the password knows exactly what the rules are.

#### Number input

- To collect whole numbers that can increase or decrease, like quantities.

- To reject values that are non numerical.

Tip: When you set a constraint, like a numerical range, use hint text to relay a precise instruction.

#### Search input

To collect search queries.

#### URL input

To collect a URL, like a web address or domain name.

## Design guidance

### Input anatomy

- The **input label** states the information a person should enter in the field. Label text is left aligned and in sentence case (only the initial letter is capitalized).
- The **hint text** communicates specific requirements for each input to support task success.
- The **field** is the container a person interacts with to provide their response.

### Design and accessibility for inputs

#### Write a short, specific, unique input label

- Ask for the information you need using as few words as possible. Phrasing as a question can make a label unnecessarily long and a person may not read the whole thing.

- Choose unique labels for each input and textarea within a page, like “Your full name”, “Manager’s name”.

- Avoid using technical jargon, like “subject email address” or “account ID”. Instead be specific and personal, like “full name”, “child’s age”.

- Avoid using colloquialisms and common verb phrases that are unfamiliar to non-fluent speakers.

- Explain any term that may not be clear. For example, “Handle (name you’d like to go by)”.

#### Hide the label only when the purpose is clear

- Visually hide the label only when the purpose of the input is clearly communicated by surrounding context, making the label visually redundant, such as in:

- chat or messaging interfaces

- search components

- tables or other space-constrained layouts

- file name inputs in web apps

- settings and configurations

- Always provide a label to support assistive technologies.

- Omit hint text if you hide the label, as it can lack context and be distracting or confusing.

#### Limit the use of placeholder text

- In forms, avoid using placeholder text to replace a label or to convey instructions or information a person needs to remember, as it can create accessibility issues.

- Outside of forms, use placeholder text only to reinforce a purpose that is already clear from visible context (for example, in search or conversational interfaces).

#### Size inputs to fit response type

- Use fixed width inputs for content you know has a specific length. For example, 6 characters for a postal code or 10 characters for a phone number.

- Opt to use most of the container width when you do not know the character count of the response and use all 75 characters for responses without a fixed length.

- Avoid using inputs for responses that may need more than 75 characters.

#### Support task success with hint text

- Add hint text to help a person filling in an input to provide a complete value.

- Write hint text to be instructional, brief and specific.

#### Write error messages for required inputs

- Use error messages to address missing and invalid entries.

- Write an error messages for all required inputs. If a field is optional, avoid using an error messages.

- Avoid blaming the person using the product. Instead, tell them exactly what to do to move forward.

## Code guidance and examples

### Coding and accessibility for inputs

#### Apply required attributes

Always use the following required attributes with `gcds-input`:

- `name`

- `label`

- `input-id`

#### Size the field to fit the response

- For responses with a specific length, set the `size` attribute.

- For example, 6 characters for a postal code or 10 characters for a phone number.

- Size “50” = 50 characters.

- For responses without a specific length, set the input to span around 90% of the container.

- Use the maximum 75 characters for responses without a fixed length.

- Use textarea for responses that need more than 75 characters.

#### Display numeric keyboard for mobile

- Use `inputmode=”numeric”` with `type=”text”` for non-incremental numbers, such as account numbers, credit card numbers, or authentication codes.

- This will open the numeric keyboard on mobile devices.

- This does not change how the value is validated.

#### Add hint text

- Use the `hint` attribute to add hint text.
- Avoid adding hint text in the field where it will disappear once the field is selected or a response starts to be entered.

#### Hide the label

- Set the `hide-label` attribute to true to visually hide the label while maintaining it for assistive technologies.
- Avoid adding hint text when the label is hidden.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for input.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `input-id`

The `input-id` attribute specifies the unique identifier for the input.

```html
<gcds-input
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `label`

The `label` attribute defines the label for the input.

```html
<gcds-input
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `name`

The `name` attribute identifies the input component within a form. It is used to reference the input’s value when a form is submitted.

```html
<gcds-input
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `autocomplete`

The `autocomplete` attribute controls whether the browser can suggest previously entered values for the input.

```html
<gcds-input
  autocomplete="given-name"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `autofocus`

The `autofocus` attribute controls whether the input will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the input is focused on render.

```html
<gcds-input
  autofocus="true"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `disabled`

The `disabled` attribute controls whether the input is disabled or not. By default, it is set to `false`.

```html
<gcds-input
  disabled="true"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `error-message`

The `error-message` attribute defines the error message to display for an invalid input.

```html
<gcds-input
  error-message="Error message"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `form`

The `form` attribute specifies the ID of the form that the input belongs to.

```html
<gcds-input
  form="form-id"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `hide-label`

The `hide-label` attribute controls whether the label is hidden or not.

```html
<gcds-input
  hide-label="true"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the label.

```html
<gcds-input
  hint="Hint / Example message."
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `inputmode`

The `inputmode` attribute sets the appearance of the virtual keyboard on mobile devices. The available options are:

- `decimal`

- `email`

- `none`

- `numeric`

- `search`

- `tel`

- `text`

- `url`

```html
<gcds-input
  inputmode="numeric"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `max`

The `max` attribute defines the maximum value that the input field can accept. It only applies to the `number` input type.

```html
<gcds-input
  max="100"
  type="number"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `maxlength`

The `maxlength` attribute defines the maximum number of characters that the input field can accept.

```html
<gcds-input
  maxlength="10"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `min`

The `min` attribute defines the minimum value that the input field can accept. It only applies to the `number` input type.

```html
<gcds-input
  min="22"
  type="number"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `minlength`

The `minlength` attribute defines the minimum number of characters that the input field can accept.

```html
<gcds-input
  minlength="5"
  type="number"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `pattern`

The `pattern` attribute specifies a regular expression that the input’s value must match.

```html
<gcds-input
  pattern="[A-Z]+"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `readonly`

The `readonly` attribute controls whether the input field can be modified or not. When set to `true`, the input field cannot be modified.

```html
<gcds-input
  readonly="true"
  value="Readonly"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `required`

The `required` attribute controls whether the input is required or not. When set to `true`, it adds “required” after the label, and activates the required validator.

```html
<gcds-input
  required="true"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `size`

The `size` attribute sets the size of the input field to provide a visual indication of the expected text length to the user.

```html
<gcds-input
  size="6"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `step`

The `step` attribute specifies the granularity that the value must adhere to. Use it with the `number` input type.

```html
<gcds-input
  step="10"
  type="number"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `suggestions`

The `suggestions` attribute defines an array of suggestion options.

```html
<gcds-input suggestions='[{ "label": "Suggestion A"}, { "label": "Suggestion B"}, { "label": "Suggestion C"}]' input-id="input-example" label="Label" name="input-example">
</gcds-input>
```

##### `type`

The `type` attribute defines the input type. The available options are:

- `email`

- `number`

- `password`

- `search`

- `tel`

- `text`

- `url`

```html
<gcds-input
  type="email"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the input.

```html
<gcds-input
  validate-on="other"
  required="true"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```

##### `value`

The `value` attribute sets the initial value displayed in the input. It reflects the current value as the user edits it.

```html
<gcds-input
  value="Text"
  input-id="input-example"
  label="Label"
  name="input-example"
></gcds-input>
```
