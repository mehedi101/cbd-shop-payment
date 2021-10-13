<?php
add_filter( 'woocommerce_default_address_fields', 'misha_remove_fields' );

function misha_remove_fields( $fields ) {

	$fields[ 'first_name' ]['class'] = ['address-field'];
	$fields[ 'last_name' ]['class'] = ['address-field'];
	return $fields;

}

add_action('woocommerce_checkout_update_order_review','cbd_add_customer_info',10,2);



function cbd_add_customer_info($post_data){ 
	if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
		return;
		}
	parse_str( $_POST['post_data']);
	WC()->customer->set_billing_first_name($billing_first_name);
	WC()->customer->set_billing_last_name($billing_last_name);


	
}
add_filter( 'woocommerce_payment_gateways_setting_columns', 'rudr_add_payment_method_column' );

function rudr_add_payment_method_column( $default_columns ) {

	$default_columns = array_slice( $default_columns, 0, 2 ) + array( 'id' => 'ID' ) + array_slice( $default_columns, 2, 3 );
	return $default_columns;

}

// woocommerce_payment_gateways_setting_column_{COLUMN ID}
add_action( 'woocommerce_payment_gateways_setting_column_id', 'rudr_populate_gateway_column' );

function rudr_populate_gateway_column( $gateway ) {

	echo '<td style="width:10%">' . $gateway->id . '</td>';

}

//add_filter( 'woocommerce_checkout_fields' , 'misha_labels_placeholders', 9999 );

function misha_labels_placeholders( $f ) {

	// first name can be changed with woocommerce_default_address_fields as well
	$f['billing']['billing_first_name']['label'] = 'Your mom calls you';
	$f['order']['order_comments']['placeholder'] = 'What\'s on your mind?';
	
	return $f;

}
add_filter( 'woocommerce_available_payment_gateways', 'rudr_gateway_by_country' );

function rudr_gateway_by_country( $gateways ) {
	
	if( is_admin()) {
		return $gateways;
	}
    
	$countries = ['AT','DE','SZ', 'BD'];
    
	if( is_wc_endpoint_url( 'order-pay' ) ) { // Pay for order page

		$order = wc_get_order( 
			wc_get_order_id_by_order_key( $_GET[ 'key' ] ) 
		);
		
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
		$hn = WC()->customer->get_billing_address_2();
		$pl =  WC()->customer->get_billing_city();
		$zp =  WC()->customer->get_billing_postcode();
		$em =  WC()->customer->get_billing_email();

	}

	if( is_checkout()){

	$opt =  get_option('cbd_shop_options');
	$uname = $opt['api_username'];
	$pass = $opt['api_pass'];
	$con = $opt['api_connection'];
	
	$params= [
        'us' => $uname,
        'pa' => $pass ,
        'fn' => $fn,
        'ln' => $ln,
		'co' => $country,
        'zp' => $zp,
        'pl' => $pl,
        'st' => $st,
		'hn' => $hn,
        'rd' => 's',
        'cd' => $con == 'test' ? 'MY': 'MC'

    ];
	
	

	if(!empty($opt) && !empty($country) && !empty($fn) && !empty($fn)&& !empty($ln)&& !empty($st)&& !empty($hn) && !empty($zp) && !empty($pl)){ 
		$url='https://portal.firstdebit.de/fdc/fc.php?';
		$url .= http_build_query($params);
	

	//	var_export($url);
		if( in_array($country, $countries) ){ 

			$response = wp_remote_get( esc_url_raw( $url ) );
			$api_response = wp_remote_retrieve_body( $response );
			//"id=16316552;rc=900"
			$ex = explode(";", $api_response);

			parse_str($ex[1]);
	
			wc_add_notice(__($api_response, 'cbd-shop'), 'success');
	
		if ( 400 >= $rc ) {
			if ( isset( $gateways[ 'cod' ] ) ) {
				unset( $gateways[ 'cod' ] );
	
				wc_add_notice(__('Cash ON DELIVERY gateway disabled', 'cbd-shop'), 'error');
			}
		}
	
		return $gateways;

		}
		


		}
	}
    /* $url='https://portal.firstdebit.de/fdc/fc.php?us=253274&pa=AqqzHxWY&fn=Friedrich&ln=Meier&zp=20354&pl=Hamburg&st=Poststr&hn=33&rd=s&cd=MY'; */

	return $gateways;
    

}
