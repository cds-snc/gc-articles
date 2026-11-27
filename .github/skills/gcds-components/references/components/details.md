# Details

Tags: `<gcds-details>`  
Also called: accordion, collapse  
Docs: https://design-system.canada.ca/en/components/details/

Details is an interactive switch for a person to expand or collapse content.

## API (from custom-elements.json, v1.7.1)

### `<gcds-details>`

Details is an interactive switch for a person to expand or collapse content.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `details-title` | `string` |  | **yes** | The details title summarizes the panel content. |
| `open` | `boolean` | `false` |  | Defines if the details panel is open by default or not. |

**Slots:**

- `default` - Slot for the main content of the details panel.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsBlur` / `onGcdsBlur` / `@gcdsBlur` / `(gcdsBlur)` - `CustomEvent<void>` Emitted when the details loses focus.
- `gcdsClick` / `onGcdsClick` / `@gcdsClick` / `(gcdsClick)` - `CustomEvent<void>` Emitted when the details has been clicked.
- `gcdsFocus` / `onGcdsFocus` / `@gcdsFocus` / `(gcdsFocus)` - `CustomEvent<void>` Emitted when the details has focus.

**Methods** (async, call on the element):

- `toggle()` - Methods

## Use case

Take a look at what problems the details component solves to see if they fit the problem you’re solving for.

### Problems details solve

Use the details component to give a person a summary of more detailed content you’ve hidden that they can choose to reveal. Use this component to:

- Reduce page size and scrolling when the content is lengthy and not essential to read in full. For example in an address collection pattern, the shipping address may be hidden using a details when it’s the same as the mailing or billing address.

- Give a summary of content that is secondary to the main task and may be overwhelming to some readers.

- Make a page with many subheadings scanable. For example, in a series of questions and answers, you can list the questions as details titles and a person can expand any section to read the answer content.

- Give readers the option to control content for subjects that may cause distress to some readers. They can access certain content when they are ready and hide content once they’ve reviewed it.

### Related components

Tabs when you want to replace the entire content of a section or a page.

Links to provide navigation to a new page, website, or section on the current page or files for download.

Buttons when a person modifies data, changes a state, or initiates a specific action.

## Design guidance

### Details anatomy

This is an anatomy of the expanded details component. By default, the content is collapsed.

- The **details title** summarizes the details content. Label text is in sentence case (only the initial letter is capitalized).
- The **caret** and the details title toggle the content display. The caret indicates if the component is currently expanded or collapsed. When it’s expanded, revealing the details text, the caret points to details content.
- The **content** displays any text and images that the reader can choose to expand or collapse.

#### Expanded details

#### Collapsed details

### Design and accessibility for details

#### Work to reduce cognitive load – not add to it

- Avoid concealing information with a list of details titles. This interrupts the flow of reading or scrolling for those using assisted technology.

- Consider making the first details components expanded by default, to help the reader understand how they work and choose what to conceal.

#### Select the type of content to include in a details component

First, remove any content that’s not useful or important to the main task. Then you can:

- Hide content the reader can choose to access for a task that involves some analysis to make a choice.

- Segment content in a list so a person can select to read the part or parts most relevant to them.

- Include supporting content that is important but secondary to the main task.

- Use the details component to conceal content that could cause distress. The reader can choose to skip or return to the text at the moment that’s right for them.

- For sensitive content, use the title to warn users before they access that content.

#### Use the summary to make content findable

- Keep the title clear and brief. Short titles are easier to scan and can help a person using assistive technology to navigate a set of details components.

- Write a title that states what the content’s about. Avoid non-descriptive titles such as “Read more”. Instead, opt for a specific and descriptive summary, like “Maximum coverage amount for your physio benefit”.

- Avoid similar or identical titles. Distinct titles help people know the difference and choose the details they want to read.

- Avoid increasing cognitive load with content that’s difficult to discover or find.

#### Avoid nesting details

- Avoid placing one details component inside another, where no one would know to look for that content.

- Similarly, break up lengthy content over more than one details component to avoid hiding multiple paragraphs in the same details content section.

#### Alternatives to using the details component

The details component can increase cognitive load. Some people may be confused by how they work or overwhelmed by a list of titles to select.

- Include in the main page flow, all content essential to accomplishing a task.

- Reduce overall text by removing redundant information or anything not essential to the task.

- Simplify the content with plain, conversational language.

- Use subheadings to break up content.

- Split the content into smaller sections and group common elements together.

## Code guidance and examples

### Coding and accessibility for details

#### Present the details component to support understanding

The details component can add to a person’s cognitive load when it:

- Interrupts the flow of reading or scrolling.

- The content they need is difficult to find and search.

- They are unfamiliar with the expand and collapse function.

To help a reader’s experience accessing details content:

- Use the `open` attribute to define if the details content is open by default or not.

- Make details titles clearly state what the content’s about. Avoid non-descriptive titles such as “Read more”. Instead, opt for a specific and descriptive summary, like “Maximum coverage amount for your physio benefit”.

- Make titles distinct so people know the difference. Identical or similar titles can be confused.

- Avoid placing one details component inside another, where no one would know to look for that content.

- Make details content searchable.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Essential attributes

These attributes are needed for the component to function correctly.

##### `details-title`

The `details-title` attribute defines the label that summarizes the details content.

```html
<gcds-details details-title="Learn more about this topic">
<gcds-text margin-bottom="0">Additional information.</gcds-text>
</gcds-details>
```

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `open`

The `open` attribute controls whether the details component is open by default or not.

```html
<gcds-details details-title="Learn more about this topic" open>
<gcds-text margin-bottom="0">Additional information.</gcds-text>
</gcds-details>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `default`

The `default` slot is for adding content inside the details component.

```html
<gcds-details details-title="Learn more about this topic">
<gcds-text margin-bottom="0">Additional information.</gcds-text>
</gcds-details>
```
