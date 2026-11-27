# CSS migration: utility classes and custom CSS to GCDS

Order of preference: **GCDS component attribute > CSS Shortcuts class > `var(--gcds-*)` token in custom CSS**. Full class list: `../../gcds-components/references/css-shortcuts.md`. Full token list: `../../gcds-components/references/design-tokens.md`.

`gcds.css` already loads the GC fonts (Lato for headings, Noto Sans for body, Noto Sans Mono) and the icon font. Remove your own `@font-face` or Google Fonts imports for these.

## Breakpoints

| GCDS prefix | Applies when | Bootstrap 4/5 | Bootstrap 3 / WET | Tailwind |
|---|---|---|---|---|
| (none) | all widths | (none) | `-xs-` | (none) |
| `xs:` | width > 30em (480px) | | | |
| `sm:` | width > 40em (640px) | `-sm-` (≥576, nearest) | `-sm-` (≥768 → use `md:`) | `sm:` |
| `md:` | width > 48em (768px) | `-md-` | `-md-` (≥992 → use `lg:`) | `md:` |
| `lg:` | width > 64em (1024px) | `-lg-` (≥992, nearest) | `-lg-` (≥1200 → use `xl:`) | `lg:` |
| `xl:` | width > 80em (1280px) | `-xl-` (≥1200, nearest) | | `xl:` |

GCDS components switch layout at **48em** (tablet) and **64em** (desktop), for example `gcds-grid columns-tablet` / `columns-desktop`. Use those widths in custom media queries: `@media (width >= 48em)`.

## Spacing scale

GCDS spacing classes take a token value: `m-`, `mt-`, `mb-`, `ms-` (start/left), `me-` (end/right), `mx-`, `my-`, `p-`, `pt-`, `pb-`, `ps-`, `pe-`, `px-`, `py-`, `gap-`, `gap-x-`, `gap-y-`. Values: `0 25 50 75 100 125 150 175 200 225 250 300 350 400 … 1250` (each 50 = 0.25rem). Margins also accept `auto`.

| rem | px | GCDS value | Bootstrap 4/5 | Tailwind | WET (approx.) |
|---|---|---|---|---|---|
| 0 | 0 | `0` | `0` | `0` | `-0` |
| 0.125 | 2 | `25` | | `0.5` | |
| 0.25 | 4 | `50` | `1` | `1` | `-sm` (5px) |
| 0.5 | 8 | `100` | `2` | `2` | |
| 0.75 | 12 | `150` | | `3` | |
| 1 | 16 | `200` | `3` | `4` | `-md` (15px) |
| 1.25 | 20 | `250` | | `5` | |
| 1.5 | 24 | `300` | `4` | `6` | |
| 2 | 32 | `400` | | `8` | `-lg` (30px) |
| 2.5 | 40 | `500` | | `10` | |
| 3 | 48 | `600` | `5` | `12` | `-xl` (50px) |
| 4 | 64 | `800` | | `16` | |
| 5 | 80 | `1000` | | `20` | |
| 6 | 96 | `1200` | | `24` | |

Formula: Tailwind `n` → GCDS `n × 50`. Name changes: Bootstrap 4 `ml-`/`mr-`/`pl-`/`pr-` → `ms-`/`me-`/`ps-`/`pe-`. Tailwind `pl-`/`pr-` → `ps-`/`pe-`. WET `mrgn-tp-md` → `mt-200`, `mrgn-bttm-lg` → `mb-400`, `mrgn-lft-*` → `ms-*`.

GCDS components already have their own vertical rhythm (`gcds-heading` and `gcds-text` have default margins, and you can change them with their `margin-top` and `margin-bottom` attributes). Remove spacing utilities that were only there to space headings and paragraphs, and use the attributes instead.

## Class mapping

