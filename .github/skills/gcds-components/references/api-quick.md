# API quick reference

Generated from custom-elements.json (v1.7.1). **Bold** attributes are required. Enum values are listed after the attribute name. Full details are in `components/<name>.md`.

## `gcds-alert`

- attrs: **heading** (string); alert-role (danger/info/success/warning); container (full/lg/md/sm/xl/xs); hide-close-btn (boolean); hide-role-icon (boolean); is-fixed (boolean)
- slots: default
- events: gcdsDismiss

## `gcds-breadcrumbs`

- attrs: hide-canada-link (boolean)
- slots: default

## `gcds-breadcrumbs-item`

- attrs: **href** (string)
- slots: default
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-button`

- attrs: button-id (string); button-role (danger/primary/secondary/start); disabled (boolean); download (string); href (string); name (string); rel (string); size (regular/small); target (string); type (button/link/reset/submit); value (string)
- slots: default
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-card`

- attrs: **card-title** (string); **href** (string); badge (string); card-title-tag (h3/h4/h5/h6); description (string); img-alt (string); img-src (string); rel (string); target (string)
- slots: default, title
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-checkboxes`

- attrs: **name** (string); **options** (CheckboxObject[] | string); autofocus (boolean); disabled (boolean); error-message (string); form (string); hide-label (boolean); hide-legend (boolean); hint (string); legend (string); required (boolean); validate-on (blur/other/submit); value (string | string[])
- JS props: validator, validity
- events: gcdsBlur, gcdsChange, gcdsClick, gcdsError, gcdsFocus, gcdsInput, gcdsValid

## `gcds-container`

- attrs: alignment (center/end/start); border (boolean); layout (full/page); margin (token 0…950); padding (token 0…950); size (full/lg/md/sm/xl/xs); tag (string)
- slots: default

## `gcds-date-input`

- attrs: **format** (compact/full/iso); **legend** (string); **name** (string); autocomplete (bday/cc-exp/off/on); autofocus (boolean); disabled (boolean); error-message (string); form (string); hint (string); max (string); min (string); required (boolean); validate-on (blur/other/submit); value (string)
- JS props: validator, validity
- events: gcdsBlur, gcdsChange, gcdsError, gcdsFocus, gcdsInput, gcdsValid

## `gcds-date-modified`

- attrs: type (date/version)
- slots: default

## `gcds-details`

- attrs: **details-title** (string); open (boolean)
- slots: default
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-error-message`

- attrs: **message-id** (string)
- slots: default

## `gcds-error-summary`

- attrs: error-links (object | string); heading (string); listen (boolean)
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-fieldset`

- attrs: **legend** (string); **legend-size** (h2/h3/h4/h5/h6); hint (string)
- slots: default

## `gcds-file-uploader`

- attrs: **label** (string); **name** (string); **uploader-id** (string); accept (string); autofocus (boolean); disabled (boolean); error-message (string); form (string); hide-label (boolean); hint (string); multiple (boolean); required (boolean); validate-on (blur/other/submit)
- JS props: files, validator, validity, value
- events: gcdsBlur, gcdsChange, gcdsError, gcdsFocus, gcdsInput, gcdsRemoveFile, gcdsValid

## `gcds-footer`

- attrs: contextual-heading (string); contextual-links (object | string); display (compact/full); sub-links (object | string)
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-grid`

- attrs: align-content (center/end/space-around/space-between/space-evenly/start/stretch); align-items (baseline/center/end/start/stretch); alignment (center/end/start); columns (string); columns-desktop (string); columns-tablet (string); container (full/lg/md/sm/xl/xs); display (grid/inline-grid); equal-row-height (boolean); gap (token 150…800); gap-desktop (token 150…800); gap-tablet (token 150…800); justify-content (center/end/space-around/space-between/space-evenly/start/stretch); justify-items (center/end/start/stretch); place-content (center/end/space-around/space-between/space-evenly/start/stretch); place-items (center/end/start/stretch); tag (article/aside/div/dl/main/nav/ol/section/ul)
- slots: default

## `gcds-grid-col`

- attrs: desktop (1/10/11/12/2/3/4/5/6/7/8/9); tablet (1/2/3/4/5/6); tag (string)
- slots: default

## `gcds-header`

