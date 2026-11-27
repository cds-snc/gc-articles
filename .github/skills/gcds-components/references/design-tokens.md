# Design tokens reference

CSS custom properties shipped in `gcds.css` (components v1.7.1). They are available on any page that loads GCDS. Order of preference for custom styles: **GCDS component > CSS Shortcuts class > global token in `var()` > base colour token**. Never use component tokens (`--gcds-button-*`, `--gcds-alert-*`, ...) outside their component; they change without notice.

```css
.my-panel {
  font: var(--gcds-font-text);
  color: var(--gcds-text-primary);
  background-color: var(--gcds-bg-light);
  padding: var(--gcds-spacing-300);
  border: var(--gcds-border-width-sm) solid var(--gcds-border-default);
}
@media (width < 48em) { .my-panel { font: var(--gcds-font-text-mobile); } }
```

Docs: https://design-system.canada.ca/en/foundations/design-tokens/

## Semantic colours (prefer these)

| Token | Value |
|---|---|
| `--gcds-border-default` | `#8c8c8c` |
| `--gcds-active-background` | `#000` |
| `--gcds-active-text` | `#fff` |
| `--gcds-bg-dark` | `#333` |
| `--gcds-bg-light` | `#f2f2f2` |
| `--gcds-bg-primary` | `#26374a` |
| `--gcds-bg-white` | `#fff` |
| `--gcds-danger-background` | `#b3192e` |
| `--gcds-danger-border` | `#b3192e` |
| `--gcds-danger-text` | `#b3192e` |
| `--gcds-disabled-background` | `#d9d9d9` |
| `--gcds-disabled-text` | `grey` |
| `--gcds-focus-background` | `#1354ec` |
| `--gcds-focus-border` | `#1354ec` |
| `--gcds-focus-text` | `#fff` |
| `--gcds-link-default` | `#1f497a` |
| `--gcds-link-hover` | `#1354ec` |
| `--gcds-link-light` | `#fff` |
| `--gcds-link-visited` | `#4b248f` |
| `--gcds-text-light` | `#fff` |
| `--gcds-text-primary` | `#333` |
| `--gcds-text-secondary` | `#595959` |

## Spacing scale

| Token | Value |
|---|---|
| `--gcds-spacing-0` | `0rem` |
| `--gcds-spacing-25` | `0.125rem` |
| `--gcds-spacing-50` | `0.25rem` |
| `--gcds-spacing-75` | `0.375rem` |
| `--gcds-spacing-100` | `0.5rem` |
| `--gcds-spacing-125` | `0.625rem` |
| `--gcds-spacing-150` | `0.75rem` |
| `--gcds-spacing-175` | `0.875rem` |
| `--gcds-spacing-200` | `1rem` |
| `--gcds-spacing-225` | `1.125rem` |
| `--gcds-spacing-250` | `1.25rem` |
| `--gcds-spacing-300` | `1.5rem` |
| `--gcds-spacing-350` | `1.75rem` |
| `--gcds-spacing-400` | `2rem` |
| `--gcds-spacing-450` | `2.25rem` |
| `--gcds-spacing-500` | `2.5rem` |
| `--gcds-spacing-550` | `2.75rem` |
| `--gcds-spacing-600` | `3rem` |
| `--gcds-spacing-650` | `3.25rem` |
| `--gcds-spacing-700` | `3.5rem` |
| `--gcds-spacing-750` | `3.75rem` |
| `--gcds-spacing-800` | `4rem` |
| `--gcds-spacing-850` | `4.25rem` |
| `--gcds-spacing-900` | `4.5rem` |
| `--gcds-spacing-950` | `4.75rem` |
| `--gcds-spacing-1000` | `5rem` |
| `--gcds-spacing-1050` | `5.25rem` |
| `--gcds-spacing-1100` | `5.5rem` |
| `--gcds-spacing-1150` | `5.75rem` |
| `--gcds-spacing-1200` | `6rem` |
| `--gcds-spacing-1250` | `6.25rem` |

## Typography

