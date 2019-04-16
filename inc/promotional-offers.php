<?php
/**
 * Promotional offers
 */

/**
 * Price shortcode for the "Register-Subscribe"
 */
function ba_price_shortcode( $attrs ) {
  $level = $attrs['level'];
  $prices = "";

  $currencies = array(
    'uk'  => '£',
    'eu'  => '€',
    'row' => '$',
  );
  while( have_rows( 'prices', 'options' ) ) {
    the_row();

    $subscript_id = get_sub_field( 'subscriptions' );
    if ( $subscript_id != $level ) continue;

    $currency = sanitize_title( get_sub_field( 'currency' ) );
    $web_price = floatval( get_sub_field( 'web_web_price' ) );
    $print_price = floatval( get_sub_field( 'web_print_price' ) );
    if ( $print_price > 0 ) {
      $prices .= sprintf(
        '<strong class="hidden subscription-price-label price-print-%d-%s">Price: %s%d</strong>',
        $subscript_id,
        $currency,
        $currencies[ $currency ],
        $print_price
      );
    }

    if ( $web_price > 0 ) {
      $prices .= sprintf(
        '<strong class="hidden subscription-price-label price-web-%d-%s">Price: %s%d</strong>',
        $subscript_id,
        $currency,
        $currencies[ $currency ],
        get_sub_field( 'web_web_price' )
      );
    }
  }

  // Is there any promotion code?
  $offer = ba_get_promotional_offer();
  if ( $offer && $offer->is_valid ) {
    while( have_rows( 'promotion', $offer->ID ) ) {
      the_row();

      $currency = strtolower( get_sub_field( 'currency' ) );
      $prices .= sprintf(
        '<strong class="hidden subscription-price-label subscription-offer offer-%s-%d-%s" data-type="%s" data-id="%d" data-currency="%s">Price: %s%d</strong>',
        get_sub_field( 'offer' ),
        get_sub_field( 'subscriptions' ),
        $currency,
        get_sub_field( 'offer' ),
        get_sub_field( 'subscriptions' ),
        $currency,
        $currencies[ $currency ],
        get_sub_field( 'price' )
      );
    }
  }
  return $prices;
}
add_shortcode( 'subscriptions', 'ba_price_shortcode' );

/**
 * Get all the available subscriptions from leaky-paywall to populate the
 * select
 *
 */
function ba_load_subscriptions( $field ) {
  $choices = array();

  $membership = Nine3_Simple_Membership::getInstance();
  $levels = $membership->get_levels();
  foreach ( $levels as $id => $level ) {
    $choices[ $id ] = $level['label'];
  }

  $field['choices'] = $choices;
  return $field;
}
add_filter('acf/load_field/name=subscriptions', 'ba_load_subscriptions');

/**
 * Get the price from the option page
 *
 * @param number $price default paywall price (used as fallback)
 */
function ba_get_payment_price( $price, $level_id ) {
  $currency = strtolower( $_GET['currency'] );
  $type = strtolower( $_GET['type'] );
  $promo = ba_get_promotional_offer();
  $has_promo = false;

  // Check that the options are the some of the offer one
  if ( $promo && $promo->is_valid ) {
    while( have_rows( 'promotion', $promo->ID ) ) {
      the_row();

      $promo_curr = strtolower( get_sub_field( 'currency' ) );
      $promo_type = strtolower( get_sub_field( 'offer' ) );
      $promo_id   = strtolower( get_sub_field( 'subscriptions' ) );

      if ( $promo_curr == $currency &&
        $promo_type == $type &&
        $promo_id == $level_id ) {

        return get_sub_field( 'price' );
      }
    }
  }

  while( have_rows( 'prices', 'options' ) ) {
    the_row();

    $subscript_id = get_sub_field( 'subscriptions' );
    if ( $subscript_id != $level_id ) continue;

    $c = strtolower( get_sub_field( 'currency' ) );
    if ( $c === $currency ) {
      $p = floatval( get_sub_field( "web_${type}_price" ) );
      if ( $p > 0 ) $price = $p;

      break;
    }
  }

  return $price;
}

/**
 * Change the defailts for the PayPal payment, according to the choices
 * made from the subscribe form
 */
