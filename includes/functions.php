<?php
add_filter( 'woocommerce_checkout_fields' , 'misha_labels_placeholders', 9999 );

function misha_labels_placeholders( $f ) {

	// first name can be changed with woocommerce_default_address_fields as well
	$f['billing']['billing_first_name']['label'] = 'Your mom calls you';
	$f['order']['order_comments']['placeholder'] = 'What\'s on your mind?';
	
	return $f;

}
add_filter( 'woocommerce_available_payment_gateways', 'rudr_gateway_by_country' );

function rudr_gateway_by_country( $gateways ) {
	
	if( is_admin() ) {
		return $gateways;
	}
	
    $first_debit_url='https://portal.firstdebit.de/fdc/fc.php';
    $user_id = '253274';
    $password = 'AqqzHxWY';
    $first_debit_api_http_parameters= [
        'us' => '253274',
        'pa' => 'AqqzHxWY',
        'fn' => 'first_name',
        'ln' => 'last_name',
        'zp' => 'zip code',
        'pl' => 'place or city',
        'st' => 'street address with home number',
        'rd' => 's',
        'cd' => 'check depth', // MY or MC
        'hn' => 'house number', //

    ];
    
	if( is_wc_endpoint_url( 'order-pay' ) ) { // Pay for order page

		$order = wc_get_order( wc_get_order_id_by_order_key( $_GET[ 'key' ] ) );
		$country = $order->get_billing_country();
		$fn = $order->get_billing_first_name();
		$ln = $order->get_billing_last_name();
		$st = $order->get_billing_address_1();
		$hn = $order->get_billing_address_2();
		$pl = $order->get_billing_city();
		$zp = $order->get_billing_postcode();
		$em = $order->get_billing_email();
		

	} else { // Cart page

		$country = WC()->customer->get_billing_country();
        $fn =  WC()->customer->get_billing_first_name();
		$ln =  WC()->customer->get_billing_last_name();
		$st =  WC()->customer->get_billing_address_1();
		$pl =  WC()->customer->get_billing_city();
		$zp =  WC()->customer->get_billing_postcode();
		$em =  WC()->customer->get_billing_email();

	}

    $url='https://portal.firstdebit.de/fdc/fc.php?us=253274&pa=AqqzHxWY&fn=Friedrich&ln=Meier&zp=20354&pl=Hamburg&st=Poststr&hn=33&rd=s&cd=MY';


    $response = wp_remote_get( esc_url_raw( $url ) );
    $api_response = wp_remote_retrieve_body( $response );


    wc_add_notice(__($api_response, 'cbd-shop'), 'success');

	if ( 'BD' === $country ) {
		if ( isset( $gateways[ 'paypal' ] ) ) {
			unset( $gateways[ 'paypal' ] );

            wc_add_notice(__('Paypal gateway disabled', 'cbd-shop'), 'error');
		}
	}

	return $gateways;

}