# Table

Tags: `<gcds-table>`  
Also called: data table, data grid  
Docs: https://design-system.canada.ca/en/components/table/

A table is a structured layout of related data in rows and columns.

> **Upstream example issues (found by `gcds_lint.py`; the API table is correct):**
> - <gcds-table columns> is not valid JSON: Expecting value

## API (from custom-elements.json, v1.7.1)

### `<gcds-table>`

A table is a structured layout of related data in rows and columns.

| Attribute | Type | Default | Required | Description |
|---|---|---|---|---|
| `columns` | `TableColumn[] \| string` | `[]` |  | Column definitions |
| `data` | `object[] \| string` | `[]` |  | Row data |
| `filter` | `boolean` | `false` |  | Enable global filter |
| `filter-value` | `string` | `''` |  | Current filter string |
| `pagination` | `boolean` | `false` |  | Enable pagination |
| `pagination-current-page` | `number` | `1` |  | Current page index |
| `pagination-size` | `number` | `10` |  | Number of rows per page |
| `pagination-size-options` | `number[] \| string` | `[10, 25, 50, 0]` |  | Available page-size options. Use 0 to represent "All rows". |
| `sort` | `boolean` | `false` |  | Enable global column sorting (can be overridden per column) |

**Slots:**

- `caption` - Slot to give an accessible name to the table, so that assistive technologies can identify it and announce it.
- `cell:<field>` - Slot to assign HTML content to a table cell, where <field> corresponds to the `field` property of a column definition.

**Events** (DOM `addEventListener` name / React prop / Vue / Angular):

- `gcdsTableStateChange` / `onGcdsTableStateChange` / `@gcdsTableStateChange` / `(gcdsTableStateChange)` - `CustomEvent<GcdsTableStateChange>`

## Use case

Take a look at what tables do to see if they fit the problem you’re solving for.

### Problems tables solve

Tables organize items with shared attributes into a structured format. They help turn raw data into clear information.

Use a table to:

- Help people scan and compare information across multiple records, like reviewing a metrics report or financial summary. Add sorting to let people reorganize data and surface patterns.

- Help people find specific information within a larger dataset, like looking up a tax rate or office location. Add filtering or sorting to let people narrow down results.

**Note**: On mobile, the table’s responsive layout removes columns. This makes the table better suited for finding specific information than for side-by-side comparison. If comparing across records is the primary task for mobile users, consider paring down the dataset.

### Related components

Card for grouping small pieces of related information as a single unit.

Container for basic layouts with a set width.

Grid for positioning unstructured elements on a page using a responsive, flexible column layout.

List for presenting items that do not share multiple common attributes or are not meant for comparison.

Charts for visualizing data to reveal trends, patterns, or differences at a glance.

## Design guidance

### Table anatomy

- The **caption** labels the table’s purpose and gives context for the data presented.
- The **filter and sort button** (optional) opens an overlay that allows people to reorder and filter data.
- The **row control** (optional) lets people set the number of rows displayed per page.
- The **display status** shows the range of visible rows and the total number of rows.
- The **column header** is a title that describes the column’s data. It also acts as an interactive triggre to sort the data.
- The **row** is a horizontal group of cells representing a single item or record.
- **Pagination** (optional) lets people move between pages of a large dataset.

### Design and accessibility for tables

#### Structure tables for clarity and simplicity

- Include at least one column header or row header so relationships between values are explicit.

- Order rows and columns in a way that fits how the information should be understood (for example, by chronology, by importance or by association).

- Avoid complex or nested tables whenever possible, as they are challenging for people using assistive technologies.

- Avoid merging or splitting cells.
- Avoid using more than one level of column or row header.
- Split complex tables into multiple simpler tables to make them easier to read.

- Add a caption to provide an accessible name to the table. Write unique captions for each table on the page so people using assistive technologies can distinguish them.

#### Format content to support scanning

- Use descriptive, concise table headers in a consistent format, such as noun phrases.

- Use consistent alignment so values can be compared quickly.

- Left-align text and non-quantitative numbers like dates, postal codes and phone numbers.
- Right-align numbers like quantities and measurements so their decimal points line up. Use the same number of decimals for all numbers.
- Align column headers with their column content.

- Place units in column headers instead of repeating them in each cell.

- Avoid long text in cells to help scanning across rows and columns.

- Avoid leaving cells blank as some assistive technologies may not communicate that a value is missing. Add context-specific text such as “Not applicable” or “No value”.

#### Support finding and exploring data

- Use filtering to help people narrow down results to a relevant subset when:

- The dataset is large enough that scanning all rows is difficult.
- People are likely to have a specific value or target in mind.

- Use sorting to help people reorganize data and find what matters most to them when:

- The order of rows affects how people interpret the data, such as by priority, recency, or magnitude.
- People may want to look for patterns across the dataset.

- Use pagination to help people navigate large datasets when:

- The number of rows would make the page too long to scroll comfortably.
- People are exploring items and do not need to see everything at once, or would benefit from faster-loading pages.

