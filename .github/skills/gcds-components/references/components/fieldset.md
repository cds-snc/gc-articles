# Fieldset

Tags: `<gcds-fieldset>`  
Also called: form group  
Docs: https://design-system.canada.ca/en/components/fieldset/

A fieldset is a group of multiple form components or elements.

> **Upstream example issues (found by `gcds_lint.py`; the API table is correct):**
> - <gcds-input> is missing required attribute(s): name
> - <gcds-select> is missing required attribute(s): name

## API (from custom-elements.json, v1.7.1)

### `<gcds-fieldset>`

A fieldset is a group of multiple form components or elements.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `legend` | `string` |  | **yes** | The title for the contents of the fieldset |
| `legend-size` | `"h2" \| "h3" \| "h4" \| "h5" \| "h6"` |  | **yes** | Sets the appropriate font size for the fieldset legend. |
| `hint` | `string` |  |  | Hint displayed below the legend. |

**Slots:**

- `default` - Slot for the form elements.

## Use case

Take a look at what problems fieldsets solve to see if they fit the problem you’re solving for.

### Problems fieldsets solve

Logically grouping form elements or components helps support understanding and usability. Use a fieldset to group form components when they relate to the same topic.

### Related components

Inputs or textareas when you are requesting a written response from a person.

## Design guidance

### Fieldset anatomy

- The **fieldset legend** acts as a heading to identify the grouped form components that follow. Legend text is left aligned and in sentence case (only the initial letter is capitalized).
- The **fieldset hint text** gives specific requirements for completing the grouped form components successfully.
- The **component grouping** is the group of related form components that the legend describes.

### Design and accessibility for fieldsets

A fieldset combines the `fieldset` and `legend` elements to group together related form fields or components. It helps users of screen readers understand that inputs are all related to that question.

#### Group information

- Group together form elements that are part of a task or are related in some way.

- Avoid using fieldset when you have a single form field that asks for a single piece of information.

#### Write fieldset content to support task success

- Write a short descriptive statement or a call to action for the legend caption to let a person know what information the fieldset group is asking.

- Identify the subtask in the legend to help people understand the relationship between the information you’re requesting and the task they’re completing.

## Code guidance and examples

### Coding and accessibility for fieldsets

#### Choose the heading level

- Use the `legend-size` attribute to visually place the legend among the headings on the page.

- Set the attribute value to `h2` | `h3` | `h4` | `h5` | `h6` to render the legend.

#### Add hint text

- Use the `hint` attribute to add hint text.
- Avoid adding hint text in the field where it will disappear once the field is selected or a response starts to be entered.

#### Add form components

- Add form components to the fieldset grouping:

gcds-checkboxes

gcds-date-input

gcds-file-uploader

gcds-input

gcds-radios

gcds-select

gcds-textarea

- Follow the respective guidance for each component within fieldset.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `legend`

The `legend` attribute defines the legend for the fieldset.

```html
<gcds-fieldset legend="Legend" legend-size="h3">
  <gcds-input
    input-id="form-input"
    label="Input label"
  ></gcds-input>
  <gcds-select select-id="form-select" label="Select label">
    <option value="option-1">Option 1</option>
    <option value="option-2">Option 2</option>
    <option value="option-3">Option 3</option>
  </gcds-select>
</gcds-fieldset>
```

##### `legend-size`

The `legend-size` attribute sets the appropriate font size for the fieldset legend. The available options are:

- `h2`

- `h3`

- `h4`

- `h5`

- `h6`

```html
<gcds-fieldset legend="Legend" legend-size="h3">
  <gcds-input
    input-id="form-input"
    label="Input label"
  ></gcds-input>
  <gcds-select select-id="form-select" label="Select label">
    <option value="option-1">Option 1</option>
    <option value="option-2">Option 2</option>
    <option value="option-3">Option 3</option>
  </gcds-select>
</gcds-fieldset>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `hint`

The `hint` attribute defines the hint text displayed under the legend.

```html
<gcds-fieldset legend="Legend" legend-size="h3" hint="Hint / Example message.">
  <gcds-input
    input-id="form-input"
    label="Input label"
  ></gcds-input>
  <gcds-select select-id="form-select" label="Select label">
    <option value="option-1">Option 1</option>
    <option value="option-2">Option 2</option>
    <option value="option-3">Option 3</option>
  </gcds-select>
</gcds-fieldset>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing form elements inside the fieldset.

```html
<gcds-fieldset legend="Legend" legend-size="h3" hint="Hint / Example message.">
  <gcds-input
    input-id="form-input"
    label="Input label"
  ></gcds-input>
  <gcds-select select-id="form-select" label="Select label">
    <option value="option-1">Option 1</option>
    <option value="option-2">Option 2</option>
    <option value="option-3">Option 3</option>
  </gcds-select>
</gcds-fieldset>
```
