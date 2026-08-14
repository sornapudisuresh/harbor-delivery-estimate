<?php
/**
 * Settings under WooCommerce → Settings → Products.
 *
 * @package Harbor_Delivery_Estimate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders Harbor Delivery Estimate settings.
 */
class HDE_Settings {

	/**
	 * Hook settings into WooCommerce.
	 */
	public static function init() {
		add_filter( 'woocommerce_get_sections_products', array( __CLASS__, 'add_section' ) );
		add_filter( 'woocommerce_get_settings_products', array( __CLASS__, 'get_settings' ), 10, 2 );
	}

	/**
	 * Add a Products settings subsection.
	 *
	 * @param array<string, string> $sections Existing sections.
	 * @return array<string, string>
	 */
	public static function add_section( $sections ) {
		$sections['harbor_delivery'] = __( 'Delivery estimate', 'harbor-delivery-estimate' );
		return $sections;
	}

	/**
	 * Settings fields for the subsection.
	 *
	 * @param array<int, array<string, mixed>> $settings Existing settings.
	 * @param string                           $current_section Current section id.
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_settings( $settings, $current_section ) {
		if ( 'harbor_delivery' !== $current_section ) {
			return $settings;
		}

		return array(
			array(
				'title' => __( 'Harbor Delivery Estimate', 'harbor-delivery-estimate' ),
				'type'  => 'title',
				'desc'  => __( 'Show customers a realistic delivery window based on business days and your daily cutoff.', 'harbor-delivery-estimate' ),
				'id'    => 'hde_settings_title',
			),
			array(
				'title'   => __( 'Enable estimates', 'harbor-delivery-estimate' ),
				'id'      => 'hde_enabled',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'             => __( 'Minimum business days', 'harbor-delivery-estimate' ),
				'id'                => 'hde_min_days',
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => 0,
					'step' => 1,
				),
				'default'           => 2,
			),
			array(
				'title'             => __( 'Maximum business days', 'harbor-delivery-estimate' ),
				'id'                => 'hde_max_days',
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => 1,
					'step' => 1,
				),
				'default'           => 5,
			),
			array(
				'title'             => __( 'Same-day cutoff hour (24h, site timezone)', 'harbor-delivery-estimate' ),
				'desc'              => __( 'Orders after this hour start counting from the next business day.', 'harbor-delivery-estimate' ),
				'id'                => 'hde_cutoff_hour',
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => 0,
					'max'  => 23,
					'step' => 1,
				),
				'default'           => 14,
				'desc_tip'          => true,
			),
			array(
				'title'   => __( 'Label', 'harbor-delivery-estimate' ),
				'id'      => 'hde_label',
				'type'    => 'text',
				'default' => __( 'Estimated delivery', 'harbor-delivery-estimate' ),
			),
			array(
				'title'   => __( 'Show on product pages', 'harbor-delivery-estimate' ),
				'id'      => 'hde_show_on_product',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Show on cart', 'harbor-delivery-estimate' ),
				'id'      => 'hde_show_on_cart',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Show on checkout', 'harbor-delivery-estimate' ),
				'id'      => 'hde_show_on_checkout',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'hde_settings_title',
			),
		);
	}
}
