=== Nimble Woo SK/CZ Functions ===
Contributors: lukascech
Tags: woocommerce, checkout, nakup na firmu, company fields, gdpr, woo sk, woo cz
Requires at least: 6.4
Tested up to: 7.0
Requires PHP: 7.4
Requires Plugins: woocommerce
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce tools for Slovak and Czech stores: company fields, checkout button text, COD fee, GDPR consent, shipping, and catalog helpers.

== Description ==

Nimble Woo SK/CZ Functions adds practical WooCommerce features commonly needed in (not only) Slovak and Czech stores.

Current features:

* Company checkout toggle with ICO, DIC, IC DPH, and billing company support.
* Custom checkout order button text with Slovak and Czech defaults (Objednať s povinnosťou platby / Objednat s povinností platby)
* Classic checkout GDPR consent checkbox with privacy policy link support.
* Fixed Cash on Delivery (Platba na dobierku) fee for the WooCommerce `cod` payment gateway.
* Hide paid shipping methods when free shipping is available.
* Hide the Additional Information product tab, with an option to move its content into the product description.
* Product subcategories row above products on category archive pages.

Most feature toggles are disabled by default. Enable only the features you need in WooCommerce settings. The checkout button text field is available immediately and defaults to the translated Slovak/Czech payment-obligation wording unless you save custom text.

== Compatibility ==

Checkout-facing features were built for both classic checkout and Checkout Block where WooCommerce provides a stable block-compatible API. Company checkout fields, custom checkout button text, and the fixed COD fee support both checkout experiences.

The GDPR checkbox is intentionally limited to classic checkout. In Checkout Block, WooCommerce combines legal consent with its terms and conditions flow.

The product subcategories row is designed for standard WooCommerce category archive templates and hooks. Custom blocks and custom archive templates use their own structure and are outside this feature's scope.

The Additional Information tab feature uses WooCommerce's product tabs API and applies to standard WooCommerce single product templates.

Tested themes before the 1.0.0 release:

* Astra
* Blocksy
* GeneratePress
* Kadence
* OceanWP
* Storefront
* Twenty Twenty-Five / current bundled WordPress block theme

== Installation ==

1. Upload the `nimble-woo-sk-cz-functions` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Ensure WooCommerce is installed and active.
4. Go to WooCommerce > Settings > Nimble Woo SK/CZ tab.
5. Enable the features your store needs and save changes.

== Frequently Asked Questions ==

= Does this plugin require WooCommerce? =

Yes. WooCommerce must be installed and active.

= Is this plugin intended for SK/CZ stores only? =

It is primarily focused on Slovak and Czech store requirements, but some functions are generic for any store in any language.

= Does it support Checkout Block? =

Company checkout fields, custom checkout button text, and the fixed COD fee support classic checkout and Checkout Block. The GDPR checkbox is intentionally added only to classic checkout; block checkout uses WooCommerce's combined legal consent flow.

= Can I change the checkout order button text? =

Yes. Go to WooCommerce > Settings > Nimble Woo SK/CZ and edit "Checkout button text". Leave the predefined text unchanged to use the translated default for the current site language. Clear the field and save to return to the translated default after saving custom text.

= Does the plugin connect to external services? =

No. The plugin does not send store data to external services and does not load remote scripts or styles.

= Where is development hosted? =

Public development is hosted at https://github.com/Ljuk67/Woo-sk-cz-functions.

= How can I support development? =

You can support development at [GitHub Sponsors](https://github.com/sponsors/Ljuk67).

= Is it translatable? =

Yes, you can easily translate it to any language using Loco Translate.

== Screenshots ==

1. Plugin settings.
2. Product subcategories row above products on category archive pages.
3. Block checkout Company fields & Cash on delivery.
4. Additional information tab moved to main product description.

== Changelog ==

= 1.0.0 =

* Initial release.
* Company checkout fields.
* Custom checkout order button text with Slovak and Czech translated defaults.
* GDPR checkbox.
* Category subcategory row.
* Hide paid shipping when free shipping is available.
* Remove or move Additional Information product tab content.
* Fixed COD fee for the WooCommerce Cash on Delivery gateway.

== Upgrade Notice ==

= 1.0.0 =

Initial public release. No upgrade steps are needed.
