# Alert

Tags: `<gcds-alert>`  
Also called: message, inline message  
Docs: https://design-system.canada.ca/en/components/alert/

The alert is an interactive object that communicates important information to the user.

## API (from custom-elements.json, v1.7.1)

### `<gcds-alert>`

Alert displays an alert message with an optional heading, icon, and close button.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `heading` | `string` |  | **yes** | Defines the alert heading. |
| `alert-role` | `"danger" \| "info" \| "success" \| "warning"` | `'info'` |  | Defines alert role. |
| `container` | `"full" \| "lg" \| "md" \| "sm" \| "xl" \| "xs"` | `'full'` |  | Defines the max width of the alert content. |
| `hide-close-btn` | `boolean` | `false` |  | Defines if the alert's close button is displayed or not. |
| `hide-role-icon` | `boolean` | `false` |  | Defines if the alert's role icon is displayed or not. |
| `is-fixed` | `boolean` | `false` |  | Defines if the alert's position is fixed. |

**Slots:**

- `default` - Slot for the main content of the alert.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsDismiss` / `onGcdsDismiss` / `@gcdsDismiss` / `(gcdsDismiss)` - `CustomEvent<void>`

## Use case

Take a look at what alerts do to see if they fit the problem you’re solving for. Then select the best subtype, called the alert role, for the use case you need to meet.

### Problems alerts solve

Use an alert to:

- Communicate information that appears dynamically in response to an event, such as:

- a person’s action, like submitting an application or saving progress.

- a system event, like a report being ready to download or a password about to expire.

- Communicate important or time-sensitive information when users should be made aware of it promptly.

- Display messages without interrupting the current task.

Avoid using an alert for messages that are part of the page content and are already there when the page loads. Use a notice instead.

### Related components

Banner for site or product-level messages.

Error messages or error summary for errors in form fields.

Notice for prominent messages that are part of the page content.

Alert dialogue for messages that interrupt a user task.

### Alert roles

The alert role changes the styling of the alert to visually signal its purpose. Pick the role relevant to your use case.

#### Information

Use the information role to:

- Inform people about the current state of their task, access, or eligibility.

- Provide status updates about a task or background process.

- Communicate contextual information that helps people understand why an action is unavailable or no longer needed.

- Inform people about non-critical information that’s relevant to their current task or situation.

You can only have one application in progress at a time. Finish or withdraw your current application before starting another one.

#### Success

Use the success role to:

- Confirm that a person’s action was completed successfully.

- Notify people when a requested process or task has finished successfully.

You can always review your preferences in Settings.

#### Warning

Use the warning role to:

- Inform people about conditions that may affect their current task.

- Explain the potential consequences of continuing with an action.

Change your password to keep access to your account.

#### Danger

Use the danger role to:

- Communicate when an action could not be completed successfully.

- Explain a problem that’s preventing a person from completing their current task.

Check your information and try again.

## Design guidance

### Alert anatomy

- The **icon** (optional) indicates the alert’s role.
- The **title** briefly communicates the main message of the alert.
- The **message** provides details and any available next steps.
- The **dismiss button** (optional) allows people to clear the alert.

### Design and accessibility for alerts

#### Choose the appropriate alert

- Refer to the use case tab for guidelines on when to use each alert role.

- Use warning and danger alerts only when the message is important and time-sensitive. These use `role="alert"`. A screen reader will interrupt its speech to announce the alert immediately, which can be intrusive.

- Use info and success alerts when the message is helpful but not urgent. These use `role="status"`. A screen reader will announce the alert at the next available opportunity, without interrupting its current speech.

#### Place the alert where it’s most relevant

- When the alert appears after a person’s action, place it inline, close to where the action occurred. For example, place it just after a button that may reveal important new information.

- When the alert is prompted on page refresh or by a server event, and you do not know where the person is on the page, place the alert at the top of the page.

- Avoid using an alert within a form component, like an input or a checkbox.

#### Write concise and descriptive titles and messages

- Include the most important piece of information in the title, so that the purpose of the alert is obvious.

- Keep the message concise, using no more than two sentences.

- Write a message that provides complementary information to the title. Avoid repeating or rephrasing the title.

- Describe the impact on the user, and provide a clear next step, when one is available.

- Include a link or another resolution path when it helps people address the issue described in the alert.

#### Consider adding a dismiss button

- Add a dismiss button when people can continue their task without acting on the alert. This is often appropriate for info and success alerts.

- Omit the dismiss button when people must resolve an issue before they can continue. This is often appropriate for danger alerts.

- Consider omitting the dismiss button if you’re already using another button in the message, to maintain a clear layout.

#### Avoid unnecessary alerts

- Only use an alert when the information is important enough to warrant additional attention. Avoid repeating information that’s already on the page.

- Avoid displaying more than one alert at the same time. Combine them if they’re about the same topic, or pick the alert with the highest priority.

## Code guidance and examples

### Coding and accessibility for alerts

#### Define the alert role

Use the `alert-role` attribute to categorize the alert as info, warning, danger or success. This choice determines the visual styling of the alert and communicates the urgency or importance of the message to people.

#### Specify the alert title

Use the `heading` attribute to create a clear and informative title for the alert. Make sure the title conveys the message’s purpose.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

##### `heading`

Use `heading` to define the title of the alert.

```html
<gcds-alert
  heading="You already have an application in progress"
></gcds-alert>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `alert-role`

Use `alert-role` to set the style of the alert. Choose from:

- `info`

- `success`

- `warning`

- `danger`

```html
<gcds-alert
  heading="Your changes have been saved"
  alert-role="success"
></gcds-alert>
```

##### `container`

Use `container` to define the container width for the alert content.

```html
<gcds-alert
  heading="You already have an application in progress"
  container="sm"
></gcds-alert>
```

##### `hide-close-btn`

Set `hide-close-btn` to `true` to hide the dismiss button. By default, it’s set to `false`, which displays the dismiss button.

```html
<gcds-alert
  heading="You already have an application in progress"
  hide-close-btn="true"
></gcds-alert>
```

##### `hide-role-icon`

Set `hide-role-icon` to `true` to hide the alert’s role icon. By default, it’s set to `false`, which displays the icon.

```html
<gcds-alert
  heading="You already have an application in progress"
  hide-role-icon="true"
></gcds-alert>
```

##### `is-fixed`

Set `is-fixed` to `true` to fix the alert’s position at the top. By default, it’s set to `false`.

```html
<gcds-alert
  heading="You already have an application in progress"
  is-fixed="true"
></gcds-alert>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

#### `default`

Use the `default` slot to add any type of content to the alert’s message. You can add things like text, links, or buttons.

```html
<gcds-alert heading="You already have an application in progress">
  <p>Message.</p>
</gcds-alert>
```
