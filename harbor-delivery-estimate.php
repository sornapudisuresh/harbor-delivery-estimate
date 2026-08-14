<?php
/**
 * Plugin Name: Harbor Delivery Estimate
 * Plugin URI: https://github.com/sornapudisuresh/harbor-delivery-estimate
 * Description: Shows a configurable estimated delivery window on WooCommerce product pages, cart, and checkout.
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Author: Sornapudi Suresh
 * Author URI: https://github.com/sornapudisuresh
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: harbor-delivery-estimate
 * Requires Plugins: woocommerce
 *
 * @package Harbor_Delivery_Estimate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HDE_VERSION', '1.0.0' );
define( 'HDE_PLUGIN_FILE', __FILE__ );
define( 'HDE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HDE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Bootstrap after plugins load so WooCommerce APIs are available.
 */
function hde_bootstrap() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'hde_woocommerce_missing_notice' );
		return;
	}

	require_once HDE_PLUGIN_DIR . 'includes/class-hde-settings.php';
	require_once HDE_PLUGIN_DIR . 'includes/class-hde-display.php';

	HDE_Settings::init();
	HDE_Display::init();
}
add_action( 'plugins_loaded', 'hde_bootstrap' );

/**
 * Admin notice when WooCommerce is inactive.
 */
function hde_woocommerce_missing_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p>';
	echo esc_html__( 'Harbor Delivery Estimate requires WooCommerce to be installed and active.', 'harbor-delivery-estimate' );
	echo '</p></div>';
}

/**
 * Default options.
 *
 * @return array<string, mixed>
 */
function hde_default_options() {
	return array(
		'enabled'          => 'yes',
		'min_days'         => 2,
		'max_days'         => 5,
		'cutoff_hour'      => 14,
		'label'            => __( 'Estimated delivery', 'harbor-delivery-estimate' ),
		'show_on_product'  => 'yes',
		'show_on_cart'     => 'yes',
		'show_on_checkout' => 'yes',
	);
}

/**
 * Get merged plugin options from flat WooCommerce settings keys.
 *
 * @return array<string, mixed>
 */
function hde_get_options() {
	$defaults = hde_default_options();

	return array(
		'enabled'          => get_option( 'hde_enabled', $defaults['enabled'] ),
		'min_days'         => get_option( 'hde_min_days', $defaults['min_days'] ),
		'max_days'         => get_option( 'hde_max_days', $defaults['max_days'] ),
		'cutoff_hour'      => get_option( 'hde_cutoff_hour', $defaults['cutoff_hour'] ),
		'label'            => get_option( 'hde_label', $defaults['label'] ),
		'show_on_product'  => get_option( 'hde_show_on_product', $defaults['show_on_product'] ),
		'show_on_cart'     => get_option( 'hde_show_on_cart', $defaults['show_on_cart'] ),
		'show_on_checkout' => get_option( 'hde_show_on_checkout', $defaults['show_on_checkout'] ),
	);
}
