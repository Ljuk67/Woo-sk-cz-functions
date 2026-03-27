# WooCommerce SK/CZ Functions Project Roadmap

## Strategic Direction

- Ship a small, reliable WooCommerce helper plugin for Slovak and Czech stores.
- Prioritize fast MVP delivery and stability over UI polish.
- Keep free and premium code separated from day one.
- Build recurring yearly revenue through per-site licensing sold on `nimble.help`.

## Product Vision

- The free plugin solves recurring WooCommerce checkout and catalog UX issues in SK/CZ projects.
- The premium add-on extends checkout logic and monetizable advanced features.
- License architecture should remain secure, cached, and checkout-safe when remote services fail.

## Phase 1: MVP Plugin

Goal: a working free plugin with core functions and safe data handling.

Scope:

- plugin skeleton and bootstrap
- feature toggles architecture with internal defaults first
- checkout company toggle: "Nakup na firmu?" with conditional fields
- GDPR checkbox at checkout with validation
- hide all shipping options when free shipping is available
- remove Additional Information tab on single product pages
- product subcategories above products on category archives
- save checkout custom fields to order meta
- SK/CZ translation-ready strings with the proper textdomain
- test on the standard WooCommerce checkout flow

Definition of done:

- installs and activates without errors
- no PHP warnings or notices in checkout flow
- fields validate and save correctly
- features can be independently enabled and disabled in code

## Session Update: 2026-03-12

Completed:

- renamed project and plugin naming from `funkcie` to `functions` across folder and file names plus textdomain
- added and wired feature: hide paid shipping methods when free shipping is available
- added and wired feature: remove Additional Information tab on single product pages
- added and wired a barebones company checkout fields structure, not production-ready yet:
  - company purchase toggle
  - conditional company fields
  - validation and order meta saving
  - checkout JS for show and hide behavior
- refactored GDPR checkbox into class callbacks and removed nested global-functions risk
- added plugin action `Settings` link on the Plugins page
- added WooCommerce Settings tab integration scaffold at `WooCommerce -> Settings -> WooCommerce SK/CZ`
- added basic feature toggle fields in the WooCommerce settings tab
- standardized localization workflow:
  - source strings in English
  - added or updated `sk_SK` and `cs_CZ` translations as `.po` and `.mo`
- re-linked the LocalWP symlink after the project path rename

In progress or next:

- finalize WooCommerce settings tab save flow and verify all feature toggles persist correctly
- connect WooCommerce settings tab values fully with `WSCF_Settings` defaults and sanitization flow
- implement `category_row` fully if it is still scaffold-only
- add settings UI polish and help text

## Phase 2: Basic Settings

Goal: a simple admin settings surface for free plugin features.

Scope:

- admin menu page under WooCommerce or Settings
- tabs:
  - General
  - Company Checkout Fields
  - GDPR Checkbox
  - Category Row
- per-feature enable and disable controls
- basic setting sanitization and nonce protection

## Phase 3: Premium Add-on Plugin

Goal: a separate premium plugin with licensing-ready architecture.

Scope:

- separate premium plugin skeleton
- COD fee rules (Dobierka)
- conditional checkout fields
- additional checkout controls
- license verification service integration:
  - signed tokens
  - local token storage
  - cached validation
  - daily revalidation
  - grace period when the license server is unavailable

## Phase 4: Sales Infrastructure

Goal: a complete commercial flow.

Scope:

- WooCommerce subscription products on `nimble.help`
- Stripe recurring billing setup
- license server endpoints
- activation and deactivation UX
- upgrade notices from the free plugin to premium

## Backlog

Not for MVP:

- field-level conditional logic builder
- analytics and diagnostics screen
- more shop archive layout controls
- onboarding wizard

## Useful Info

### MVP Scope

- company checkout toggle with business fields
- GDPR checkbox at checkout
- hide paid shipping when free shipping is available
- remove Additional Information tab on single product pages
- product subcategories row above products on category archive pages

### Development Principles

- MVP first
- keep code modular and readable
- keep free and premium plugins separated
