# Error message

Tags: `<gcds-error-message>`  
Also called: error, inline error  
Docs: https://design-system.canada.ca/en/components/error-message/

An error message is a description of a problem blocking a user goal.

## API (from custom-elements.json, v1.7.1)

### `<gcds-error-message>`

An error message is a description of a problem blocking a user goal.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `message-id` | `string` |  | **yes** | Id attribute for the error message. |

**Slots:**

- `default` - Slot for the error message content.

## Use case

Take a look at what problems error messages solve to see if they fit the problem you’re solving for.

### Problems error messages solve

All GC Design System components come with default error handling for required form components.

Use an error message for your own component instances when you need to interrupt a flow because of a problem where:

- A person needs to fix something before they can either continue an action or move on to the next task.

- A form component is required.

- You need to specify an incorrect response type or format and let a person know exactly what to do to fix the problem and move on.

### Related components

Error summary for listing all problems to address for response submission.

Alert for communicating an important or time-sensitive change.

## Design guidance

### Error message anatomy

- The **error message** cites the problem in context and includes a call to action to address it.

#### Error message applied to a form component

All GC Design System components come with default error handling for required form components.

For your own component instances, add the error message component, and include the following:

- The **required marker** shows a response area, a field or selection, needs to be completed. An incomplete response or omission will prompt an error message.
- The **hint text** gives brief, specific instructions about the constraints of the response. It can give an example of an accepted response.
- The **error message** cites the problem in context and includes a call to action to address it.
- The **response area** takes on the appearance of the response format and error message state of the component it’s attached to. For an input or textarea, the box will be highlighted. For radios or checkboxes, the options will be highlighted.

### Design and accessibility for error messages

When you interrupt the flow of a person’s action in your product, make the error and recovery obvious to get them back on task and they can regain momentum.

#### Identify and define the error

- Prepare error messages for each response constraint a component has. List out what could go wrong, and then design a short, specific statement describing each need.

- Only provide one reason for the error. Tell them the criterion the response does not meet.

- Avoid mentioning the user action that led to the problem. For example, “You missed answering a question” places the blame on the user, but “This question needs a response” identifies the problem blamelessly and neutrally.

#### Get the person back on track with a call to action

- Reduce additional friction by keeping the call to action specific and clear.

- Provide one or two solutions for fixing the error, not a multi-step process.

- Connect the dots for the person using your product. Avoid making them have to puzzle out what to do to correct an error.

- State exactly what’s needed to correct the issue and avoid the frustration of a repeat error.

#### Write a message that’s scanable

- Get rid of unnecessary details and include only the information a person needs to act on the problem.

- Be concise. If it takes more than a line or two, the ask may be too complex.

- Use sentence case and avoid caps and exclamation marks.

- Follow a predictable format: what happened, why it happened, and what to do about it.

Tip: Think about whether the message could be interpreted another way.

#### Use a human tone

- Talk the way the people using your product talk. Learn about them and their language by reading their requests or issues and by testing your messages with them in user research.

- Use plain, conversational language, like the tone of voice you’d use if you were describing the error to the person directly.

- Use and avoid jargon or coding terminology, like invalid code, unrecognized field, mandatory—and device.

- Avoid using the word error or any negative words.

Tip: To adopt a more human tone, think about explaining the error aloud to someone. How does it sound when you speak it in conversation?

## Code guidance and examples

### Coding and accessibility for error messages

#### Use error messages to support accessible task success

- Make sure a person can immediately access the form controls associated with the label the error message is attached to.

- Make your error message specific to avoid a user getting the same error message again after they thought they fixed the error.

- Avoid introducing additional steps. Let a person know exactly what to do to fix the error so they can take action and resubmit or revalidate without having to do extra work.

- Provide one or two solutions for fixing the error, not a multi-step process.

Note: All GC Design System components already come with default error handling for required form components.

#### Follow a predictable format for error messages

Provide simple, fast guidance that’s in context and interrupts the flow of action as minimally as possible:

- Clearly identifies and points to the error.

- Defines the error without making the user’s actions the source of the problem.

- Includes a call to action that’s specific and actionable.

A person who receives an error message needs to:

- Find the error and be able to access it.

- Know what to do to fix it.

- Be able to make the changes.

- Resubmit or revalidate without having to fill out the whole form again.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `message-id`

The `message-id` attribute specifies the unique identifier for the error message.

```html
<gcds-error-message message-id="message-default">
  Error message.
</gcds-error-message>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding the error message text.

```html
<gcds-error-message message-id="message-default">
  Error message.
</gcds-error-message>
```
