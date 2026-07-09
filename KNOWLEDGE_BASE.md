# HumHub Thiscovery Theme Module — Knowledge Base

**Document version:** 1.0  
**Module version:** 2.3.0  
**Last updated:** 9 July 2026  
**Maintainer:** D Cube Consulting Ltd — [info@dcubeconsulting.co.uk](mailto:info@dcubeconsulting.co.uk)  
**License:** Proprietary — Copyright (c) D Cube Consulting Ltd. All rights reserved.

---

## Table of contents

1. [Executive summary](#1-executive-summary)
2. [What this module does](#2-what-this-module-does)
3. [System requirements](#3-system-requirements)
4. [Architecture overview](#4-architecture-overview)
5. [Installation](#5-installation)
6. [Deployment and updates](#6-deployment-and-updates)
7. [Administration](#7-administration)
8. [Configuration reference](#8-configuration-reference)
9. [Mobile navigation](#9-mobile-navigation)
10. [Export and import](#10-export-and-import)
11. [Optional module integrations](#11-optional-module-integrations)
12. [Theme build process](#12-theme-build-process)
13. [Technical reference](#13-technical-reference)
14. [Troubleshooting](#14-troubleshooting)
15. [Version history](#15-version-history)
16. [Support and ownership](#16-support-and-ownership)

---

## 1. Executive summary

The **Thiscovery Theme** module is a proprietary HumHub extension that delivers branded, mobile-friendly site styling for the THIScovery platform and similar deployments. It combines:

- A bundled **Thiscovery** child theme (extends HumHub’s base theme)
- A full **admin configuration UI** for colours, typography, layout, navigation, and accordions
- **Mobile navigation** modes: hamburger panel or floating bottom bar
- **JSON export/import** for promoting settings between environments
- Automatic **SCSS compilation** when settings are saved

Admin URL: `/thiscovery-theme/config`  
GitHub repository: `gauravsdcube/thiscovery-theme`

---

## 2. What this module does

### Branding and visual design

- Applies a GOV.UK-inspired default colour palette (blues, greys, reds) that can be fully customised
- Syncs Bootstrap theme colours to HumHub’s global design settings
- Controls typography (base font, heading font, sizes, weights)
- Styles panels, cards, tables, lists, sidebars, footers, forms, and buttons
- Provides a **Custom CSS rules** editor with validation (invalid CSS is rejected before save)
- Styles FAQ accordions (`.faq-accordion`) and TinyMCE accordions (`details.mce-accordion`)

### Navigation

- **Desktop:** unified top header with logo, centre navigation links, and utility area (search, notifications, messages, account)
- **Mobile hamburger:** slide-down panel with configurable links, custom links, and account section
- **Mobile floating bar:** pill-style bottom navigation with icons and labels
- Replaces HumHub’s default top-navigation JavaScript when the Thiscovery theme is active

### Profile and layout

- Profile and space banner crop: **1962×192 px**; desktop display height **192 px**
- Configurable max container width (default 1600px)
- Mobile content spacing controls (padding, gutters, panel spacing)

### Administration

- Collapsible settings sections in the admin UI
- Export/import of full configuration as JSON
- Version number displayed in admin header

---

## 3. System requirements

| Requirement | Details |
|-------------|---------|
| HumHub | **1.18.0** or later |
| PHP | Same as your HumHub installation |
| SCSS compilation | HumHub’s built-in ScssPhp pipeline (standard) |
| File permissions | Web server must be able to write compiled theme CSS to HumHub’s assets directory |
| Database | No custom tables; settings stored in HumHub’s settings storage |

### Optional companion modules

These are not required but integrate when installed:

- **Menu Manager** — hidden desktop menu items can appear in mobile navigation
- **Site Footer** — Legals link placement in mobile menus
- **Calendar** — calendar URL mapping for mobile menu items
- **Jitsi Meet** — optional top-menu URL mapping

---

## 4. Architecture overview

```
┌─────────────────────────────────────────────────────────────────┐
│                     HumHub Application                          │
├─────────────────────────────────────────────────────────────────┤
│  thiscovery-theme module                                        │
│  ├── Admin UI (ConfigController + ConfigForm)                   │
│  ├── Settings → HumHub SettingsManager (module + global)         │
│  ├── SCSS generation → global themeCustomScss                  │
│  ├── Events → swap TopNavigationAsset for custom JS              │
│  └── ProfileBannerImage override (1962×192 crop)               │
├─────────────────────────────────────────────────────────────────┤
│  Thiscovery child theme (inside module)                         │
│  ├── themes/Thiscovery/scss/  → compiles to theme.css           │
│  └── themes/Thiscovery/views/ → layout + topNavigation overrides │
├─────────────────────────────────────────────────────────────────┤
│  Parent theme: /themes/HumHub (HumHub base theme)               │
└─────────────────────────────────────────────────────────────────┘
```

### How settings become live CSS

1. Administrator saves the configuration form.
2. `ConfigForm` validates and persists all settings.
3. Brand colours are synced to HumHub global design settings.
4. Generated SCSS (CSS custom properties and rules) is injected into global `themeCustomScss`.
5. The **Thiscovery** theme is activated.
6. `ThemeHelper::buildCss()` compiles SCSS and publishes `theme.css`.
7. On each page render, if the Thiscovery theme is active, custom top-navigation JavaScript is loaded.

### Theme location

The Thiscovery theme lives **inside the module**, not in `/themes/Thiscovery`:

```
protected/modules/thiscovery-theme/themes/Thiscovery/
```

HumHub discovers it automatically via module theme scanning.

---

## 5. Installation

### Step 1 — Deploy the module

Copy or clone the module to:

```
protected/modules/thiscovery-theme
```

Example (production server with deploy key):

```bash
cd /var/www/humhub/protected/modules
git clone git@github.com:gauravsdcube/thiscovery-theme.git thiscovery-theme
```

### Step 2 — Set permissions

Ensure the web server user (e.g. `www-data`) can read the module and write compiled CSS:

```bash
chown -R www-data:www-data protected/modules/thiscovery-theme
```

### Step 3 — Enable the module

1. Log in as a system administrator.
2. Go to **Administration → Modules**.
3. Find **Thiscovery Theme** and click **Enable**.

### Step 4 — Configure and build

1. Open **Administration → Modules → Thiscovery Theme** (or visit `/thiscovery-theme/config`).
2. Review and adjust settings.
3. Click **Save** — this compiles CSS and activates the Thiscovery theme.

### Step 5 — Confirm appearance

1. Go to **Administration → Settings → Appearance**.
2. Confirm **Thiscovery** is the active theme.
3. Hard-refresh the browser (`Ctrl+Shift+R` / `Cmd+Shift+R`) if styles do not appear immediately.

### Step 6 — Flush cache (if needed)

```bash
php protected/yii cache/flush-all
```

---

## 6. Deployment and updates

### Git repository

| Item | Value |
|------|-------|
| Repository | `https://github.com/gauravsdcube/thiscovery-theme` |
| SSH remote (server alias) | `git@github.com-thiscovery-theme:gauravsdcube/thiscovery-theme.git` |
| Current release branch | `release/v2.3.0` (also `main`) |

### Deploy key (public)

Add to GitHub → Repository → Settings → Deploy keys:

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIF1y9XHV+y7n36gw0udwOgT3G8axbzcZJxb7sBwH3QgT deploy-thiscovery-theme@ip-172-31-29-33
```

Enable **Allow write access** if pushing from the server.

Private key on dev server: `/home/admin/.ssh/thiscovery_theme_deploy`

### Update procedure

```bash
cd protected/modules/thiscovery-theme
git fetch origin
git checkout release/v2.3.0   # or main
git pull
php protected/build-thiscovery-theme.php
php protected/yii cache/flush-all
```

### Promoting configuration between environments

1. On source environment: `/thiscovery-theme/config` → **Export** → download `thiscovery-theme-configuration.json`.
2. On target environment: upload the JSON at the top of the config page.
3. Click import/save — CSS is rebuilt automatically.

---

## 7. Administration

### Access

| Item | Value |
|------|-------|
| URL | `/thiscovery-theme/config` |
| Permission required | `ManageSettings` (system administrator) |
| Export URL | `/thiscovery-theme/config/export` |

### Admin page structure

The configuration page is organised into collapsible sections:

1. **Import / export theme**
2. **Brand colours**
3. **Danger badge colours**
4. **Layout & top header**
5. **Mobile settings**
6. **Typography**
7. **Page & content**
8. **Top navigation menus**
9. **Forms & buttons**
10. **Cards, tables & lists**
11. **Sidebar & side navigation**
12. **Footer**
13. **Accordions**
14. **Custom CSS rules**

Each section can be expanded or collapsed independently. Colour fields support hex text input and an optional colour picker.

### Save behaviour

- **Save** validates all fields, persists settings, syncs SCSS, activates the Thiscovery theme, and rebuilds CSS.
- Validation errors are shown with specific messages (e.g. invalid hex colour, invalid CSS declaration).
- Import follows the same rebuild path as a manual save.

---

## 8. Configuration reference

### Brand colours

| Setting | Purpose |
|---------|---------|
| Primary | Main brand colour (links, primary buttons) |
| Accent | Secondary brand accent |
| Secondary | Secondary UI colour |
| Success / Danger / Warning / Info | Bootstrap semantic colours |
| Light / Dark | Light and dark UI variants |

These sync to HumHub’s global Bootstrap theme colour settings on save.

### Danger badge colours

Separate from the brand danger colour. Controls notification count badges and red badge styling in the top bar.

### Layout and top header

| Setting | Default (approx.) | Purpose |
|---------|-------------------|---------|
| Container max width | 1600px | Maximum content width |
| Top bar height | configurable | Header bar height |
| Top bar font size | configurable | Header font size |
| Top menu alignment | flex-start | Centre nav link alignment |
| Top header background | configurable | Header bar background |
| Top header text colour | configurable | Search, notifications, account text |
| Top header button hover colours | configurable | Hover state for utility buttons |

**Important:** Top header text colour affects the **utility area** (search, notifications, messages, account). Centre navigation links use the separate **menu item** colour settings below.

### Typography

| Setting | Purpose |
|---------|---------|
| Font family | Base body font |
| Heading font family | Headings (optional override) |
| Font size / weight / bold weight | Base text styling |
| H1–H6 font sizes | Per-heading sizes |

### Page and content

| Setting | Purpose |
|---------|---------|
| Link colour | Hyperlink colour |
| Text colour main / secondary | Body text colours |
| Background colour main / page | Content and page backgrounds |
| Panel border colour / radius / shadow | Panel card styling |
| Button border radius | Global button rounding |

### Top navigation menus

| Setting | Purpose |
|---------|---------|
| Text transform | none, uppercase, lowercase, capitalize |
| Font weight | 300–800 |
| Font style | normal, italic |
| Letter spacing | Spacing between letters |
| Menu item text / hover / active colours | Centre nav link states |
| Menu item active background | Active link highlight |
| Menu item padding X / Y | Link padding |

### Forms and buttons

Primary, secondary, light, and default button colours; input border/background/focus colours; button padding and font weight.

### Cards, tables, and lists

Card background/border/shadow; table header and row colours; list group item colours and spacing.

### Sidebar and side navigation

Sidebar background, text, link, and active colours; padding and border radius.

### Footer

Footer background and text colours (works alongside Site Footer module content).

### Accordions

Full styling for:

- **FAQ accordions** — `.faq-accordion` class
- **TinyMCE accordions** — `details.mce-accordion` elements

Controls include header/body colours, border, padding, font size, font weight, and chevron colour.

### Custom CSS rules

Add arbitrary CSS as validated selector/declaration pairs:

| Field | Purpose |
|-------|---------|
| Description | Admin note (not output to CSS) |
| Selector | CSS selector (e.g. `.my-class`, `#my-id`) |
| Declarations | CSS properties (e.g. `color: #fff; margin: 0;`) |

Invalid declarations are rejected before save. Legacy hardcoded accordion rules in pasted custom SCSS are stripped automatically on save so accordion admin settings take effect.

---

## 9. Mobile navigation

Mobile navigation is configured under **Mobile settings** in the admin UI.

### Navigation styles

| Style | Description |
|-------|-------------|
| **Hamburger** | Slide-down panel from the top bar; labels only (no icons in panel) |
| **Floating bar** | Pill-style bar fixed at the bottom of the screen with icons and labels |

Search, notifications, and messages always remain in the **top bar** on mobile regardless of style.

### Floating bottom bar

| Setting | Purpose |
|---------|---------|
| Floating bar items | Checkboxes + sort order for each link |
| Show profile in floating navigation | Profile photo as last item (mobile only) |
| Show Legals in floating navigation | Move Site Footer Legals from hamburger to floating bar |
| Background colour / opacity | Bar appearance |
| Text colour / active colour | Item colours |
| Hide item labels | Icons only |
| Hide on scroll down | Auto-hide bar when scrolling down |

**Rules:**

- **Home** is always first in the floating bar when enabled (mobile only).
- **Profile** is always last when enabled (mobile only).
- Home and profile floating items are **hidden on desktop**.

### Hamburger menu

| Setting | Purpose |
|---------|---------|
| Hamburger menu items | Main links in the slide-down panel |
| Custom hamburger links | Arbitrary label + URL pairs (add, edit, delete, sort) |
| Hamburger account items | Settings, logout, etc. |
| Background / text colour | Panel appearance |
| Font size | Panel text size |
| Highlight active item | Visual active state |
| Item padding X / Y | Link spacing |

Account header is hidden in the hamburger panel; account links appear in the main panel list.

### Mobile layout spacing

| Setting | Purpose |
|---------|---------|
| Mobile content padding X / Y | Page content padding |
| Mobile content gutter | Column gutter |
| Mobile panel spacing | Gap between panels |
| Mobile panel body padding | Inner panel padding |
| Mobile topbar padding X | Top bar horizontal padding |

### Menu item assignment workflow

1. Choose navigation style (hamburger or floating bar).
2. Under **Floating bottom bar items**, check the links to show in the bottom bar and set sort order (lower = earlier).
3. Under **Hamburger menu items**, check links for the slide-down panel.
4. Add **Custom hamburger links** for external or custom URLs.
5. Configure **Hamburger account items** for settings/logout.
6. Save — settings persist and mobile JS distributes items accordingly.

### Menu Manager compatibility

If the **Menu Manager** module is installed, items hidden from the desktop top menu can still be assigned to mobile navigation. The module resolves menu link IDs and URLs at runtime.

### Site Footer Legals

If the **Site Footer** module is installed:

- **Legals** appears in the hamburger menu by default.
- Enable **Show Legals in floating navigation** to move it to the floating bar instead.

---

## 10. Export and import

### Export

1. Go to `/thiscovery-theme/config`.
2. Click **Export** (or visit `/thiscovery-theme/config/export`).
3. Download `thiscovery-theme-configuration.json`.

### Import

1. Go to `/thiscovery-theme/config`.
2. Under **Import / export theme**, upload a JSON file.
3. Submit — settings are applied and CSS is rebuilt.

### JSON format

| Field | Value |
|-------|-------|
| File name | `thiscovery-theme-configuration.json` |
| Format key | `thiscovery-theme` |
| Export version | `1` |
| Includes | `moduleVersion`, `exportedAt`, all ConfigForm settings |

Use export/import to promote branding from dev → staging → production without manually re-entering hundreds of settings.

---

## 11. Optional module integrations

| Module | Integration behaviour |
|--------|----------------------|
| **Menu Manager** | Discovers menu items; resolves IDs; injects desktop-hidden items into mobile nav when configured |
| **Site Footer** | Provides Legals menu item; configurable hamburger vs floating bar placement |
| **Calendar** | Maps calendar module URL for mobile menu item IDs |
| **Jitsi Meet** | Optional top-menu URL mapping |

All integrations use runtime `class_exists` / module checks — the theme module works without them.

---

## 12. Theme build process

### Automatic build (recommended)

Saving the admin configuration form or importing JSON triggers:

1. `ConfigForm::save()`
2. `ThemeHelper::getThemeByName('Thiscovery')->activate()`
3. `ThemeHelper::buildCss($theme)`

### CLI rebuild

Use after deployment or if styles appear stale:

```bash
php /var/www/humhub/protected/build-thiscovery-theme.php
```

This script:

1. Bootstraps the HumHub console application
2. Runs `ConfigForm::save()` to re-sync generated SCSS
3. Activates the Thiscovery theme
4. Compiles and publishes CSS

Expected output:

```
Thiscovery theme CSS rebuilt successfully.
```

### SCSS structure

| File | Purpose |
|------|---------|
| `themes/Thiscovery/scss/build.scss` | Build entry point |
| `themes/Thiscovery/scss/variables.scss` | Parent theme reference (`$baseTheme: "HumHub"`) |
| `themes/Thiscovery/scss/_base.scss` | Base imports |
| `themes/Thiscovery/scss/_theme.scss` | Desktop styling (topbar, buttons, panels, stream) |
| `themes/Thiscovery/scss/_accordions.scss` | Accordion CSS variables |
| `themes/Thiscovery/scss/_mobile.scss` | Mobile hamburger and floating bar |
| `themes/Thiscovery/scss/config-generated-root.scss` | Gitignored site snapshot (not primary save target) |

Generated admin settings are written to HumHub’s global `themeCustomScss` setting, not only to the gitignored file.

---

## 13. Technical reference

### Module metadata

| Item | Value |
|------|-------|
| Module ID | `thiscovery-theme` |
| Namespace | `humhub\modules\thiscoveryTheme` |
| Theme name | `Thiscovery` |
| Version | `2.3.0` |
| Icon | `paint-brush` |

### Key files

| Path | Purpose |
|------|---------|
| `Module.php` | Module entry; profile banner registration; theme active check |
| `config.php` | View event registration |
| `Events.php` | Swaps top-nav asset and injects JS config |
| `controllers/ConfigController.php` | Admin config, export, theme activation |
| `models/ConfigForm.php` | Settings model, validation, SCSS generation |
| `models/ThemeImportForm.php` | JSON import |
| `libs/MobileMenuHelper.php` | Menu discovery, ID resolution, caching |
| `libs/ProfileBannerImage.php` | 1962×192 banner crop |
| `assets/ThiscoveryTopNavigationAsset.php` | Custom top-nav JS asset |
| `resources/js/humhub.thiscoveryTheme.topNavigation.js` | Mobile nav logic (~1260 lines) |
| `themes/Thiscovery/views/humhub/layouts/main.php` | Main layout override |
| `themes/Thiscovery/views/humhub/widgets/views/topNavigation.php` | Top nav markup override |

### Events

| Event | Handler | Behaviour |
|-------|---------|-----------|
| `View::EVENT_BEFORE_RENDER` | `Events::onViewBeforeRender` | If Thiscovery theme active: unregisters `TopNavigationAsset`, registers custom JS config |
| `Space::EVENT_INIT` | `Module::init` | Sets `profileBannerImageClass` to `ProfileBannerImage` |
| `User::EVENT_INIT` | `Module::init` | Same banner class override |

### Routes

| Route | Action |
|-------|--------|
| `/thiscovery-theme/config` | Admin configuration |
| `/thiscovery-theme/config/export` | JSON export download |

### Data storage

- **No custom database tables**
- Settings stored via HumHub `SettingsManager` (module settings + global settings)
- Legacy data migrations run on load/save (menu ID aliases, mobile style renames, SCSS marker cleanup)

### Migrations (runtime, not SQL)

| Migration | Purpose |
|-----------|---------|
| Legacy mobile style | `bottom-bar` → `floating-bar` |
| Menu item IDs | Legacy hash IDs → canonical IDs |
| Sort order keys | Alias normalisation |
| Legacy SCSS markers | Removes old `GOVUK_THEME_GENERATED_*` blocks |
| Accordion CSS cleanup | Strips hardcoded accordion rules from custom SCSS |

---

## 14. Troubleshooting

### Styles do not update after save

1. Hard-refresh the browser.
2. Run `php protected/build-thiscovery-theme.php`.
3. Run `php protected/yii cache/flush-all`.
4. Confirm **Thiscovery** is active under **Administration → Settings → Appearance**.
5. Check web server can write to HumHub’s published assets directory.

### Theme save fails with validation error

- Read the specific error message shown in admin.
- Common causes: invalid hex colour, font weight out of range (100–900), invalid custom CSS declaration.
- Accordion font weight must be between 100 and 900.

### Mobile menu shows wrong items

1. Open `/thiscovery-theme/config` → **Mobile settings**.
2. Verify floating bar and hamburger checkboxes and sort orders.
3. Save again — legacy menu IDs are migrated to canonical IDs on save.
4. Clear browser cache and test on a real mobile device or responsive mode.

### Desktop shows mobile-only items (Home / Profile in nav)

This was fixed in v2.3.0. Ensure you are on **2.3.0** or later.

### Internal server error on save

- Check `protected/runtime/logs/app.log`.
- v2.3.0 fixed ConfigForm sort-order closure bugs that caused 500 errors.
- Ensure PHP memory limit is adequate (module sets 256M in some controllers).

### Menu Manager items not in mobile nav

1. Confirm Menu Manager module is enabled.
2. Assign the item to floating bar or hamburger in Thiscovery Theme mobile settings.
3. Save and hard-refresh.

### Export/import does not apply

- Confirm JSON file is a valid `thiscovery-theme` export (format key in file).
- Check file upload size limits in PHP (`upload_max_filesize`, `post_max_size`).
- Review admin error messages after import.

### Profile banner crop wrong size

The module overrides HumHub’s default banner crop to **1962×192 px**. Re-upload the banner after enabling the module if an older crop was applied.

---

## 15. Version history

| Version | Date | Summary |
|---------|------|---------|
| **2.3.0** | 2026-07-09 | Mobile nav overhaul: item assignment, custom hamburger links, Menu Manager compatibility, MobileMenuHelper, memory fixes |
| **2.2.0** | 2026-07-09 | Configurable mobile menu items, profile in floating bar, admin UI cleanup; removed legacy Clean Theme bottom bar |
| **2.1.0** | 2026-07-08 | Accordion admin settings, legacy accordion CSS cleanup, validation fixes |
| **2.0.0** | 2026-07-07 | Floating bottom nav, custom top-nav JS, profile banner crop, danger badge colours, button colours, CSS validation |
| **1.0.0** | 2026-07-07 | Initial release: child theme, admin config, export/import, top nav styling |

Full details: see [CHANGELOG.md](CHANGELOG.md).

---

## 16. Support and ownership

| Item | Details |
|------|---------|
| **Owner** | D Cube Consulting Ltd |
| **Email** | info@dcubeconsulting.co.uk |
| **Website** | https://dcubeconsulting.co.uk |
| **License** | Proprietary — see [LICENSE](LICENSE) |
| **Repository** | https://github.com/gauravsdcube/thiscovery-theme |

For maintenance, upgrades, or custom branding changes, contact D Cube Consulting.

---

## Quick reference card

```
Module path:     protected/modules/thiscovery-theme
Theme path:      protected/modules/thiscovery-theme/themes/Thiscovery
Parent theme:    themes/HumHub
Admin URL:       /thiscovery-theme/config
CLI rebuild:     php protected/build-thiscovery-theme.php
Cache flush:     php protected/yii cache/flush-all
Export file:     thiscovery-theme-configuration.json
Permission:      ManageSettings (system admin)
HumHub minimum:  1.18.0
Current version: 2.3.0
```