| Purpose | Bootstrap | Tailwind | GCDS |
|---|---|---|---|
| Display | `d-none d-md-block`, `d-flex`, `d-inline-block` | `hidden md:block`, `flex`, `inline-block` | `d-none md:d-block`, `d-flex`, `d-inline-block` |
| Grid display | `d-grid` | `grid grid-cols-3` | `d-grid grid-cols-3` (or `gcds-grid`) |
| Flex direction / wrap | `flex-column`, `flex-row`, `flex-wrap` | `flex-col`, `flex-row`, `flex-wrap` | `flex-col`, `flex-row`, `flex-wrap` |
| Flex grow | `flex-grow-1`, `flex-fill` | `grow`, `flex-1` | `flex-grow`, `flex-1` |
| Justify | `justify-content-between` | `justify-between` | `justify-content-between` (`-start`, `-end`, `-center`, `-around`, `-evenly`) |
| Align | `align-items-center`, `align-self-start` | `items-center`, `self-start` | `align-items-center`, `align-self-start` |
| Gap | `gap-3` | `gap-4` | `gap-200` |
| Text align | `text-start`, `text-center`, `text-end` | `text-left`, `text-center`, `text-right` | `text-left`, `text-center`, `text-right` |
| Weight | `fw-bold`, `fw-semibold`, `fw-normal` | `font-bold`, `font-semibold`, `font-normal` | `font-bold`, `font-semibold`, `font-regular` |
| Italic | `fst-italic` | `italic` | `font-italic` |
| Size | `small`, `fs-6`, `.h3` on non-heading | `text-sm`, `text-xl` | `font-text-small`, `font-text`, `font-h3` (prefer `gcds-text size` / a real heading) |
| Text colour | `text-muted`, `text-secondary`, `text-white`, `text-body` | `text-gray-500`, `text-white` | `text-secondary`, `text-light`, `text-primary` |
| Transform | `text-uppercase` | `uppercase` | `text-uppercase` |
| Background | `bg-light`, `bg-dark`, `bg-white`, `bg-danger` | `bg-gray-100`, … | `bg-light`, `bg-dark`, `bg-white`, `bg-danger`, `bg-primary` (GC navy, **not** Bootstrap blue) |
| Border | `border`, `border-top`, `border-0` | `border`, `border-t`, `border-0` | `b-sm b-default`, `bt-sm b-default`, `b-0` |
| Radius | `rounded`, `rounded-pill`, `rounded-circle` | `rounded-md`, `rounded-full` | `b-radius-md`, `b-radius-lg`, `b-radius-xl` |
| Images | `img-fluid`, `w-100` on img | `max-w-full`, `w-full` | `img-responsive`, `img-full` |
| Max width / container | `container`, `container-md` | `max-w-screen-lg`, `container` | `gcds-container size="xl"` or `container-xl` / `container-lg` / `container-md` |
| Visually hidden | `visually-hidden`, `sr-only` | `sr-only` | `<gcds-sr-only>` (or `visibility-sr-only` class) |
| Position | `position-relative`, `sticky-top` | `relative`, `sticky top-0` | `position-relative`, `position-sticky` (+ `top` in CSS) |
| Overflow | `overflow-hidden`, `overflow-auto` | `overflow-hidden`, `overflow-auto` | `overflow-hidden`, `overflow-scroll`, `overflow-visible` (no `auto`; use CSS `overflow: auto`) |
| Lists | `list-unstyled` | `list-none` | `list-none` (`list-disc` / `list-decimal` for bullets with GCDS indentation) |
| Shadows | `shadow`, `shadow-sm` | `shadow-md` | none. Drop them; GCDS uses borders, not elevation |

Check any class not in this table against `css-shortcuts.md`. If there is no shortcut, write a small rule with tokens.

## Custom CSS → tokens

| Hard-coded value (typical) | Token |
|---|---|
| Body text `#333`, `#212529`, `black` | `var(--gcds-text-primary)` |
| Muted text `#6c757d`, `#666` | `var(--gcds-text-secondary)` |
| Canada.ca navy `#26374a`, brand primary | `var(--gcds-bg-primary)` |
| Link `#284162`, `#0d6efd`, `#0535d2` | `var(--gcds-link-default)`; hover `var(--gcds-link-hover)`; visited `var(--gcds-link-visited)` |
| Error red `#d3080c`, `#dc3545`, `#af3c43` | `var(--gcds-danger-text)` / `--gcds-danger-border` / `--gcds-danger-background` |
| Light grey panel `#f5f5f5`, `#f8f9fa`, `#eaebed` | `var(--gcds-bg-light)` |
| Border grey `#ccc`, `#dee2e6` | `var(--gcds-border-default)` |
| Focus outline | `outline: var(--gcds-outline-width) solid var(--gcds-focus-border)` |
| `font-family: "Lato"` / `"Noto Sans"` | `var(--gcds-font-families-heading)` / `var(--gcds-font-families-body)` |
| `font: 400 20px/1.6 …` body | `font: var(--gcds-font-text)` (`--gcds-font-text-mobile` below 48em) |
| Heading sizes | `font: var(--gcds-font-h2)` etc. (and `-mobile` variants) |
| `padding: 24px` | `padding: var(--gcds-spacing-300)` |
| `border-radius: 4px` | `var(--gcds-border-radius-md)` |
| Max content width `1140px`, `1170px` | `var(--gcds-container-size-xl)` (71.25rem) |

Success, warning and info colours have no semantic token. Use `gcds-notice` or `gcds-alert` for those messages, not coloured boxes. If a custom component really needs them, use base palette tokens (`--gcds-color-green-*`, `--gcds-color-yellow-*`, `--gcds-color-blue-*`) and check 4.5:1 contrast.

## Delete, don't port

- Styles for elements now rendered by GCDS components (header, footer, buttons, inputs, alerts, cards, breadcrumbs, pagination, tables, details). Shadow DOM ignores them anyway.
- Resets and normalize files when CSS Shortcuts is loaded (it includes GCDS reset styles).
- Bootstrap/WET JavaScript plugins whose job the components now do (collapse, dropdowns used for nav, form validation, tables).
- `!important` overrides that targeted the old framework.
