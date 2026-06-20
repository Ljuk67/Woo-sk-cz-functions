<?php
/**
 * Category row feature for product category archives.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Category_Row {

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'woocommerce_before_shop_loop', array( $this, 'render_child_categories_row' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Enqueue frontend styles for the category row feature.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		$style_path = WSCF_PLUGIN_PATH . 'assets/css/category-row.css';

		if ( ! function_exists( 'is_product_category' ) || ! is_product_category() || ! file_exists( $style_path ) ) {
			return;
		}

		wp_enqueue_style(
			'wscf-category-row',
			WSCF_PLUGIN_URL . 'assets/css/category-row.css',
			array(),
			(string) filemtime( $style_path )
		);
	}

	/**
	 * Render child categories above products on product category archives.
	 *
	 * This currently targets the classic WooCommerce archive loop hook.
	 * Block-based archive/product collection layouts may need a separate
	 * integration surface when WooCommerce exposes a stable one.
	 *
	 * @return void
	 */
	public function render_child_categories_row() {
		if ( ! function_exists( 'is_product_category' ) || ! is_product_category() ) {
			return;
		}

		$current_category = get_queried_object();

		if ( ! $current_category instanceof WP_Term || 'product_cat' !== $current_category->taxonomy ) {
			return;
		}

		$categories = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'orderby'    => 'name',
				'hide_empty' => true,
				'parent'     => (int) $current_category->term_id,
			)
		);

		if ( is_wp_error( $categories ) || empty( $categories ) ) {
			return;
		}

		echo '<div class="wscf-category-row">';

		foreach ( $categories as $category ) {
			$category_link = get_term_link( $category );

			if ( is_wp_error( $category_link ) ) {
				continue;
			}

			echo '<div class="wscf-category-row__item">';
			echo '<a class="wscf-category-row__link" href="' . esc_url( $category_link ) . '">';
			echo '<span class="wscf-category-row__name">' . esc_html( $category->name ) . '</span>';
			echo '</a>';
			echo '</div>';
		}

		echo '</div>';
	}
}
