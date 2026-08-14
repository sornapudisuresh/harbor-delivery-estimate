<?php
/**
 * Front-end delivery estimate rendering.
 *
 * @package Harbor_Delivery_Estimate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculates and prints delivery estimate notices.
 */
class HDE_Display {

	/**
	 * Register hooks and assets.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'render_on_product' ), 25 );
		add_action( 'woocommerce_before_cart_table', array( __CLASS__, 'render_on_cart' ) );
		add_action( 'woocommerce_before_checkout_form', array( __CLASS__, 'render_on_checkout' ), 5 );
	}

	/**
	 * Front-end styles.
	 */
	public static function enqueue_assets() {
		if ( ! self::should_load_assets() ) {
			return;
		}

		wp_enqueue_style(
			'harbor-delivery-estimate',
			HDE_PLUGIN_URL . 'assets/css/harbor-delivery-estimate.css',
			array(),
			HDE_VERSION
		);
	}

	/**
	 * Whether current request can show an estimate.
	 *
	 * @return bool
	 */
	private static function should_load_assets() {
		$options = hde_get_options();
		if ( 'yes' !== $options['enabled'] ) {
			return false;
		}

		return is_product() || is_cart() || is_checkout();
	}

	/**
	 * Product page output.
	 */
	public static function render_on_product() {
		$options = hde_get_options();
		if ( 'yes' !== $options['enabled'] || 'yes' !== $options['show_on_product'] ) {
			return;
		}

		self::render_notice();
	}

	/**
	 * Cart output.
	 */
	public static function render_on_cart() {
		$options = hde_get_options();
		if ( 'yes' !== $options['enabled'] || 'yes' !== $options['show_on_cart'] ) {
			return;
		}

		self::render_notice();
	}

	/**
	 * Checkout output.
	 */
	public static function render_on_checkout() {
		$options = hde_get_options();
		if ( 'yes' !== $options['enabled'] || 'yes' !== $options['show_on_checkout'] ) {
			return;
		}

		self::render_notice();
	}

	/**
	 * Print the estimate markup.
	 */
	private static function render_notice() {
		$window = self::get_delivery_window();
		if ( empty( $window ) ) {
			return;
		}

		$options = hde_get_options();
		$label   = $options['label'];

		printf(
			'<div class="hde-estimate" role="status"><span class="hde-estimate__label">%1$s</span> <span class="hde-estimate__dates">%2$s</span></div>',
			esc_html( $label ),
			esc_html( $window )
		);
	}

	/**
	 * Build a human-readable delivery window.
	 *
	 * @return string
	 */
	public static function get_delivery_window() {
		$options  = hde_get_options();
		$min_days = max( 0, absint( $options['min_days'] ) );
		$max_days = max( $min_days, absint( $options['max_days'] ) );
		$cutoff   = min( 23, absint( $options['cutoff_hour'] ) );

		$timezone = wp_timezone();
		$start    = new DateTimeImmutable( 'now', $timezone );

		if ( (int) $start->format( 'G' ) >= $cutoff ) {
			$start = $start->modify( '+1 day' );
		}

		$start = self::next_business_day( $start );
		$min   = self::add_business_days( $start, $min_days );
		$max   = self::add_business_days( $start, $max_days );

		$format = get_option( 'date_format' );

		if ( $min->format( 'Y-m-d' ) === $max->format( 'Y-m-d' ) ) {
			return wp_date( $format, $min->getTimestamp(), $timezone );
		}

		return sprintf(
			/* translators: 1: earliest delivery date, 2: latest delivery date */
			__( '%1$s – %2$s', 'harbor-delivery-estimate' ),
			wp_date( $format, $min->getTimestamp(), $timezone ),
			wp_date( $format, $max->getTimestamp(), $timezone )
		);
	}

	/**
	 * Move forward until the date is a weekday.
	 *
	 * @param DateTimeImmutable $date Starting date.
	 * @return DateTimeImmutable
	 */
	private static function next_business_day( DateTimeImmutable $date ) {
		while ( in_array( (int) $date->format( 'N' ), array( 6, 7 ), true ) ) {
			$date = $date->modify( '+1 day' );
		}

		return $date;
	}

	/**
	 * Add N business days to a date.
	 *
	 * @param DateTimeImmutable $date Starting date.
	 * @param int               $days Business days to add.
	 * @return DateTimeImmutable
	 */
	private static function add_business_days( DateTimeImmutable $date, $days ) {
		$days = absint( $days );
		while ( $days > 0 ) {
			$date = $date->modify( '+1 day' );
			if ( ! in_array( (int) $date->format( 'N' ), array( 6, 7 ), true ) ) {
				--$days;
			}
		}

		return $date;
	}
}
