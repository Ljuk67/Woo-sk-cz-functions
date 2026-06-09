<?php
/**
 * Remove additional information product tab.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Remove_Additional_Information_Tab {

	/**
	 * Settings service.
	 *
	 * @var WSCF_Settings
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param WSCF_Settings $settings Settings service.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'woocommerce_product_tabs', array( $this, 'filter_product_tabs' ), 100, 1 );

		if ( $this->settings->is_setting_enabled( 'move_additional_information_to_description' ) ) {
			add_filter( 'the_content', array( $this, 'append_additional_information_to_description' ), 20 );
		}
	}

	/**
	 * Remove "Additional information" tab from single product page.
	 *
	 * @param array<string, array<string, mixed>> $tabs Product tabs array.
	 * @return array<string, array<string, mixed>>
	 */
	public function filter_product_tabs( $tabs ) {
		if ( isset( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information'] );
		}

		return $tabs;
	}

	/**
	 * Append moved Additional Information content to product description.
	 *
	 * @param string $content Product content.
	 * @return string
	 */
	public function append_additional_information_to_description( $content ) {
		if ( is_admin() || ! function_exists( 'is_product' ) || ! is_product() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$product = wc_get_product( get_the_ID() );

		if ( ! $product instanceof WC_Product ) {
			return $content;
		}

		$additional_information = $this->get_additional_information_content( $product );

		if ( '' === $additional_information ) {
			return $content;
		}

		return $content . $additional_information;
	}

	/**
	 * Build Additional Information markup for the description area.
	 *
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	private function get_additional_information_content( $product ) {
		if ( ! function_exists( 'wc_display_product_attributes' ) ) {
			return '';
		}

		ob_start();
		wc_display_product_attributes( $product );
		$attributes_html = trim( (string) ob_get_clean() );

		if ( '' === $attributes_html ) {
			return '';
		}

		return sprintf(
			'<section class="wscf-additional-information-content"><h2>%1$s</h2>%2$s</section>',
			esc_html__( 'Additional information', 'nimble-woo-sk-cz-functions' ),
			$attributes_html
		);
	}
}