- attrs: **lang-href** (string); **skip-to-href** (string); signature-has-link (boolean)
- slots: account, banner, breadcrumb, menu, search, signature, skip-to-nav, toggle
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-heading`

- attrs: **tag** (h1/h2/h3/h4/h5/h6); character-limit (boolean); heading-role (light/primary/secondary); margin-bottom (token 0…950); margin-top (token 0…950)
- slots: default

## `gcds-hint`

- attrs: hint-id (string)
- slots: default

## `gcds-icon`

- attrs: **name** (token arrow-down…warning-triangle); label (string); margin-left (token 0…950); margin-right (token 0…950); size (h1/h2/h3/h4/h5/h6/inherit/text/text-small)

## `gcds-input`

- attrs: **input-id** (string); **label** (string); **name** (string); autocomplete (string); autofocus (boolean); disabled (boolean); error-message (string); form (string); hide-label (boolean); hint (string); inputmode (decimal/email/none/numeric/search/tel/text/url); max (number | string); maxlength (number); min (number | string); minlength (number); pattern (string); readonly (boolean); required (boolean); size (number); step ("any" | number); suggestions (SuggestionOption[] | string); type (email/number/password/search/tel/text/url); validate-on (blur/other/submit); value (string)
- JS props: validator, validity
- events: gcdsBlur, gcdsChange, gcdsError, gcdsFocus, gcdsInput, gcdsSuggestionSelected, gcdsValid

## `gcds-label`

- attrs: hide-label (boolean); label (string); label-for (string); required (boolean)
- slots: default

## `gcds-lang-toggle`

- attrs: **href** (string)
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-link`

- attrs: **href** (string); display (block/inline); download (string); external (boolean); link-role (default/light); rel (string); size (inherit/regular/small); target (string); type (string)
- slots: default
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-nav-group`

- attrs: **menu-label** (string); **open-trigger** (string); close-trigger (string); open (boolean)
- slots: default
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-nav-link`

- attrs: **href** (string); current (boolean)
- slots: default
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-notice`

- attrs: **notice-role** (danger/info/success/warning); **notice-title** (string); **notice-title-tag** (h2/h3/h4/h5)
- slots: default

## `gcds-pagination`

- attrs: **label** (string); current-page (number); display (list/simple); next-href (string); next-label (string); previous-href (string); previous-label (string); total-pages (number); url (object | string)
- events: gcdsBlur, gcdsClick, gcdsFocus

## `gcds-radios`

- attrs: **legend** (string); **name** (string); **options** (RadioObject[] | string); autofocus (boolean); disabled (boolean); error-message (string); form (string); hide-legend (boolean); hint (string); required (boolean); validate-on (blur/other/submit); value (string)
- JS props: validator, validity
- events: gcdsBlur, gcdsChange, gcdsError, gcdsFocus, gcdsInput, gcdsValid

## `gcds-search`

- attrs: action (string); method (get/post); name (string); placeholder (string); search-id (string); suggested (string | string[]); value (string)
- events: gcdsBlur, gcdsChange, gcdsFocus, gcdsInput, gcdsSubmit

## `gcds-select`

- attrs: **label** (string); **name** (string); **select-id** (string); autocomplete (string); autofocus (boolean); default-value (string); disabled (boolean); error-message (string); form (string); hide-label (boolean); hint (string); required (boolean); validate-on (blur/other/submit); value (string)
- JS props: validator, validity
- slots: default
- events: gcdsBlur, gcdsChange, gcdsError, gcdsFocus, gcdsInput, gcdsValid

## `gcds-side-nav`

- attrs: **label** (string)
- slots: default, home

## `gcds-signature`

- attrs: has-link (boolean); type (signature/wordmark); variant (colour/white)

## `gcds-sr-only`

- attrs: tag (h1/h2/h3/h4/h5/h6/p/span)
- slots: default

## `gcds-stepper`

- attrs: **current-step** (number); **total-steps** (number); tag (h1/h2/h3)
- slots: default

## `gcds-table`

- attrs: columns (TableColumn[] | string); data (object[] | string); filter (boolean); filter-value (string); pagination (boolean); pagination-current-page (number); pagination-size (number); pagination-size-options (number[] | string); sort (boolean)
- slots: caption, cell:<field>
- events: gcdsTableStateChange

## `gcds-text`

- attrs: character-limit (boolean); display (block/flex/inline/inline-block/inline-flex/none); margin-bottom (token 0…950); margin-top (token 0…950); size (body/small); text-role (light/primary/secondary)
- slots: default

## `gcds-textarea`

- attrs: **label** (string); **name** (string); **textarea-id** (string); autofocus (boolean); cols (number); disabled (boolean); error-message (string); form (string); hide-label (boolean); hide-limit (boolean); hint (string); maxlength (number); minlength (number); required (boolean); rows (number); validate-on (blur/other/submit); value (string)
- JS props: validator, validity
- events: gcdsBlur, gcdsChange, gcdsError, gcdsFocus, gcdsInput, gcdsValid

## `gcds-top-nav`

- attrs: **label** (string); alignment (end/start)
- slots: default, home

## `gcds-topic-menu`

- attrs: home (boolean)
