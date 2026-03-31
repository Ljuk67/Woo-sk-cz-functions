# Current State

This file is the shared live project state for active work. Update it incrementally instead of rewriting it from scratch.

## Current Tasks

- [in_progress] Build MVP feature #1 (company checkout fields + order meta)
- [done] Build MVP feature #2 (GDPR checkbox + validation)
- [pending] Build MVP feature #3 (category row above products)
- [in_progress] Extend checkout-facing features to support both classic checkout and Checkout Block
- [pending] Keep all frontend labels Slovak-first and translatable
- [pending] Prepare GitHub setup steps for first push

## Completed Tasks

- [done] Created the project roadmap document
- [done] Defined roadmap phases and backlog boundaries
- [done] Confirmed MVP-first strategy and premium separation
- [done] Created the free plugin folder structure and bootstrap file
- [done] Added modular class architecture for feature registration
- [done] Added settings service with feature flags defaulted to enabled
- [done] Added uninstall cleanup and base docs
- [done] Added translation files scaffold (`.pot` + `cs_CZ.po`)
- [done] Compiled the Czech runtime translation file (`cs_CZ.mo`)
- [done] Switched feature settings to individual WooCommerce options
- [done] Changed feature defaults to merchant opt-in on fresh installs
- [done] Added GDPR checkbox support for both classic checkout and Checkout Block
- [done] Added Privacy Policy page link to the GDPR checkbox in both checkout implementations

## Upcoming Tasks

- Extend company checkout fields to work in both classic checkout and Checkout Block
- Audit other customer-facing features for block-compatible implementations where applicable
- Run a security and stability pass on checkout hooks
- Validate compatibility on both classic and block-based WooCommerce flows
