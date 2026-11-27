# Hint and label

Building blocks used internally by GCDS form components. Use them only when composing a custom form control that has no GCDS equivalent, so its label and hint match GCDS styling.

## API (from custom-elements.json, v1.7.1)

### `<gcds-hint>`

Hint provides additional information or context to help users understand the content or functionality of a related element.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `hint-id` | `string` |  |  | Id attribute for the hint. |

**Slots:**

- `default` - Slot for the hint content.

**CSS parts:** `::part(hint)`

### `<gcds-label>`

Label for form fields, providing accessibility and context for users.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `hide-label` | `boolean` |  |  | Specifies if the label is hidden or not. |
| `label` | `string` |  |  | Form field label |
| `label-for` | `string` |  |  | Defines the label's for attribute. |
| `required` | `boolean` |  |  | Specifies if a form field is required or not. |

**Slots:**

- `default` - Slot for the label content.