function ba_paypal_payment_data( $payment_options, $level, $level_id ) {
  if ( ! isset( $_GET['currency'] ) || ! isset( $_GET['type'] ) ) {
    return $payment_options;
  }

  $currencies = array(
    'uk' => 'GBP',
    'eu' => 'EUR',
    'row' => 'USD',
  );

	$results = '';
	$settings = get_leaky_paywall_settings();
	$mode = 'off' === $settings['test_mode'] ? 'live' : 'test';
	$paypal_sandbox = 'off' === $settings['test_mode'] ? '' : 'sandbox';
	$paypal_account = 'on' === $settings['test_mode'] ? $settings['paypal_sand_email'] : $settings['paypal_live_email'];
	$currency = $settings['leaky_paywall_currency'];
  if ( isset( $_GET['currency'] ) ) {
    $c = esc_attr( strtolower( $_GET['currency'] ) );
    $currency = isset( $currencies[ $c ] ) ? $currencies[ $c ] : $currency;
  }
	$current_user = wp_get_current_user();
  $price = ba_get_payment_price( $level['price'], $level_id );

	if ( 0 !== $current_user->ID ) {
		$user_email = $current_user->user_email;
	} else {
		$user_email = 'no_lp_email_set';
	}
	if ( !empty( $level['recurring'] ) && 'on' === $level['recurring'] ) {

		$results .= '<script src="' . LEAKY_PAYWALL_URL . '/js/paypal-button.min.js?merchant=' . esc_js( $paypal_account ) . '"
						data-env="' . esc_js( $paypal_sandbox ) . '"
						data-callback="' . esc_js( add_query_arg( 'listener', 'IPN', get_site_url() . '/' ) ) . '"
						data-return="' . esc_js( add_query_arg( 'leaky-paywall-confirm', 'paypal_standard', get_page_link( $settings['page_for_after_subscribe'] ) ) ) . '"
						data-cancel_return="' . esc_js( add_query_arg( 'leaky-paywall-paypal-standard-cancel-return', '1', get_page_link( $settings['page_for_profile'] ) ) ) . '"
						data-src="1"
						data-period="' . esc_js( strtoupper( substr( $level['interval'], 0, 1 ) ) ) . '"
						data-recurrence="' . esc_js( $level['interval_count'] ) . '"
						data-currency="' . esc_js( apply_filters( 'leaky_paywall_paypal_currency', $currency ) ) . '"
						data-amount="' . esc_js( $price ) . '"
						data-name="' . esc_js( $level['label'] ) . '"
						data-number="' . esc_js( $level_id ) . '"
						data-button="subscribe"
						data-no_note="1"
						data-no_shipping="1"
						data-custom="' . esc_js( $user_email ) . '"
					></script>';

	} else {

		$results .= '<script src="' . LEAKY_PAYWALL_URL . '/js/paypal-button.min.js?merchant=' . esc_js( $paypal_account ) . '"
						data-env="' . esc_js( $paypal_sandbox ) . '"
						data-callback="' . esc_js( add_query_arg( 'listener', 'IPN', get_site_url() . '/' ) ) . '"
						data-return="' . esc_js( add_query_arg( 'leaky-paywall-confirm', 'paypal_standard', get_page_link( $settings['page_for_after_subscribe'] ) ) ) . '"
						data-cancel_return="' . esc_js( add_query_arg( 'leaky-paywall-paypal-standard-cancel-return', '1', get_page_link( $settings['page_for_profile'] ) ) ) . '"
						data-tax="0"
						data-shipping="0"
						data-currency="' . esc_js( apply_filters( 'leaky_paywall_paypal_currency', $currency ) ) . '"
						data-amount="' . esc_js( $price ) . '"
						data-quantity="1"
						data-name="' . esc_js( $level['label'] ) . '"
						data-number="' . esc_js( $level_id ) . '"
						data-button="buynow"
						data-no_note="1"
						data-no_shipping="1"
						data-shipping="0"
						data-custom="' . esc_js( $user_email ) . '"
					></script>';
	}

	if ( empty( $paypal_account ) ) {
		$results = 'Please enter your Paypal credentials in the Leaky Paywall settings.';
	}

	return '<div class="leaky-paywall-paypal-standard-button leaky-paywall-payment-button">' . $results . '</div>';
}
add_filter('leaky_paywall_subscription_options_payment_options', 'ba_paypal_payment_data', 99, 3 );

/**
 * Add the custom permalink for the offer.
 *
 * Need to generate an unique random id for each offer.
 */
function ba_generate_offer_id( $post ) {
  if ( $post->post_type != 'offers' ) return;

  $id = $post->ID;

  $id = get_field( 'unique_id', $post->ID );
  if ( empty( $id ) )  {
    $id = uniqid( 'bo' );
  }
  $url = get_field( 'offers_landing_page', 'option' );
  $url = add_query_arg( 'promo-code', $id, $url );
  update_post_meta( $post->ID, 'unique_id', $id );

  printf( '<div style="margin-top: 5px"><strong>Promo link:</strong> <a href="%s" target="_blank">%s</a></div>', $url, $url );
}
add_action( 'edit_form_before_permalink', 'ba_generate_offer_id' );

/**
 * Get all the info about the offer by using the "promo-code" parameter.
 *
 * Used from the front-end
 */
function ba_get_promotional_offer() {
  $promo_id = isset( $_GET['promo-code'] ) ? $_GET['promo-code'] : '';
  $promo = null;
  if ( ! ( empty ( $promo_id ) ) ) {
    $post = get_posts( array(
      'post_type' => 'offers',
      'meta_key'  => 'unique_id',
      'meta_value' => $promo_id,
      'posts_per_page' => 1,
    ) );

    if ( $post ) {
      $promo = $post[0];
      // 3rd parameter set to true in order to get the data in RAW format (YYYYMMDD)
      $start_date = get_field( 'start_date', $promo->ID, true );
      $end_date = get_field( 'end_date', $promo->ID, true );
      $today = date( 'Ymd', mktime() );
      $promo->is_valid = $today >= $start_date && $today <= $end_date;
    }

    wp_reset_postdata();
  }

  return $promo;
}

/**
 * Populate the promo code value for the hidden field
 */
add_filter( 'gform_field_value_promo-code', 'ba_gf_promo_code' );
function ba_gf_promo_code( $value ) {
  return isset( $_GET['promo-code'] ) ? $_GET['promo-code'] : '';
}