## Code guidance and examples

### Coding and accessibility for tables

#### Use the correct component name

- Use `gcds-table` in HTML, Vue, or React.

- Use `gcds-table-ng` in Angular.

#### Define your columns and data

To build a table, you need at minimum to define the columns and the data.

- Use the `columns` attribute to define the table’s columns and their behaviour.

- Use the `data` attribute to populate the table with content.

#### Provide an accessible caption

Use the `caption` slot to give an accessible name to the table, so that assistive technologies can identify it and announce it.

#### Add filtering for narrowing results

Set `filter` to `true` to allow filtering, so people can narrow down results by keyword.

#### Add sorting for reordering data

- Set `sort` to `true` to allow people to sort the table by column, so they can reorganize data by priority, recency, or magnitude, for example.

- If the table has a default sort order that reflects how people are most likely to first read the data, set it using the `sortDirection` property in the relevant column object.

#### Add pagination for managing large datasets

- Set `pagination` to true to split data into pages.

- Adjust `pagination-size-options` to control how many rows are shown per page.

### Examples

Explore the different ways you can configure the component. Each example shows a working implementation and ready-to-copy code.

#### Optional attributes

These attributes allow you to customize or extend the component’s behaviour and presentation.

##### `columns`

Use the `columns` attribute to define the columns of the table. Pass an array of objects, where each object defines a column and controls how it looks and behaves.

Each column object takes the following properties:

- `alignment`: Use `alignment` to control how the content inside the column cells is positioned horizontally. Set to `start` to align content to the left, `center` to align it to the middle, or `end` to align it to the right.

- `field`: Use `field` to define the unique id that connects the column to the correct data. The value must match the key used for that data in your data object.

- `header`: Use `header` to set the column heading that appears at the top of the column.

- `rowHeader`: Set `rowHeader` to `true` if you want to mark each cell in the column as a row header. Row headers label what each row is about.

- `slotted`: Set `slotted` to `true` to flag that the cell will render custom content. To see how each framework handles this, go to Framework-specific slots for custom content.

- `sort`: Set `sort` to `true` to allow people to sort the table by that column.

- `sortDirection`: Use `sortDirection` to set a default sort order for the column when the page loads. Set to `asc` for ascending order or `desc` for descending order.

##### `data`

Use `data` to pass the content of the table. Pass an array of objects where each object represents one row, and each key matches a column `field`.

```html
<gcds-table
  columns='[{"field":"submission_id","header":"ID","rowHeader":true},{"field":"submitter_name","header":"Name"},{"field":"date_submitted","header":"Date Submitted"},{"field":"assigned_reviewer","header":"Reviewer"}]'
  data='[{"submission_id":"EXP-2026-056","submitter_name":"Amy Roberts","date_submitted":"2026-01-28 6:00","status":"Pending Review","assigned_reviewer":"Michael Tran"},{"submission_id":"EXP-2026-057","submitter_name":"Leila Bouchard","date_submitted":"2026-01-28 8:30","status":"Rejected","assigned_reviewer":"Sarah Chen"},{"submission_id":"EXP-2026-058","submitter_name":"Nick Harris","date_submitted":"2026-01-28 10:20","status":"Approved","assigned_reviewer":"David Patel"},{"submission_id":"EXP-2026-059","submitter_name":"Marta Kowalska","date_submitted":"2026-01-29 3:40","status":"Under Review","assigned_reviewer":"Lisa Wong"},{"submission_id":"EXP-2026-060","submitter_name":"George Clark","date_submitted":"2026-01-29 5:30","status":"Pending Review","assigned_reviewer":"Michael Tran"}]'
></gcds-table>
```

##### `filter`

Set `filter` to `true` to display a filter input, so people can narrow down results by keyword.

```html
<gcds-table filter></gcds-table>
```

##### `filter-value`

Use `filter-value` to set a default keyword in the filter input, so some rows are already filtered when the table loads. By default, there’s no set value.

```html
<gcds-table filter filter-value="Dav"></gcds-table>
```

##### `pagination`

Set `pagination` to `true` to split the table into pages when there are many rows. By default, `pagination` is set to `false`.

```html
<gcds-table pagination></gcds-table>
```

##### `pagination-current-page`

Use `pagination-current-page` to set which page is displayed when the table first loads. By default, it’s set to `1`, which shows the first page.

```html
<gcds-table pagination pagination-current-page="2"
></gcds-table>
```

##### `pagination-size`

Use `pagination-size` to set how many rows are displayed per page. The default is `10`.

```html
<gcds-table pagination pagination-size="10"
></gcds-table>
```

##### `pagination-size-options`

Use `pagination-size-options` to define the page size choices available to people in the row control. The default options are `[10, 25, 50, 0]`.

**Note**: Including `0` in the options adds an “All” choice that displays all rows at once. Use with caution on large datasets, as it may affect performance.

```html
<gcds-table pagination pagination-size-options="[10, 15, 20, 25, 0]"></gcds-table>
```

