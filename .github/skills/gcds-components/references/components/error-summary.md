# Error summary

Tags: `<gcds-error-summary>`  
Also called: error list  
Docs: https://design-system.canada.ca/en/components/error-summary/

An error summary is a list of user errors in a form.

## API (from custom-elements.json, v1.7.1)

### `<gcds-error-summary>`

An error summary is a list of user errors in a form.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `error-links` | `object \| string` |  |  | Object of list items for error list. Format: { link-href: link-label } |
| `heading` | `string` |  |  | Set error summary heading |
| `listen` | `boolean` | `true` |  | Specifies if the error summary should listen for GcdsError event to generate error list. |

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the link loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<string>` Emitted when the link has been clicked.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the link has focus.

## Use case

Take a look at what problems error summaries solve to see if they fit the problem you’re solving for.

GC Design System form components come with default error handling. When a person tries to submit a form, any errors to required components will prompt an error summary.

### Problems that error summaries solve

Use an error summary to interrupt a person who’s submitting a form when there is a problem or a series of problems to:

- Make errors obvious so a person knows about them and how to address them.

- List error messages when a person needs to add information or make a correction to two or more components before they can submit a form.

### Related components

Error message for describing a problem blocking a user action, related to a single component.

## Design guidance

### Error summary anatomy

- The **heading** communicates that there’s a problem. This is set by default and can be edited to be more specific.
- The **error summary** is a collection of error messages on the page, listed in order of their appearance on the form.
- The **error summary item** links to the error context and includes the same text as the error message (a call to action statement to address the error).

### Design and accessibility for error summaries

#### Create usable error summaries

Keep in mind that errors interrupt the flow of a person’s action in your product. This can be jarring and uncomfortable.

- Provide a clear path to fixing the error. Errors elicit an emotional response that is heightened when the problem is difficult or impossible to resolve.

- Make the error message and recovery obvious to get a person back on task and regain momentum.

- Keep the error summary list readable with short and specific error messages.

- Limit the number of form components on a page that require a response.

#### Use the error summary with your own component instances

- Add error messages for your own (non-GC Design System) component instances in your forms.

- Match the error summary item content to each of your error messages.

- Order errors in the error summary in the same order that they appear on the form.

## Code guidance and examples

### Coding and accessibility for error summaries

#### Use the error summary with GC Design System components

All GC Design System components come with default error handling for required form components. For error summaries this means any required component will prompt an inline error message `onBlur` by default. That error message will be listed in the error summary on form submit.

The GC Design System error summary uses the `listen` attribute to collect errors. The error summary will list errors from GC Design System components `onSubmit`. Errors are listed in the same order as they appear on the page.

- Enable any controls the person will need to access to recover from any of the errors.

- Consider replacing any generic default error message with text that defines the specific problem and how a person can fix it.

#### Opt to use the error summary with your own component instances

If you’re using some of your own form components:

- Add errors for those instances by passing the `error-links` attribute for any component or element you’re using that has an error message. Then the error messages will appear.

- Match the error summary item content to each of your error messages.

- Order errors in the error summary in the same order that they appear on the form.

#### Opt to change the error heading

Opt to make your error heading more specific by using the `heading` attributes.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `error-links`

The `error-links` attribute defines the list of errors to display in the error summary. Format: `{ link-href: link-label }`.

```html
<gcds-error-summary error-links='{ "#error-href-1": "Error summary item.", "#error-href-2": "Error summary item.", "#error-href-3": "Error summary item." }'>
</gcds-error-summary>
```

##### `heading`

The `heading` attribute defines the error summary heading.

```html
<gcds-error-summary heading="Heading" error-links='{ "#error-href-1": "Error summary item.", "#error-href-2": "Error summary item.", "#error-href-3": "Error summary item." }'>
</gcds-error-summary>
```

##### `listen`

The `listen` attribute controls whether the error summary should listen for the `gcdsError` event to generate a list of errors or not. By default, it is set to `true`.

```html
<gcds-error-summary error-links='{ "#error-href-1": "Error summary item.", "#error-href-2": "Error summary item.", "#error-href-3": "Error summary item." }' listen>
</gcds-error-summary>
```
