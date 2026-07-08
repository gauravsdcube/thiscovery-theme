# Changelog

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
