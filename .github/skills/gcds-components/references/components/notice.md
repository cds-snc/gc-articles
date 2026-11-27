# Notice

Tags: `<gcds-notice>`  
Also called: contextual alert  
Docs: https://design-system.canada.ca/en/components/notice/

The notice is a short, prominent message that’s part of the page content.

## API (from custom-elements.json, v1.7.1)

### `<gcds-notice>`

The notice is a short, prominent message that’s part of the page content.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `notice-role` | `"danger" \| "info" \| "success" \| "warning"` |  | **yes** | The notice role property specifies the style of notice to be displayed. |
| `notice-title` | `string` |  | **yes** | Set the notice title. |
| `notice-title-tag` | `"h2" \| "h3" \| "h4" \| "h5"` |  | **yes** | The notice title tag property specifies the HTML heading element for the title. This property does not modify the font size. It is used to assign the heading level in order to maintain heading hierarchy and accessibility for assistive technologies. |

**Slots:**

- `default` - Slot for the main content of the notice.

## Use case

### Problems notice solves

Use notice to:

- Communicate updates, warnings, and confirmations about the tasks on a page or about an activity or event that could affect people using the service.

- Make key messages stand out within page content, through sparing use.

- Provide context or additional information on the page content for better understanding.

### Related components

Error message or error summary for errors in a form field, on a page, or in a flow.

Banner for a message that applies to part of or the entire site or product.

### Notice roles

Set the notice roles based on the message context and corresponding visual appearance.

#### Information

Use the information role to:

- Provide supplementary information to enhance the reader’s understanding of the surrounding content.

- To offer tips or additional context that’s helpful in completing the main tasks on the page.

- Communicate non-critical information that’s important for the job or task a person would want to do.

Thank you for your interest in Canada Summer Jobs. Applications are now closed.

#### Success

Use the success notice role to:

- Communicate the successful completion of a task.

- Reinforce positive outcomes or confirmations within the content.

Processing your request may take up to four (4) weeks. We’ll contact you if we need more information.

#### Warning

Use the warning notice role to:

- Caution people about issues or considerations when there’s no immediate need for action.

- Inform people about changes, alterations to services, or other impactful but non-critical updates.

- Draw attention to information about future changes or possible implications to health and safety, security, and laws.

New penalties for impaired driving and cannabis-related crimes may affect your immigration status in Canada. If you commit one of these crimes, you may not be able to come to or stay in Canada.

#### Danger

Use the danger notice role to:

- Highlight content that people must be aware of due to its severity and that could have major negative impacts if ignored.

- Emphasize an issue or news that has serious implications to health and safety, security, and laws. Include a link to the most current information.

- Flag when a person needs to take action right away and provide a means to do so, like a link.

Avoid travel to Costa Rica if you are pregnant or planning to conceive a child in the next 3 months.

## Design guidance

### Notice anatomy

- **Guide line** indicates visually the notice’s start and end.
- **Icon** indicates the notice role.
- **Title** specifies the notice topic.
- **Message** provides the notice details.

### Design and accessibility

#### Choose the appropriate notice role

Refer to the use case tab for guidelines on when to use each notice role.

#### Write concise and descriptive headings and messages

- Write a meaningful heading that’s short and simple to highlight the goal of the notice.

- In the heading, use words like “warning” and “success” that match the meaning conveyed by the icon and colour of the notice role you selected.

- Craft the message (below the heading) to be brief and impactful, ideally within three sentences.

- Save the details for the main content area of a page. The notice is meant to attract attention and create an information trail for anyone who needs to know more.

#### Use links to guide people to additional details if necessary

- Use links to connect to additional details for a person who wants to read more.

- Opt to create a dedicated page for more detailed content and use a short message in a notice to draw interested people to that page.

- Avoid long notices. They can be distracting and frustrating.

#### Use notices sparingly to avoid visual fatigue

- Limit the number of notices on a page. People become less receptive to notices and warning messages the more often they encounter them.

- Make sure to remove notices once they’ve served their purpose.

#### Place notices where you’d place a paragraph

- Add a notice where you’d add an important paragraph (or section) on the page.

- Consider placing the notice at the top of the page or content section to avoid it being overlooked.

- Avoid using a notice within a form component, like an input or a checkbox.

## Code guidance and examples

### Coding and accessibility for notice

#### Define the notice role

Use the `notice-role` attribute to categorize the notice as `info`, `warning`, `danger` or `success`. This choice determines the visual styling of the notice and communicates the urgency or importance of the message to people.

#### Specify the notice title and heading level

- Use the `notice-title` attribute to create a clear and informative heading for the notice. Ensure the title conveys the message’s purpose.

- Set the `notice-title-tag` to define the correct heading level for the notice title. While the font size remains the same regardless of the tag, setting the correct heading level maintains proper hierarchy and accessibility for assistive technologies.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `notice-role`

The `notice-role` attribute sets the style of notice to be displayed.

```html
<gcds-notice notice-role="success" notice-title="Success notice title" notice-title-tag="h2">
  This is a success message.
</gcds-notice>
```

##### `notice-title`

The `notice-title` attribute defines the title of the notice.

```html
<gcds-notice notice-role="success" notice-title="Success notice title" notice-title-tag="h2">
  This is a success message.
</gcds-notice>
```

##### `notice-title-tag`

The `notice-title-tag` attribute sets the HTML heading element for the title. This attribute does not modify the font size. It is used to assign the heading level in order to maintain heading hierarchy and accessibility for assistive technologies.

```html
<gcds-notice notice-role="success" notice-title="Success notice title" notice-title-tag="h2">
  This is a success message.
</gcds-notice>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for placing the message of the notice.

```html
<gcds-notice notice-role="success" notice-title="Success notice title" notice-title-tag="h2">
  This is a success message.
</gcds-notice>
```