| Token | Value |
|---|---|
| `--gcds-font-h1` | `700 2.5625rem/117% "Lato",sans-serif` |
| `--gcds-font-h1-mobile` | `700 2.3125rem/119% "Lato",sans-serif` |
| `--gcds-font-h2` | `700 2.4375rem/123% "Lato",sans-serif` |
| `--gcds-font-h2-mobile` | `700 2.1875rem/125% "Lato",sans-serif` |
| `--gcds-font-h3` | `700 1.8125rem/137% "Lato",sans-serif` |
| `--gcds-font-h3-mobile` | `700 1.625rem/123% "Lato",sans-serif` |
| `--gcds-font-h4` | `700 1.6875rem/133% "Lato",sans-serif` |
| `--gcds-font-h4-mobile` | `700 1.5rem/133% "Lato",sans-serif` |
| `--gcds-font-h5` | `700 1.5rem/133% "Lato",sans-serif` |
| `--gcds-font-h5-mobile` | `700 1.375rem/127% "Lato",sans-serif` |
| `--gcds-font-h6` | `700 1.375rem/145% "Lato",sans-serif` |
| `--gcds-font-h6-mobile` | `700 1.25rem/140% "Lato",sans-serif` |
| `--gcds-font-text` | `400 1.25rem/160% "Noto Sans",sans-serif` |
| `--gcds-font-text-mobile` | `400 1.125rem/155% "Noto Sans",sans-serif` |
| `--gcds-font-text-mono` | `400 1.25rem/160% "Noto Sans Mono",monospace` |
| `--gcds-font-text-mono-mobile` | `400 1.125rem/155% "Noto Sans Mono",monospace` |
| `--gcds-font-text-small` | `400 1.125rem/155% "Noto Sans",sans-serif` |
| `--gcds-font-text-small-mobile` | `400 1rem/150% "Noto Sans",sans-serif` |
| `--gcds-font-families-heading` | `"Lato",sans-serif` |
| `--gcds-font-families-body` | `"Noto Sans",sans-serif` |
| `--gcds-font-families-monospace` | `"Noto Sans Mono",monospace` |
| `--gcds-font-families-icons` | `"gcds-icons"` |
| `--gcds-font-sizes-text-small` | `1.125rem` |
| `--gcds-font-sizes-text-small-mobile` | `1rem` |
| `--gcds-font-sizes-text` | `1.25rem` |
| `--gcds-font-sizes-text-mobile` | `1.125rem` |
| `--gcds-font-sizes-h1` | `2.5625rem` |
| `--gcds-font-sizes-h1-mobile` | `2.3125rem` |
| `--gcds-font-sizes-h2` | `2.4375rem` |
| `--gcds-font-sizes-h2-mobile` | `2.1875rem` |
| `--gcds-font-sizes-h3` | `1.8125rem` |
| `--gcds-font-sizes-h3-mobile` | `1.625rem` |
| `--gcds-font-sizes-h4` | `1.6875rem` |
| `--gcds-font-sizes-h4-mobile` | `1.5rem` |
| `--gcds-font-sizes-h5` | `1.5rem` |
| `--gcds-font-sizes-h5-mobile` | `1.375rem` |
| `--gcds-font-sizes-h6` | `1.375rem` |
| `--gcds-font-sizes-h6-mobile` | `1.25rem` |
| `--gcds-font-weights-light` | `300` |
| `--gcds-font-weights-regular` | `400` |
| `--gcds-font-weights-medium` | `500` |
| `--gcds-font-weights-semibold` | `600` |
| `--gcds-font-weights-bold` | `700` |
| `--gcds-line-heights-text-small` | `155%` |
| `--gcds-line-heights-text-small-mobile` | `150%` |
| `--gcds-line-heights-text` | `160%` |
| `--gcds-line-heights-text-mobile` | `155%` |
| `--gcds-line-heights-h1` | `117%` |
| `--gcds-line-heights-h1-mobile` | `119%` |
| `--gcds-line-heights-h2` | `123%` |
| `--gcds-line-heights-h2-mobile` | `125%` |
| `--gcds-line-heights-h3` | `137%` |
| `--gcds-line-heights-h3-mobile` | `123%` |
| `--gcds-line-heights-h4` | `133%` |
| `--gcds-line-heights-h4-mobile` | `133%` |
| `--gcds-line-heights-h5` | `133%` |
| `--gcds-line-heights-h5-mobile` | `127%` |
| `--gcds-line-heights-h6` | `145%` |
| `--gcds-line-heights-h6-mobile` | `140%` |