##### `sort`

Set `sort` to `true` to allow users to sort the table by clicking on column headers. Set to `false` to disable sorting across all columns. By default, `sort` is set to `false`.

**Note**: The `sort` property in each column object has the final say. If a column sets `sort` to `false`, that column will not be sortable even if the table-level `sort` is set to `true`.

```html
<gcds-table sort></gcds-table>
```

#### Slots

The default slot allows you to inject custom content into the component’s primary content area, and named slots, into specific areas.

##### `caption`

Use the `caption` slot to give an accessible name to the table, so that assistive technologies can identify it and announce it.

```html
<gcds-table>
  <div slot="caption">
    <h5>Table caption</h5>
    <p>Detailed caption for the table</p>
  </div>
</gcds-table>
```

##### Framework-specific slots for custom content

Use custom cell content when you need something more than plain text in a cell, like a link, image, or button. To enable custom content for a column, set `slotted` to `true` in your column object.

Each framework handles custom cell content differently. HTML, Vue, and Angular use slots or directives, while React uses the `renderCell` property.

**HTML example**

Use the `cell:<field>` slot to define custom content for a specific column. Replace `<field>` with the `field` name you set in the column object.

Use `data-bind-*` to pass a row value directly into an attribute.

Use `data-bind-template-*` when you need to insert a row value inside a string before assigning it to an attribute. For example, use it to build an image’s `alt` and `src` attributes.

If interactive elements need to use the row data in their functions, you can use the properties of `row`, `rowIndex`, `column` and `value` through `this`. 
```html
<gcds-table
  columns='[
    ...,
    {
      field: "submission_id",
      header: "ID",
      slotted: true,
    },
    {
      field: "actions",
      header: "Actions",
      slotted: true,
    },
    ...,
  ];'
>
  <template slot="cell:submission_id">
    <a
      data-bind-template-href="/view_submission/{submission_id}"
      data-bind="submission_id"
    ></a>
  </template>
  <template slot="cell:actions">
    <gcds-button
      button-role="secondary"
      size="small"
      onclick="console.log(this.row, this.rowIndex, this.column, this.value)"
    >
      Console log row data
    </gcds-button>
  </template>
</gcds-table>
```

**Vue example**

Use the `#<field>` slot to define custom content for a specific column. Replace `<field>` with the `field` name you set in the column object.

Use `{ row }` to access row data inside the slot. 
```html
<GcdsTable
  :columns="[
    ...,
    {
      field: 'submission_id',
      header: 'ID',
      slotted: true,
    },
    {
      field: 'actions',
      header: 'Actions',
      slotted: true,
    },
  ]"
>
  <template #submission_id="{ row }">
    <a
      :href="`/view_submission/${row.submission_id}`"
    >
      {{ row.submission_id }}
    </a>
  </template>
  <template #actions="{ row, rowIndex, column, value }">
    <gcds-button
      button-role="secondary"
      size="small"
      @click="() => {
        console.log(row, rowIndex, column, value);
      }"
    >
      Console log row data
    </gcds-button>
  </template>
</GcdsTable>
```

**Angular example**

Use the `gcdsCell=”<field>”` directive to assign custom cell content to the correct column.

If interactive elements need to use the row data in their functions, you can use the properties of `row`, `rowIndex`, `column` and `value` through `let-*`. 
```html
<gcds-table-ng
  [columns]="[
    ...,
    {
      field: 'submission_id',
      header: 'ID',
      slotted: true,
    },
    {
      field: 'actions',
      header: 'Actions',
      slotted: true,
    },
  ]"
>
  <ng-template gcdsCell="submission_id" let-row>
    <a
      [href]="'/view_submission/' + row.submission_id"
    >
      {{ row.submission_id }}
    </a>
  </ng-template>
  <ng-template
    gcdsCell="actions"
    let-row
    let-rowIndex="rowIndex"
    let-column="column"
    let-value="value"
  >
    <gcds-button
      button-role="secondary"
      (click)="logRow(row, rowIndex, column, value)"
    >
      Console log row data
    </gcds-button>
  </ng-template>
</gcds-table-ng>
```

**React example**

Use the `renderCell` property to define the content to render in the cell. Unlike the other frameworks, React uses a `renderCell` property instead of slots. This property extends the column object with a function that returns what to display. 
```html
<GcdsTable
  columns={[
    ...,
    {
      field: 'submission_id',
      header: 'ID',
      slotted: true,
      renderCell: ({ row }) => {
        return (
          <a
            href={`/view_submission/${row.submission_id}`}
          >
            {row.submission_id}
          </a>
        );
      },
    },
    {
      field: 'actions',
      header: 'Actions',
      slotted: true,
      renderCell: ({ row, rowIndex, column, value }) => {
        return (
          <GcdsButton
            buttonRole="secondary"
            size="small"
            onClick={() => console.log(row, rowIndex, column, value)}
          >
            Console log row data
          </GcdsButton>
        )
      }
    },
  ]}
>
</GcdsTable>
```
