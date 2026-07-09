# Changelog

## 2.3.0 — 2026-07-09

Mobile navigation overhaul: configurable item assignment, custom hamburger links, and Menu Manager compatibility.

### Features

- **Floating vs hamburger item assignment** — checkboxes and sort order for each link in the floating bar and hamburger panel
- **Custom hamburger links** — add, edit, delete, and sort arbitrary label/URL pairs in the mobile hamburger menu
- **Home in floating bar** — mobile-only Home item, always first in the floating bar when enabled
- **Profile in floating bar** — optional profile photo link, mobile only, always last in the floating bar
- **Site Footer Legals** — hamburger by default; toggle to show Legals in the floating bar instead
- **Menu Manager compatibility** — injects menu items hidden from desktop (e.g. Calendar) into mobile navigation when configured
- **Hamburger panel styling** — labels only (no icons), consistent spacing, account header hidden
- **MobileMenuHelper** — centralised menu discovery, ID resolution, and catalog caching

### Fixes

- Mobile menu distribution now respects saved settings (no spurious items in floating bar)
- Legacy menu ID aliases migrated to canonical IDs on save
- Desktop no longer shows mobile-only Home or Profile items
- PHP memory exhaustion from repeated TopMenu builds (request-level caching)
- Checkbox settings persistence when saving other theme sections
- ConfigForm sort-order closure bug causing internal server errors

## 2.2.0 — 2026-07-09

Mobile navigation configuration, profile in floating bar, and admin UI cleanup.

### Features

- **Configurable mobile menu items** — choose which links appear in the floating bottom bar vs the hamburger panel
- **Profile in floating bar** — user profile photo as the last floating bar item, linking directly to profile home
- **Hide floating bar on scroll** — setting retained and clarified in admin
- **Cleaner admin layout** — subsection dividers within collapsible settings (mobile, typography, etc.)

### Removed

- **Clean Theme bottom navigation bar** — legacy mobile mode and related settings removed (sites using it migrate to floating bottom navigation)

## 2.1.0 — 2026-07-08

Accordion styling in admin, with fixes for save validation and legacy CSS overrides.

### Features

- **Accordion settings** — configurable FAQ (`.faq-accordion`) and TinyMCE (`details.mce-accordion`) styling in admin
- **Legacy accordion CSS cleanup** — removes hardcoded accordion rules from pasted custom SCSS on save so settings apply correctly

### Fixes

- Accordion header font weight validation (allowed 100–900, not capped at 100)
- Theme save errors now show the specific validation message

## 2.0.0 — 2026-07-07

Major update with mobile navigation overhaul, floating bottom bar, and admin improvements.

### Features

- **Floating bottom navigation** — pill-style bar at the bottom with icons and labels; search, notifications, and messages stay in the top bar
- **Mobile menu styles** — hamburger, Clean Theme bottom bar, or floating bottom navigation (configurable)
- **Custom top navigation JS** — replaces HumHub `TopNavigationAsset` when the Thiscovery theme is active
- **Profile banner crop** — 1962×192 px upload crop; 192 px desktop display height
- **Danger badge colours** — separate admin settings for notification/badge styling
- **Button colours** — primary, secondary, light, and default button styling in admin
- **Custom CSS validation** — invalid declarations (e.g. `background color`) are caught before save
- **Desktop stream typography** — stream content uses configured typography settings

### Fixes

- Floating bar visibility and label layout (CSS specificity)
- Topbar utilities (search/notifications) preserved on mobile
- Dropdown menus readable on white backgrounds
- Space header stats and notification badge clipping
- Mobile hamburger gap and header height alignment
- Banner upload buttons on desktop (hover + z-index)

## 1.0.0 — 2026-07-07

Initial release.

### Features

- Thiscovery child theme with unified top header layout
- Admin configuration for brand colours, layout, typography, and page styling
- Top navigation menu styling (colours, padding, text case, weight, letter spacing)
- Custom CSS rules editor with descriptions
- Theme configuration export and import (JSON)
- Hex colour text inputs with optional colour picker
- Round notification badges and profile avatars

Copyright (c) D Cube Consulting Ltd. All rights reserved.
