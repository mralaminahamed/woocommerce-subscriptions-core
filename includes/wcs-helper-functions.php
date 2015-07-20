<?php
/**
 * WooCommerce Subscriptions Helper Functions
 *
 * @author 		Prospress
 * @category 	Core
 * @package 	WooCommerce Subscriptions/Functions
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Display date/time input fields
 *
 * @param int (optional) A timestamp for a certain date in the site's timezome. If left empty, or 0, it will be set to today's date.
 * @param array $args A set of name => value pairs to customise the input fields
 *		'id_attr': (string) the date to display in the selector in MySQL format ('Y-m-d H:i:s'). Required.
 *		'date': (string) the date to display in the selector in MySQL format ('Y-m-d H:i:s'). Required.
 *		'tab_index': (int) the tab index for the element. Optional. Default 0.
 *		'include_time': (bool) whether to include a specific time for the selector. Default true.
 *		'include_year': (bool) whether to include a the year field. Default true.
 *		'include_buttons': (bool) whether to include submit buttons on the selector. Default true.
 * @since 2.0
 */
function wcs_date_input( $timestamp = 0, $args = array() ) {

	$args = wp_parse_args( $args, array(
			'name_attr'         => '',
			'include_time'      => true,
		)
	);

	$date       = ( 0 !== $timestamp ) ? date_i18n( 'Y-m-d', $timestamp ) : '';
	/* translators: date placeholder for input, javascript format */
	$date_input = '<input type="text" class="date-picker woocommerce-subscriptions" placeholder="' . esc_attr__( 'YYYY-MM-DD', 'woocommerce-subscriptions' ) . '" name="' . esc_attr( $args['name_attr'] ) . '" id="' . esc_attr( $args['name_attr'] ) . '" maxlength="10" value="' . esc_attr( $date ) . '" pattern="([0-9]{4})-(0[1-9]|1[012])-(##|0[1-9#]|1[0-9]|2[0-9]|3[01])"/>';

	if ( true === $args['include_time'] ) {
		$hours        = ( 0 !== $timestamp ) ? date_i18n( 'H', $timestamp ) : '';
		/* translators: hour placeholder for time input, javascript format */
		$hour_input   = '<input type="text" class="hour" placeholder="' . esc_attr__( 'HH', 'woocommerce-subscriptions' ) . '" name="' . esc_attr( $args['name_attr'] ) . '_hour" id="' . esc_attr( $args['name_attr'] ) . '_hour" value="' . esc_attr( $hours ) . '" maxlength="2" size="2" pattern="([01]?[0-9]{1}|2[0-3]{1})" />';
		$minutes      = ( 0 !== $timestamp ) ? date_i18n( 'i', $timestamp ) : '';
		/* translators: minute placeholder for time input, javascript format */
		$minute_input = '<input type="text" class="minute" placeholder="' . esc_attr__( 'MM', 'woocommerce-subscriptions' ) . '" name="' . esc_attr( $args['name_attr'] ) . '_minute" id="' . esc_attr( $args['name_attr'] ) . '_minute" value="' . esc_attr( $minutes ) . '" maxlength="2" size="2" pattern="[0-5]{1}[0-9]{1}" />';
		$date_input   = sprintf( '%s@%s:%s', $date_input, $hour_input, $minute_input );
	}

	$timestamp_utc = ( 0 !== $timestamp ) ? $timestamp - get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS : $timestamp;
	$date_input    = '<div class="wcs-date-input">' . $date_input . '</div>';

	return apply_filters( 'woocommerce_subscriptions_date_input', $date_input, $timestamp, $args );
}

/**
 * Get the edit post link without checking if the user can edit that post or not.
 *
 * @param int $post_id
 * @since 2.0
 */
function wcs_get_edit_post_link( $post_id ) {
	$post_type_object = get_post_type_object( get_post_type( $post_id ) );

	if ( ! $post_type_object || ! in_array( $post_type_object->name, array( 'shop_order', 'shop_subscription' ) ) ) {
		return;
	}

	return apply_filters( 'get_edit_post_link', admin_url( sprintf( $post_type_object->_edit_link . '&action=edit', $post_id ) ),$post_id, '' );
}
