# WooCommerce SK/CZ Funkcie - Project Roadmap

## Strategic Direction
- Ship a small, reliable WooCommerce helper plugin for Slovak/Czech stores.
- Prioritize fast MVP delivery and stability over UI polish.
- Keep free and premium code separated from day one.
- Build recurring yearly revenue through per-site licensing sold on `nimble.help`.

## Product Vision
- Free plugin solves recurring WooCommerce checkout and catalog UX issues in SK/CZ projects.
- Premium add-on extends checkout logic and monetizable advanced features.
- License architecture is secure, cached, and checkout-safe when remote services fail.

## Phase 1 - MVP Plugin (Current)
Goal: working free plugin with core functions and safe data handling.

Scope:
- Plugin skeleton and bootstrap
- Feature toggles architecture (internal defaults first)
- Checkout company toggle: "Nakup na firmu?" with conditional fields
- GDPR checkbox at checkout with validation
- Product subcategories above products on category archives
- Save checkout custom fields to order meta
- SK/CZ translation-ready strings (`__()`, textdomain)
- Test on standard WooCommerce checkout flow

Definition of done:
- Installs and activates without errors
- No PHP warnings/notices in checkout flow
- Fields validate and save correctly
- Features can be independently enabled/disabled in code

## Phase 2 - Basic Settings
Goal: simple admin settings for free plugin features.

Scope:
- Admin menu page under WooCommerce or Settings
- Tabs:
  - General
  - Company Checkout Fields
  - GDPR Checkbox
  - Category Row
- Per-feature enable/disable controls
- Basic setting sanitization and nonce protection

## Phase 3 - Premium Add-on Plugin
Goal: separate premium plugin with licensing-ready architecture.

Scope:
- Separate premium plugin skeleton
- COD fee rules (Dobierka)
- Conditional checkout fields
- Additional checkout controls
- License verification service integration:
  - Signed tokens
  - Local token storage
  - Cached validation
  - Daily revalidation
  - Grace period when license server unavailable

## Phase 4 - Sales Infrastructure
Goal: complete commercial flow.

Scope:
- WooCommerce subscription products on `nimble.help`
- Stripe recurring billing setup
- License server endpoints
- Activation/deactivation UX
- Upgrade notices from free plugin to premium

## Backlog (Not for MVP)
- Field-level conditional logic builder
- Analytics/diagnostics screen
- More shop archive layout controls
- Onboarding wizard
