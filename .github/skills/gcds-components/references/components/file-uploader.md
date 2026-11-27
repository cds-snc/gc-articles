# File uploader

Tags: `<gcds-file-uploader>`  
Also called: file upload, file input, dropzone  
Docs: https://design-system.canada.ca/en/components/file-uploader/

A file uploader is a space to select and add supporting documentation.

## API (from custom-elements.json, v1.7.1)

### `<gcds-file-uploader>`

A file uploader is a space to select and add supporting documentation.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `label` | `string` |  | **yes** | Form field label. |
| `name` | `string` |  | **yes** | Name attribute for file input element. |
| `uploader-id` | `string` |  | **yes** | Id attribute for a file uploader element. |
| `accept` | `string` |  |  | Defines the file types the file uploader accepts. |
| `autofocus` | `boolean` |  |  | If true, the file uploader will be focused on component render |
| `disabled` | `boolean` | `false` |  | Specifies if a file uploader element is disabled or not. |
| `error-message` | `string` |  |  | Error message for an invalid file uploader element. |
| `form` | `string` |  |  | The ID of the form that the file uploader field belongs to. |
| `hide-label` | `boolean` | `false` |  | Specifies if the label is hidden or not. |
| `hint` | `string` |  |  | Hint displayed below the label. |
| `multiple` | `boolean` |  |  | Boolean that specifies if the user is allowed to select more than one file. |
| `required` | `boolean` | `false` |  | Specifies if a form field is required or not. |
| `validate-on` | `"blur" \| "other" \| "submit"` | `'blur'` |  | Set event to call validator |

**JS-only properties** (set via JavaScript, not HTML attributes):

- `files`: `FileList` - FileList of uploaded files to input
- `validator`: `(string \| ValidatorEntry \| Validator<string \| number \| FileList>)[]` - Array of validators
- `validity`: `ValidityState` - Read-only property of the file uploader, returns a ValidityState object that represents the validity states this element is in.
- `value`: `string[]` - Value for a file uploader element.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the uploader loses focus.
- `gcdsChange` / `onGcdsChange` / `@gcdsChange` / `(gcdsChange)` - `CustomEvent<string[]>` Emitted when the user has made a file selection. Contains the new value in the event detail.
- `gcdsError` / `onGcdsError` / `@gcdsError` / `(gcdsError)` - `CustomEvent<object>` Emitted when the uploader has a validation error.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the uploader has focus.
- `gcdsInput` / `onGcdsInput` / `@gcdsInput` / `(gcdsInput)` - `CustomEvent<string[]>` Emitted when the user has uploaded a file. Contains the new value in the event detail.
- `gcdsRemoveFile` / `onGcdsRemoveFile` / `@gcdsRemoveFile` / `(gcdsRemoveFile)` - `CustomEvent<any>` Remove file and update value.
- `gcdsValid` / `onGcdsValid` / `@gcdsValid` / `(gcdsValid)` - `CustomEvent<object>` Emitted when the uploader has a validation error.

**Methods** (async, call on the element):

- `checkValidity()` - Check the validity of gcds-file-uploader
- `getValidationMessage()` - Get validationMessage of gcds-file-uploader
- `validate()` - Call any active validators

## Use case

### Problems file uploader solves

Use the file uploader to:

- Allow a person to upload one or more files from a computer or device.

- Review selected files to be uploaded.

### Related components

Textareas for multi-line freeform responses.

Buttons when you want to take a person to another page to access additional content or move to the next step in a task.

## Design guidance

### File uploader anatomy

- The **label** provides the instruction for the component.
- The **hint text** presents the types of files and other requirements, like maximum file size.
- The **button** initiates the interface for file selection.
- The **file list item** is a container showing an uploaded file.
- The **file name** shows the name of the file that was added.
- The **remove** button allows an uploaded file to be removed prior to submission.

### Design and accessibility for file uploader

#### Write clear labels and hints

- Use the input label to set clear expectations about the kind of file a person can or needs to upload.

- Use the hint to specify any upload limitations.

#### Hide the label only when the purpose is clear

- Visually hide the label only when the purpose of the file uploader is clearly communicated by surrounding context, making the label visually redundant, such as in:

- tables or other space-constrained layouts

- settings and configurations

- Always provide a label to support assistive technologies.

- Omit hint text if you hide the label, as it can lack context and be distracting or confusing.

## Code guidance and examples

### Coding and accessibility for file uploader

#### Apply required attributes

Always use the following required attributes with `gcds-file-uploader`:

- `name`

- `label`

- `uploader-id`

#### Allow multiple files

Use the `multiple` attribute to allow the selection of more than one file.

#### Define file types

Use the `accept` attribute to define the file types that the file uploader can accept.

#### Add hint text

- Use the `hint` attribute to add hint text.
- Avoid adding hint text in the field where it will disappear once the field is selected or a response starts to be entered.

#### Hide the label

- Set the `hide-label` attribute to true to visually hide the label while maintaining it for assistive technologies.
- Avoid adding hint text when the label is hidden.

#### Add validation and error messages

- Use the `required` attribute to activate the built-in error messages and validation for file uploader.
- Fields with specific input requirements will validate if the entry is invalid, even if it is not required.
- For optional fields that need validation, like email address formats, add custom validation with a custom error message.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `label`

The `label` attribute defines the label for the file uploader.

```html
<gcds-file-uploader
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `name`

The `name` attribute identifies the file uploader component within a form. It is used to reference the uploaded files when a form is submitted.

```html
<gcds-file-uploader
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `uploader-id`

The `uploader-id` attribute specifies the unique identifier for the file uploader.

```html
<gcds-file-uploader
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `accept`

The `accept` attribute defines the file types the file uploader accepts.

```html
<gcds-file-uploader
  accept="image/*"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `autofocus`

The `autofocus` attribute controls whether the file uploader will be focused automatically when the page loads. By default, it is set to `false`. When set to `true`, the file uploader is focused on render.

```html
<gcds-file-uploader
  autofocus="true"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `disabled`

The `disabled` attribute controls whether the file uploader is disabled or not. By default, it is set to `false`.

```html
<gcds-file-uploader
  disabled="true"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `error-message`

The `error-message` attribute defines the error message to display for an invalid file input.

```html
<gcds-file-uploader
  error-message="Error message"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `form`

The `form` attribute specifies the ID of the form that the file uploader belongs to.

```html
<gcds-file-uploader
  form="form-id"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `hide-label`

The `hide-label` attribute controls whether the label is hidden or not.

```html
<gcds-file-uploader
  hide-label="true"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `hint`

The `hint` attribute defines the hint text displayed under the label.

```html
<gcds-file-uploader
  hint="Hint / Example message."
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `multiple`

The `multiple` attribute controls whether the user is allowed to select more than one file.

```html
<gcds-file-uploader
  multiple="true"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `required`

The `required` attribute controls whether the file input is required or not. When set to `true`, it adds “required” after the label, and activates the required validator.

```html
<gcds-file-uploader
  required="true"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```

##### `validate-on`

The `validate-on` attribute defines the validation event for the file uploader.

```html
<gcds-file-uploader
  validate-on="other"
  required="true"
  uploader-id="file-uploader-preview"
  label="Label"
  name="file-uploader-preview"
></gcds-file-uploader>
```
