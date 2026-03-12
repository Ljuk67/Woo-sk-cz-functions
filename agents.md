# Agent Task Tracker

## Current Tasks
- [in_progress] Build MVP feature #1 (company checkout fields + order meta)
- [pending] Build MVP feature #2 (GDPR checkbox + validation)
- [pending] Build MVP feature #3 (category row above products)
- [pending] Keep all frontend labels Slovak-first and translatable
- [pending] Prepare GitHub setup steps for first push

## Completed Tasks
- [done] Created root roadmap in `ROADMAP.md`
- [done] Defined roadmap phases and backlog boundaries
- [done] Confirmed MVP-first strategy and premium separation
- [done] Created free plugin folder structure and bootstrap file
- [done] Added modular class architecture for feature registration
- [done] Added settings service with feature flags defaulted to enabled
- [done] Added uninstall cleanup and base docs
- [done] Added translation files scaffold (`.pot` + `cs_CZ.po`)
- [done] Compiled Czech runtime translation file (`cs_CZ.mo`)

## Upcoming Tasks
- Add simple admin settings UI with tabs and per-feature on/off
- Run security and stability pass on checkout hooks
- Validate compatibility on standard WooCommerce checkout flow

## Notes For AI Assistant
- Keep free and premium code separate.
- Prioritize stable WooCommerce hooks over custom complexity.
- Do not add advanced premium logic in free plugin.
- Update this file incrementally; do not rewrite from scratch.
- Plugin author string must be exactly: `Lukas Cech - www.nimble.help`.

## AI Working Instructions
- Always check `ROADMAP.md` and this file before coding.
- Keep work MVP-first: deliver smallest reliable implementation first.
- If user suggests new feature during MVP, add to backlog first; do not implement immediately unless explicitly prioritized.
- Use human-readable class, method, and variable names.
- Prefer modular classes with clear responsibilities.
- Use WordPress/WooCommerce hooks and APIs, avoid custom frameworks.
- For each completed task: update `Current Tasks`, `Completed Tasks`, and `Upcoming Tasks` incrementally.
- Keep commit scopes small and meaningful.

## Security Checklist (Minimum)
- Sanitize all incoming data (`sanitize_text_field`, `wc_clean`, etc.).
- Escape all output (`esc_html`, `esc_attr`, `esc_url`, etc.).
- Verify intent for admin actions with nonces and capability checks.
- Never trust `$_POST`/`$_GET` directly.
- Validate checkout required fields server-side.
- Ensure plugin failures do not break checkout page rendering.

## i18n and Language Rules
- Frontend labels and messages must be Slovak by default.
- Every user-facing string must use the plugin textdomain `woocommerce-sk-cz-funkcie`.
- Keep strings translatable with `__()`, `_e()`, `esc_html__()`, `esc_attr__()`.
- Store translation assets in `/woocommerce-sk-cz-funkcie/languages/`.
- Maintain `woocommerce-sk-cz-funkcie.pot` and locale files (at minimum `woocommerce-sk-cz-funkcie-cs_CZ.po` and compiled `.mo`).
- Do not hardcode Czech strings in PHP templates; use translations only.

## Definition of Done (Per Feature)
- Hooks execute in standard WooCommerce checkout only.
- Validation errors are clear and localized.
- Data is saved to correct WooCommerce order meta keys.
- Feature can be toggled on/off without side effects.
- No PHP warnings/notices in debug mode.
