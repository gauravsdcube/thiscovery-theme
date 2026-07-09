# HumHub Thiscovery Theme Module

**Version 2.3.0**

Copyright (c) D Cube Consulting Ltd. All rights reserved.

Configurable Thiscovery branding for HumHub: colours, layout, typography, top navigation, mobile navigation, and custom CSS — with a bundled **Thiscovery** child theme.

## Features

- **Brand colours** — Bootstrap palette integration (primary, accent, success, danger, etc.)
- **Layout & top header** — bar height, font size, alignment, header colours
- **Top navigation** — menu colours, padding, text case, font weight, letter spacing, italic
- **Typography** — base and heading fonts, sizes, weights
- **Page & content** — backgrounds, panel borders, shadows, radius
- **Forms, cards, tables, lists, sidebar, footer** — granular styling
- **Custom CSS rules** — selector/declaration pairs with descriptions
- **Mobile navigation** — hamburger panel or floating bottom navigation
- **Configurable menu assignment** — choose which links appear in the floating bar vs the hamburger panel, with per-item sort order
- **Custom hamburger links** — add your own label/URL links to the mobile hamburger menu (editable, deletable, sortable)
- **Floating bottom bar** — icons and labels in a pill at the bottom; optional profile item (mobile only)
- **Menu Manager compatibility** — items hidden from the desktop top menu can still be assigned to mobile navigation
- **Site Footer Legals** — Legals in the hamburger menu by default; optional toggle to show in the floating bar instead
- **Profile banners** — 1962×192 px crop; 192 px desktop display height
- **Danger badge colours** — separate settings for notification counts and red badges
- **Button colours** — primary, secondary, light, and default buttons
- **Custom CSS validation** — catches invalid CSS before save
- **Export / import** — share theme JSON between environments

## Requirements

- HumHub **1.18.0** or later
- PHP with SCSS compilation support (standard HumHub theme build)
- Optional: **Menu Manager**, **Site Footer**, and **Calendar** modules integrate with mobile menu configuration when installed

## Installation

1. Copy this module to `protected/modules/thiscovery-theme`.
2. Enable the module: **Administration → Modules → Thiscovery Theme**.
3. Open **Administration → Modules → Thiscovery Theme** (or `/thiscovery-theme/config`).
4. Configure settings and click **Save** to compile theme CSS.
5. Activate the **Thiscovery** theme: **Administration → Settings → Appearance**.

## Configuration

Admin URL: `/thiscovery-theme/config`

After changing settings, save the form to rebuild CSS. Hard-refresh the browser if styles do not update immediately.

### Mobile navigation

Under **Mobile settings** you can:

| Setting | Purpose |
|--------|---------|
| **Navigation style** | Floating bottom navigation or top hamburger menu |
| **Floating bottom bar items** | Links shown in the bottom pill (Home is always first on mobile) |
| **Hamburger menu items** | Main links in the slide-down panel |
| **Custom hamburger links** | Additional links with label, URL, and sort order |
| **Hamburger account items** | Settings, logout, etc. in the hamburger panel |
| **Show profile in floating navigation** | Profile photo as the last floating bar item (mobile only) |
| **Show Legals in floating navigation** | Move Site Footer Legals from hamburger to floating bar |

Sort order numbers control item position within each list. Lower numbers appear earlier.

### Top bar colour notes

| Setting | Affects |
|--------|---------|
| **Top header text color** | Search, notifications, messages, account name |
| **Menu item text color** | Centre navigation links (Dashboard, Spaces, etc.) |

Desktop top navigation is unchanged by mobile menu settings. Home and profile floating items are hidden on desktop.

## Export / import

Use **Import / export theme** at the top of the configuration page to download or upload `thiscovery-theme-configuration.json`.

## License

Proprietary — see [LICENSE](LICENSE).

## Contact

D Cube Consulting Ltd — [info@dcubeconsulting.co.uk](mailto:info@dcubeconsulting.co.uk) — [https://dcubeconsulting.co.uk](https://dcubeconsulting.co.uk)
