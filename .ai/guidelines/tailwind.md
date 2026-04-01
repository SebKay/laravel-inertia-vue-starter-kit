# Tailwind in this app

Tailwind **v4** is wired through Vite (`@tailwindcss/vite` in `vite.config.js`), not a `tailwind.config.js` file. The entry point is `resources/css/app.css`.

## CSS-first pipeline

- **`@import "tailwindcss"`** — core.
- **`@plugin "@tailwindcss/forms"`** — form control resets/defaults (see package `@tailwindcss/forms`).
- **`@source "../../resources/";`** — content detection for class names under `resources/` (Vue, Blade, etc.). Filament has its own entry and sources (below).

## Design tokens (`resources/css/variables.css`)

Tokens live in an **`@theme { ... }`** block. They extend or replace Tailwind’s theme:

| Token                                           | Role                                                                                                                                                    |
| ----------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `--default-transition-duration`                 | `0.2s` (global transition duration)                                                                                                                     |
| `--font-text`, `--font-heading`, `--font-serif` | Body, heading, and serif stacks (currently Arial / Georgia placeholders). Exposed as utilities `font-text`, `font-heading`, `font-serif`.               |
| `--breakpoint-xs` … `--breakpoint-4xl`          | **Non-default breakpoints** (e.g. `sm` is `451px`, not Tailwind’s default `640px`). Prefer the app’s `sm:`, `md:`, etc. when matching existing layouts. |
| `--color-ui-1-1`                                | Primary brand green (`#257c53`). Use utilities like `bg-ui-1-1`, `text-ui-1-1`, `border-ui-1-1`, etc.                                                   |

Add new colors or fonts by defining `--color-*` / `--font-*` (and breakpoints if needed) in the same `@theme` block.

## Layer imports (after variables)

Order in `app.css`: `variables.css` → `typography.css` → `utilities.css` → `forms.css` → `buttons.css` → `tables.css` → `content.css`.

### Base layer

- `app.css`: `#app` gets `h-full`.
- `forms.css`: `label[for]` gets `cursor-pointer`.

### Custom `@utility` classes (use these instead of re-styling from scratch)

- **Links:** `text-link` — underline + hover behavior (`resources/css/utilities.css`).
- **Headings:** `heading`, `h1`–`h6` — responsive type scale + weight (`typography.css`).
- **Forms:** `form-row`, `form-col`, `form-col-2`, `label`, `small-label`, `inline-label`, `field`, `input`, `textarea`, `select`, `option`, `checkbox`, `radio`, `toggle`, `field-error`, `field-hint` (`forms.css`). Form pages should align with `resources/css/forms.css` and Blade `x-field-error` where applicable.
- **Buttons:** `button`, `button-full` — primary styling uses `bg-ui-1-1` (`buttons.css`).
- **Tables:** `table`, `table-wrap` (`tables.css`).
- **Rich HTML:** `content` — wraps headings, links (`text-link`), blockquotes (`font-serif`), lists, spacing, and nested `table` styling (`content.css`).

## Filament admin

`resources/css/filament/admin/theme.css` is a **separate Vite input**: it imports Filament’s vendor theme and adds `@source` for `app/Filament/**` and `resources/views/filament/**`. Inertia/app tokens from `app.css` are not automatically part of the Filament bundle unless duplicated or shared via that file.

## Tooling

- **Prettier:** `prettier-plugin-tailwindcss` sorts classes in `resources/`.
- **Vite aliases:** `@css` → `resources/css` (see `vite.config.js`).

## Quick reference for agents

1. Prefer **`bg-ui-1-1` / `text-ui-1-1`** (and related) for brand color, not arbitrary hex in new UI.
2. Remember **breakpoints differ from default Tailwind** — check `variables.css` before assuming standard `sm`/`md` widths.
3. Use **`font-text`** on the document body (`resources/views/app.blade.php`); headings in prose can use `heading` + `h1`–`h6` or the **`content`** wrapper for CMS-style HTML.
