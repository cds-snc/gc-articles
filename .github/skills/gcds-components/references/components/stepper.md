# Stepper

Tags: `<gcds-stepper>`  
Also called: progress indicator, steps  
Docs: https://design-system.canada.ca/en/components/stepper/

A stepper is a progress tracker for a multi-step process.

## API (from custom-elements.json, v1.7.1)

### `<gcds-stepper>`

A stepper is a progress tracker for a multi-step process.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `current-step` | `number` |  | **yes** | Defines the current step. |
| `total-steps` | `number` |  | **yes** | Defines the total amount of steps. |
| `tag` | `"h1" \| "h2" \| "h3"` | `'h2'` |  | Defines the heading tag to render |

**Slots:**

- `default` - Slot for the heading content.

## Use case

Take a look at what problems steppers solve to see if they fit the problem you’re solving for.

### Problems steppers solve

Use a stepper for a logical sequence split over more than one page to:

- Show a person which step they’re on in a process, like in a multi-step form.

- Make the structure of a path clear by showing the total number of steps.

### Related components

Pagination when a user needs to navigate a range of pages that are not part of a multi-step process. It provides controls to select the next or previous page.

## Design guidance

### Stepper anatomy

- The **current step** indicates the step the person’s on in the process.
- The **total steps** indicates the number of steps overall in the process.
- The **step title** describes the type of information that will be asked in the step.

### Design and accessibility for steppers

#### Always include the step title after the stepper

The step title and the stepper are a heading on the page. This is a H1, H2, or H3, depending on the heading hierarchy of the page.

#### Assign a goal to each step in your sequence

- Choose a step title that reflects the goal of each step.

- Make sure the actions in each step support the stated goal.

## Code guidance and examples

### Coding and accessibility for steppers

#### Use each step for a goal

Use the `current-step` attribute to indicate the step that the person is on and the `total-steps` attribute to indicate the overall number of steps.

#### Placing the stepper on the page

Use the `tag` attribute to assign the appropriate heading tag to the stepper. The stepper is able to render as either a `h1`, `h2` or `h3`.

#### Use valid properties

Component properties have built-in validation. Required properties or properties prone to misuse will be validated. If there are any validation errors, the component will be non-functional and won’t render. Review the browser’s console for details and adjust the properties accordingly to ensure proper functionality.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `current-step`

The `current-step` attribute defines the current step.

```html
<gcds-stepper current-step="1" total-steps="4">
Section title
</gcds-stepper>
```

##### `total-steps`

The `total-steps` attribute defines the total number of steps.

```html
<gcds-stepper current-step="1" total-steps="4">
Section title
</gcds-stepper>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `tag`

The `tag` attribute sets the HTML heading element for the stepper. By default, it is set to `h2`.

```html
<gcds-stepper current-step="1" total-steps="4" tag="h3">
Section title
</gcds-stepper>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing the heading text.

```html
<gcds-stepper current-step="1" total-steps="4">
Section title
</gcds-stepper>
```
