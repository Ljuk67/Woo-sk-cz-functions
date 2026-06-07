# Repository Instructions

This file contains stable instructions for agents working in this repository.

Do not use this file as a scratchpad. Keep ongoing state in `memory/current-state.md` and private machine-specific notes in `.local/`.

## Startup Checklist

Before making changes:

- read `README.md`
- read `docs/roadmap.md`
- read `memory/current-state.md`
- inspect the relevant plugin root before changing code
- check for existing uncommitted changes and preserve unrelated work

## Repository Shape

- Repo root: project workspace for code, shared docs, and local notes
- Free plugin root: `woocommerce-sk-cz-functions/`
- Planned premium plugin root: `woocommerce-sk-cz-functions-premium/`
- Shared product planning: `docs/roadmap.md`
- Shared live state: `memory/current-state.md`
- Private local notes and machine-specific material: `.local/`

# Caveman Mode - simplify your output

## Core Rule
Respond like smart caveman. Cut articles, filler, pleasantries. Keep all technical
Default intensity: **full**. Change with `/caveman lite`, `/caveman full`

## Grammar
- Drop articles (a, an, the)
- Drop filler (just, really, basically, actually, simply)
- Drop pleasantries (sure, certainly, of course, happy to)
- Short synonyms (big not extensive, fix not "implement a solution for")
- No hedging (skip "it might be worth considering")
- Fragments fine. No need full sentence
- Technical terms stay exact. "polymorphism" stays "polymorphism"
- Code blocks unchanged. Caveman speak around code, not in code
- Error messages quoted exact. Caveman only for explanation

## Pattern
...
[thing] [action] [reason]. [next step].


## Product And Architecture Rules

- Keep free and premium code separate.
- Prioritize stable WooCommerce hooks over custom complexity.
- Do not add advanced premium logic in the free plugin.
- Keep work MVP-first: deliver the smallest reliable implementation first.
- If a new feature is suggested during MVP work, add it to backlog first unless explicitly reprioritized.
- Use human-readable class, method, and variable names.
- Prefer modular classes with clear responsibilities.
- Use WordPress and WooCommerce hooks and APIs; avoid custom frameworks.
- Keep commit scopes small and meaningful.
- Plugin author string must be exactly: `Nimble.help`.

## Block Compatibility Rules

- Always check whether a WooCommerce Blocks version or block-compatible API exists for any storefront feature you touch, including checkout, cart, and related customer-facing flows.
- Do not assume classic WooCommerce hooks automatically apply to block-based experiences.
- For customer-facing WooCommerce features, support both classic and block-based implementations whenever WooCommerce provides both surfaces.
- Treat checkout-facing features as compatibility work across two surfaces: classic shortcode checkout and Checkout Block.
- For checkout fields in the Checkout Block, prefer the WooCommerce additional checkout fields API instead of classic-only render hooks.
- Shared business logic should stay centralized, while classic integration and block integration may be implemented separately.
- If WooCommerce does not provide a viable block-compatible extension point for a feature, document that limitation clearly in code comments, docs, and settings/help text where relevant.

## State Management Rules

- Update `memory/current-state.md` incrementally; do not rewrite it from scratch.
- Keep `docs/roadmap.md` focused on medium-term and long-term direction.
- Keep `.local/` for private or temporary material that should not be committed.

## Security Checklist

- Sanitize all incoming data (`sanitize_text_field`, `wc_clean`, etc.).
- Escape all output (`esc_html`, `esc_attr`, `esc_url`, etc.).
- Verify intent for admin actions with nonces and capability checks.
- Never trust `$_POST` or `$_GET` directly.
- Validate checkout required fields server-side.
- Ensure plugin failures do not break checkout page rendering.

## i18n And Language Rules

- Frontend labels and messages must be Slovak by default.
- Every user-facing string must use the plugin textdomain `woocommerce-sk-cz-functions`.
- Keep strings translatable with `__()`, `_e()`, `esc_html__()`, and `esc_attr__()`.
- Store translation assets in `/woocommerce-sk-cz-functions/languages/`.
- Maintain `woocommerce-sk-cz-functions.pot` and locale files, at minimum `woocommerce-sk-cz-functions-cs_CZ.po` plus compiled `.mo`.
- Do not hardcode Czech strings in PHP templates; use translations only.

## Definition Of Done Per Feature

- Hooks execute in the intended WooCommerce flow without affecting unsupported flows.
- Customer-facing WooCommerce features have been checked for block-compatible integration points where applicable.
- Checkout-facing features support both classic checkout and Checkout Block.
- Validation errors are clear and localized.
- Data is saved to the correct WooCommerce order meta keys.
- Features can be toggled on and off without side effects.
- No PHP warnings or notices appear in debug mode.
