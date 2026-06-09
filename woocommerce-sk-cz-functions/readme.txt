=== WooCommerce SK/CZ Functions ===
Contributors: ljuk67
Tags: woocommerce, checkout, slovakia, czechia, gdpr
Requires at least: 6.4
Tested up to: 6.8
Requires PHP: 7.4
Requires Plugins: woocommerce
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce tools for Slovak and Czech stores: company fields, checkout button text, COD fee, GDPR consent, shipping, and catalog helpers.

== Description ==

WooCommerce SK/CZ Functions adds practical WooCommerce features commonly needed in Slovak and Czech stores.

Current features:

* Company checkout toggle with ICO, DIC, IC DPH, and billing company support.
* Custom checkout order button text with Slovak and Czech defaults.
* Classic checkout GDPR consent checkbox with privacy policy link support.
* Fixed Cash on Delivery fee for the WooCommerce `cod` payment gateway.
* Hide paid shipping methods when free shipping is available.
* Remove the Additional Information product tab, with an option to move its content into the product description.
* Product subcategories row above products on category archive pages.

Most feature toggles are disabled by default. Enable only the features you need in WooCommerce settings. The checkout button text field is available immediately and defaults to the translated Slovak/Czech payment-obligation wording unless you save custom text.

== Compatibility ==

Checkout-facing features were built for both classic checkout and Checkout Block where WooCommerce provides a stable block-compatible API. Company checkout fields, custom checkout button text, and the fixed COD fee support both checkout experiences.

The GDPR checkbox is intentionally limited to classic checkout. In Checkout Block, WooCommerce combines legal consent with its terms and conditions flow.

The product subcategories row is designed for standard WooCommerce category archive templates and hooks. Custom blocks and custom archive templates use their own structure and are outside this feature's scope.

The Additional Information tab feature uses WooCommerce's product tabs API and applies to standard WooCommerce single product templates.

Tested themes before the 0.1.0 release:

* Astra
* Blocksy
* GeneratePress
* Kadence
* OceanWP
* Storefront
* Twenty Twenty-Five / current bundled WordPress block theme

== Installation ==

1. Upload the `woocommerce-sk-cz-functions` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Ensure WooCommerce is installed and active.
4. Go to WooCommerce > Settings > WooCommerce SK/CZ.
5. Enable the features your store needs and save changes.

== Frequently Asked Questions ==

= Does this plugin require WooCommerce? =

Yes. WooCommerce must be installed and active.

= Is this plugin intended for SK/CZ stores only? =

It is primarily focused on Slovak and Czech store requirements, but parts may be useful elsewhere.

= Does it support Checkout Block? =

Company checkout fields, custom checkout button text, and the fixed COD fee support classic checkout and Checkout Block. The GDPR checkbox is intentionally added only to classic checkout; block checkout uses WooCommerce's combined legal consent flow.

= Can I change the checkout order button text? =

Yes. Go to WooCommerce > Settings > WooCommerce SK/CZ and edit "Checkout button text". Leave the predefined text unchanged to use the translated default for the current site language. Clear the field and save to return to the translated default after saving custom text.

= Does the plugin connect to external services? =

No. The plugin does not send store data to external services and does not load remote scripts or styles.

= Where is development hosted? =

Public development is hosted at https://github.com/Ljuk67/Woo-sk-cz-functions.

== Changelog ==

= 0.1.0 =

* Initial release.
* Company checkout fields.
* Custom checkout order button text with Slovak and Czech translated defaults.
* GDPR checkbox.
* Category subcategory row.
* Hide paid shipping when free shipping is available.
* Remove or move Additional Information product tab content.
* Fixed COD fee for the WooCommerce Cash on Delivery gateway.
