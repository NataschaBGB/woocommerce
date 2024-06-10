<?php

	/* WooCommerce support
	------------------------------------------------------------------ */

	add_action( 'after_setup_theme', 'lb_add_woocommerce_support' );
	function lb_add_woocommerce_support() {
	    
	    add_theme_support( 'woocommerce' );

	    // Support for galleri in W3.0 and newer
	    add_theme_support( 'wc-product-gallery-zoom' );
	    add_theme_support( 'wc-product-gallery-lightbox' );
	    add_theme_support( 'wc-product-gallery-slider' );

	}


	/* WooCommerce admin notice
	------------------------------------------------------------------ */

	add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

	
	/* WooCommerce styling
	------------------------------------------------------------------ */

	add_filter( 'woocommerce_enqueue_styles', 'layback_dequeue_styles' );
	function layback_dequeue_styles( $enqueue_styles ) {
		unset( $enqueue_styles['woocommerce-general'] );
		unset( $enqueue_styles['woocommerce-layout'] );
		// unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
		return $enqueue_styles;
	}

	
	/* WooCommerce products per page
	------------------------------------------------------------------ */
	
	add_filter( 'loop_shop_per_page', 'lb_products_per_page', 20 );
	if(!function_exists('lb_products_per_page'))
	{
		function lb_products_per_page() {
			return 9;
		}
	}


	/* WooCommerce remove actions
	------------------------------------------------------------------ */
	
	// add_action( 'init', 'lb_remove_woocommerce_functions' );
	function lb_remove_woocommerce_functions()
	{

		/* General
		------------------------------------------------------------------ */

		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		

		/* WooCommerce archive
		------------------------------------------------------------------ */

		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20, 0 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30, 0 );

	}


	/* WooCommerce dynamic cart
	------------------------------------------------------------------ */

	// add_filter('add_to_cart_fragments', 'lb_woocommerce_header_add_to_cart_fragment');
	function lb_woocommerce_header_add_to_cart_fragment( $fragments )
	{
		ob_start();
		$cart_items = WC()->cart->get_cart_contents_count();
		echo '<a class="header-cart" href="' . get_permalink( wc_get_page_id( 'cart' ) ) . '">';
			echo '<i class="fas fa-shopping-cart"></i>';
			
			if($cart_items == 0){
				echo get_the_title(wc_get_page_id( 'cart' )); 
			}else{
				echo '<span class="cart-items">' . WC()->cart->get_total() . '</span>';
			}
		echo '</a>';
		
		$fragments['.header-cart'] = ob_get_clean();
		return $fragments;
	}


	/* WooCommerce checkout
	------------------------------------------------------------------ */

	// add_filter( 'woocommerce_checkout_fields' , 'lb_checkout_billing_fields', 20, 1 );
	function lb_checkout_billing_fields( $fields )
	{
		
		$domain = 'layback';
			
		$fields['billing']['billing_phone']['placeholder']		= __( 'Phone', $domain );
		$fields['billing']['billing_email']['placeholder']		= __( 'Email', $domain );
		$fields['billing']['billing_company']['placeholder']	= __( 'Company', $domain );

		// Change class
		$fields['billing']['billing_company']['class']			= array('form-row-wide');

		$fields['billing']['billing_postcode']['class']			= array('form-row-first');
		$fields['billing']['billing_city']['class']				= array('form-row-last');
		
		$fields['billing']['billing_phone']['class']			= array('form-row-first');
		$fields['billing']['billing_email']['class']			= array('form-row-last');

		return $fields;

	}


	/* WooCommerce custom functions
	------------------------------------------------------------------ */

	// prevent variation (in this case: size) to be added to cart if it has ID=15 (can be found on wp wc admin page - 'Products')
	add_filter( 'woocommerce_add_to_cart_validation', 'variation_check', 10, 5 );
    function variation_check( $passed, $product_id, $quantity, $variation_id = 0, $variations = null ) {
        if ( $variation_id == 15 ) {
            wc_add_notice( __( 'Sorry, you cannot add this product to the cart.', 'your-text-domain' ), 'error' );
            return false;
        }
        return $passed;
    }

	// when order status is changed to 'completed' in wc admin 'Orders' - send http request to $url
	add_action('woocommerce_order_status_completed', 'send_http_request');
    function send_http_request($order_id) {

		// get order details
		$order = wc_get_order($order_id);

		// create items array
		$product_details = array();
		// get and loop through order items
		foreach ($order->get_items() as $item) {
			$product_id 		= $item->get_product_id();
			$product_name 		= $item->get_name();
			$quantity      		= $item->get_quantity();
			$price 				= $item->get_total();

			// push into array and create new array for each item
			array_push($product_details, array(
				"product_id" 	=> $product_id,
				"product_name"	=> $product_name,
				"quantity" 		=> $quantity,
				"price" 		=> $price.' DKK'
			));
		};

		$order_details = array(
			// get customer info
			"customer_note" => $order->get_customer_note(),
			"customer_info" => array(
				"name" 		=> $order->get_billing_first_name().' '.$order->get_billing_last_name(),
				"email" 	=> $order->get_billing_email(),
				"phone" 	=> $order->get_billing_phone(),
				"address"	=> array(
					"street" 	=> $order->get_billing_address_1().', '.$order->get_billing_address_2(),
					"postal_code" 	=> $order->get_billing_postcode(),
					"city" 		=> $order->get_billing_city(),
					"state" 	=>$order->get_billing_state()
				)
			),
			// get shipping info
			"shipping_info" => array(
				"name" 		=> $order->get_shipping_first_name().' '.$order->get_shipping_last_name(),
				"address"	=> array(
					"street" 	=> $order->get_shipping_address_1().', '.$order->get_shipping_address_2(),
					"postal_code" 	=> $order->get_shipping_postcode(),
					"city" 		=> $order->get_shipping_city(),
					"state" 	=> $order->get_shipping_state()
				)
			),
			// items = each item array in product_details
			"order" => array(
				"order_id" 	=> $order_id,
				"items" 	=> $product_details,
				// get shipping and full order price
				"shipping" => $order->get_shipping_total() + $order->get_shipping_tax().' DKK',
				"order_total" 	=> $order->get_total().' DKK'
			),
		);

		// converte order_details array to json file
		$postdata = json_encode($order_details);

		// set up cURL to post json file
		$url = "https://webhook.site/8a5f628a-4ac7-4cd4-935e-2a0349c4c10d";

		// initialize curl session
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		// post the $postdata json file
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		// ignore ssl
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		
		// execute cURL session
		$response = curl_exec($curl);

		// if cURL session is not executed
		if(!$response){
			// tell what error and error number
			die("Error: " . curl_error($curl) . "- Code: " . curl_errno($curl));
		}

		// close cURL session
		curl_close($curl);
	}