## Borders and outlines

| Token | Value |
|---|---|
| `--gcds-border-radius-sm` | `0.125rem` |
| `--gcds-border-radius-md` | `0.375rem` |
| `--gcds-border-radius-lg` | `3rem` |
| `--gcds-border-radius-xl` | `100%` |
| `--gcds-border-width-sm` | `0.0625rem` |
| `--gcds-border-width-md` | `0.125rem` |
| `--gcds-border-width-lg` | `0.25rem` |
| `--gcds-border-width-xl` | `0.375rem` |
| `--gcds-outline-width` | `0.25rem` |

## Container sizes

| Token | Value |
|---|---|
| `--gcds-container-size-xs` | `20rem` |
| `--gcds-container-size-sm` | `30rem` |
| `--gcds-container-size-md` | `48rem` |
| `--gcds-container-size-lg` | `62rem` |
| `--gcds-container-size-xl` | `71.25rem` |
| `--gcds-container-size-full` | `100%` |

## Base colour palette (use only when no semantic token fits)

| Token | Value |
|---|---|
| `--gcds-color-black` | `#000` |
| `--gcds-color-blue-50` | `#ebf2fa` |
| `--gcds-color-blue-100` | `#d6e4f5` |
| `--gcds-color-blue-150` | `#c2d7f0` |
| `--gcds-color-blue-200` | `#adcaeb` |
| `--gcds-color-blue-250` | `#99bde6` |
| `--gcds-color-blue-300` | `#85afe0` |
| `--gcds-color-blue-350` | `#70a2db` |
| `--gcds-color-blue-400` | `#5c95d6` |
| `--gcds-color-blue-450` | `#4788d1` |
| `--gcds-color-blue-500` | `#337acc` |
| `--gcds-color-blue-550` | `#2e6eb8` |
| `--gcds-color-blue-600` | `#2962a3` |
| `--gcds-color-blue-650` | `#24568f` |
| `--gcds-color-blue-700` | `#1f497a` |
| `--gcds-color-blue-750` | `#193d66` |
| `--gcds-color-blue-800` | `#143152` |
| `--gcds-color-blue-850` | `#0f253d` |
| `--gcds-color-blue-900` | `#0a1829` |
| `--gcds-color-blue-muted` | `#26374a` |
| `--gcds-color-blue-vivid` | `#1354ec` |
| `--gcds-color-grayscale-50` | `#f2f2f2` |
| `--gcds-color-grayscale-100` | `#e6e6e6` |
| `--gcds-color-grayscale-150` | `#d9d9d9` |
| `--gcds-color-grayscale-200` | `#ccc` |
| `--gcds-color-grayscale-250` | `#bfbfbf` |
| `--gcds-color-grayscale-300` | `#b3b3b3` |
| `--gcds-color-grayscale-350` | `#a6a6a6` |
| `--gcds-color-grayscale-400` | `#999` |
| `--gcds-color-grayscale-450` | `#8c8c8c` |
| `--gcds-color-grayscale-500` | `grey` |
| `--gcds-color-grayscale-550` | `#737373` |
| `--gcds-color-grayscale-600` | `#666` |
| `--gcds-color-grayscale-650` | `#595959` |
| `--gcds-color-grayscale-700` | `#4d4d4d` |
| `--gcds-color-grayscale-750` | `#404040` |
| `--gcds-color-grayscale-800` | `#333` |
| `--gcds-color-grayscale-850` | `#262626` |
| `--gcds-color-grayscale-900` | `#1a1a1a` |
| `--gcds-color-green-50` | `#ebfaf0` |
| `--gcds-color-green-100` | `#d6f5e1` |
| `--gcds-color-green-150` | `#c2f0d3` |
| `--gcds-color-green-200` | `#adebc4` |
| `--gcds-color-green-250` | `#99e6b5` |
| `--gcds-color-green-300` | `#85e0a6` |
| `--gcds-color-green-350` | `#70db97` |
| `--gcds-color-green-400` | `#5cd689` |
| `--gcds-color-green-450` | `#47d17a` |
| `--gcds-color-green-500` | `#33cc6b` |
| `--gcds-color-green-550` | `#2eb860` |
| `--gcds-color-green-600` | `#29a356` |
| `--gcds-color-green-650` | `#248f4b` |
| `--gcds-color-green-700` | `#1f7a40` |
| `--gcds-color-green-750` | `#196636` |
| `--gcds-color-green-800` | `#14522b` |
| `--gcds-color-green-850` | `#0f3d20` |
| `--gcds-color-green-900` | `#0a2915` |
| `--gcds-color-purple-50` | `#f0ebfa` |
| `--gcds-color-purple-100` | `#e1d6f5` |
| `--gcds-color-purple-150` | `#d3c2f0` |
| `--gcds-color-purple-200` | `#c4adeb` |
| `--gcds-color-purple-250` | `#b599e6` |
| `--gcds-color-purple-300` | `#a685e0` |
| `--gcds-color-purple-350` | `#9770db` |
| `--gcds-color-purple-400` | `#895cd6` |
| `--gcds-color-purple-450` | `#7a47d1` |
| `--gcds-color-purple-500` | `#6b33cc` |
| `--gcds-color-purple-550` | `#602eb8` |
| `--gcds-color-purple-600` | `#5629a3` |
| `--gcds-color-purple-650` | `#4b248f` |
| `--gcds-color-purple-700` | `#401f7a` |
| `--gcds-color-purple-750` | `#361966` |
| `--gcds-color-purple-800` | `#2b1452` |
| `--gcds-color-purple-850` | `#200f3d` |
| `--gcds-color-purple-900` | `#150a29` |
| `--gcds-color-red-50` | `#fce9eb` |
| `--gcds-color-red-100` | `#f9d2d7` |
| `--gcds-color-red-150` | `#f5bcc4` |
| `--gcds-color-red-200` | `#f2a6b0` |
| `--gcds-color-red-250` | `#ef8f9c` |
| `--gcds-color-red-300` | `#ec7988` |
| `--gcds-color-red-350` | `#e96375` |
| `--gcds-color-red-400` | `#e64d61` |
| `--gcds-color-red-450` | `#e2364d` |
| `--gcds-color-red-500` | `#df2039` |
| `--gcds-color-red-550` | `#c91d34` |
| `--gcds-color-red-600` | `#b3192e` |
| `--gcds-color-red-650` | `#9c1628` |
| `--gcds-color-red-700` | `#861322` |
| `--gcds-color-red-750` | `#70101d` |
| `--gcds-color-red-800` | `#590d17` |
| `--gcds-color-red-850` | `#430a11` |
| `--gcds-color-red-900` | `#2d060b` |
| `--gcds-color-white` | `#fff` |
| `--gcds-color-yellow-50` | `#fef7e7` |
| `--gcds-color-yellow-100` | `#fcefcf` |
| `--gcds-color-yellow-150` | `#fbe7b6` |
| `--gcds-color-yellow-200` | `#fade9e` |
| `--gcds-color-yellow-250` | `#f9d686` |
| `--gcds-color-yellow-300` | `#f7ce6e` |
| `--gcds-color-yellow-350` | `#f6c655` |
| `--gcds-color-yellow-400` | `#f5be3d` |
| `--gcds-color-yellow-450` | `#f4b625` |
| `--gcds-color-yellow-500` | `#f2ad0d` |
| `--gcds-color-yellow-550` | `#da9c0b` |
| `--gcds-color-yellow-600` | `#c28b0a` |
| `--gcds-color-yellow-650` | `#aa7909` |
| `--gcds-color-yellow-700` | `#916808` |
| `--gcds-color-yellow-750` | `#795706` |
| `--gcds-color-yellow-800` | `#614505` |
| `--gcds-color-yellow-850` | `#493404` |
| `--gcds-color-yellow-900` | `#302303` |
