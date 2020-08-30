<?php
	/**
	 * Setup Traveling Store Theme.
	 */

	//  Theme setup
	if ( ! function_exists( 'traveling_store_setup' ) ) :

		function traveling_store_setup() {
			// Add theme support for Custom Logo.
			add_theme_support( 'custom-logo' );
			add_theme_support( 'post-thumbnails' );

			add_theme_support( 'menus' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'post-thumbnails' );

			// Add theme support for Menu Navigation.
			register_nav_menu( 'header-nav', 'Main header navigation' );
//			register_nav_menu( 'sidebar-nav', 'Categories sidebar navigation' );
			register_nav_menu( 'footer-nav', 'Main footer navigation' );
			register_nav_menu( 'contacts-footer-nav', 'Contacts footer navigation' );
			register_nav_menu( 'social-footer-nav', 'Social footer navigation' );

			//  woocommerce setup
			add_theme_support( 'woocommerce' );

			remove_filter( 'the_content', 'wpautop' );
			remove_filter( 'the_excerpt', 'wpautop' );

			add_filter( 'wpseo_prev_rel_link', '__return_empty_string' );
			add_filter( 'wpseo_next_rel_link', '__return_empty_string' );

		}

	endif;
	add_action( 'after_setup_theme', 'traveling_store_setup' );

	/**
	 * Include theme classes.
	 */
	//require get_template_directory() . '/classes/class-traveling-store-booking.php';

	//  Add viewport meta
	if ( ! function_exists( 'add_viewport_meta_tag' ) ) :

		function add_viewport_meta_tag() {
			echo '<meta name="msapplication-TileColor" content="#ffffff">
			  <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
			  <meta name="theme-color" content="#ffffff">
			  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
			  <meta http-equiv="X-UA-Compatible" content="ie=edge">';
		}

	endif;
	add_action( 'wp_head', 'add_viewport_meta_tag', '1' );

	/**
	 * Replace theme jquery core.
	 */
	if ( ! function_exists( 'replace_core_jquery_version' ) ) :

		function replace_core_jquery_version() {
			wp_deregister_script( 'jquery' );
			wp_deregister_script( 'jquery-core' );
			wp_register_script( 'jquery-core',  get_theme_file_uri( '/assets/js/libs/jquery-3.4.1.min.js' ), array(), '3.4.1' );
			wp_deregister_script( 'jquery-migrate' );
			wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-3.0.0.min.js", array(), '3.0.0' );
		}

	endif;
	add_action( 'wp_enqueue_scripts', 'replace_core_jquery_version' );

	//  Register styles
	if ( ! function_exists( 'traveling_store_styles_setup' ) ) :

		function traveling_store_styles_setup() {
			$release_version = '1.001';

			wp_enqueue_style( 'traveling-store-animations', get_theme_file_uri( '/assets/css/libs/animations.css' ) );
			wp_enqueue_style( 'traveling-store-mfp-popup', get_theme_file_uri( '/assets/css/libs/mfp-popup.css' ) );
			wp_enqueue_style( 'traveling-store-swiper', get_theme_file_uri( '/assets/css/libs/swiper.min.css' ) );
            wp_enqueue_style( 'traveling-store-jquery-ui', get_theme_file_uri( '/assets/css/libs/jquery-ui.min.css' ) );

            wp_enqueue_style( 'traveling-store-style', get_theme_file_uri( '/style.css' ), array(), $release_version );
		}

	endif;
	add_action( 'wp_enqueue_scripts', 'traveling_store_styles_setup' );

	//  Register scripts
	if ( ! function_exists( 'traveling_store_scripts_setup' ) ) :

		function traveling_store_scripts_setup() {

			global $wp_query;

			$term = $wp_query->get_queried_object();

			$release_version = '1.002';

			wp_enqueue_script( 'traveling-store-animate', get_theme_file_uri( '/assets/js/libs/css3-animate-it.js' ), array( 'jquery-core' ), '1.0', true );
			wp_enqueue_script( 'traveling-store-swiper', get_theme_file_uri( '/assets/js/libs/swiper.min.js' ), array( 'jquery-core' ), '1.0', true );
			wp_enqueue_script( 'traveling-store-magnific-popup', get_theme_file_uri( '/assets/js/libs/mfp.min.js' ), array( 'jquery-core' ), '1.0', true );
			wp_enqueue_script( 'traveling-store-nice-select', get_theme_file_uri( '/assets/js/libs/jquery.nice-select.min.js' ), array( 'jquery-core' ), '1.0', true );
            wp_enqueue_script( 'traveling-store-jquery-ui', get_theme_file_uri( '/assets/js/libs/jquery-ui.min.js' ), array( 'jquery-core' ), '1.0', true );

            wp_enqueue_script( 'traveling-store-main', get_theme_file_uri( '/assets/js/main.js' ), array( 'jquery-core' ), $release_version, true );

            if ( is_shop() || is_product_tag() || is_product_category() || taxonomy_is_product_attribute( $term->taxonomy ) ) {
	            wp_enqueue_script( 'traveling-store-shop', get_theme_file_uri( '/assets/js/ajax/shop.min.js' ), array( 'jquery-core' ), $release_version, true );
            }

            if ( is_cart() ) {
	            wp_enqueue_script( 'traveling-store-cart', get_theme_file_uri( '/assets/js/ajax/cart.js' ), array( 'jquery-core' ), $release_version, true );
            }

		}

	endif;
	add_action( 'wp_enqueue_scripts', 'traveling_store_scripts_setup' );

	// Shop filters ajax loop
	add_action( 'wp_ajax_traveling_store_shop_filters', 'traveling_store_shop_filters' );
	add_action( 'wp_ajax_nopriv_traveling_store_shop_filters', 'traveling_store_shop_filters' );
	function traveling_store_shop_filters() {

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 12,
			'post_status'    => 'publish',
			'perm'           => 'readable'
		);

		$args = array_merge( $args, wp_parse_args( $_GET['query'] ) );

		//  Attributes query
		if ( isset($args['pa_regions']) && !empty($args['pa_regions']) ) {

			$regions_args = array(
				'taxonomy'        => 'pa_regions',
				'field'           => 'slug',
				'terms'           =>  explode(",", $args['pa_regions']),
				'operator'        => 'IN',
			);

			$args['tax_query'][] = $regions_args;

		}

		if ( ! isset( $args['order'] ) ) {
			$args = array_merge( $args, array(
				'orderby' => 'menu_order+title',
				'order'   => 'ASC',
			) );
		} else {
			$args['orderby'] = 'meta_value_num';
		}

		if ( ! isset( $args['tax_query'] ) ) {
			$args['tax_query'] = array();
		}
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'terms'    => array( 'exclude-from-catalog', 'hidden' ),
			'field'    => 'slug',
			'operator' => 'NOT IN',
		);

		$loop = new WP_Query( $args );

		$total = $loop->found_posts;

		if ( $loop->have_posts() ) {

			echo '<input type="hidden" class="total-count" data-count="' . $total . '" />';

			while ( $loop->have_posts() ) : $loop->the_post();
				$post_id = get_the_ID();

				get_template_part( 'template-parts/catalog-product' );

			endwhile;
		} else {
			echo __( '<div class="empty-product">Поиск не дал результатов</div>' );
		}

		wp_die();

	}

	//  Checkout payment replace
	remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
	add_action( 'woocommerce_checkout_place_payment', 'woocommerce_checkout_payment', 20 );

	//  Checkout custom fields
	add_filter( 'woocommerce_billing_fields', 'traveling_store_remove_checkout_fields', 10, 1 );
	function traveling_store_remove_checkout_fields( $address_fields ) {

		$address_fields['billing_company']['required'] = false;
		$address_fields['billing_city']['required'] = false;
		$address_fields['billing_postcode']['required'] = false;
		$address_fields['billing_country']['required'] = false;

		return $address_fields;

	}

	function traveling_store_checkout_messenger_field( $checkout ) {

		echo '<div class="chbWrap"><label class="container">';

		woocommerce_form_field( 'whatsapp', array(
			'type'          => 'checkbox',
		), $checkout->get_value( 'whatsapp' ));

		echo '<span class="checkmark"></span>'
		     . '<span class="chbLabel">WhatsApp</span>'
		     . '</label>'
		     . '</div>';

		echo '<div class="chbWrap"><label class="container">';

		woocommerce_form_field( 'viber', array(
			'type'          => 'checkbox',
		), $checkout->get_value( 'viber' ));

		echo '<span class="checkmark"></span>'
		     . '<span class="chbLabel">Viber</span>'
		     . '</label>'
		     . '</div>';

		echo '<div class="chbWrap"><label class="container">';

		woocommerce_form_field( 'telegram', array(
			'type'          => 'checkbox',
		), $checkout->get_value( 'telegram' ));

		echo '<span class="checkmark"></span>'
		     . '<span class="chbLabel">Telegram</span>'
		     . '</label>'
		     . '</div>';

	}

	add_action( 'woocommerce_checkout_update_order_meta', 'traveling_store_checkout_messenger_field_update_order_meta' );
	function traveling_store_checkout_messenger_field_update_order_meta( $order_id ) {

		if ( ! empty( $_POST['whatsapp'] ) ) {
			update_post_meta( $order_id, 'whatsapp', sanitize_text_field( $_POST['whatsapp'] ) );
		}

		if ( ! empty( $_POST['viber'] ) ) {
			update_post_meta( $order_id, 'viber', sanitize_text_field( $_POST['viber'] ) );
		}

		if ( ! empty( $_POST['telegram'] ) ) {
			update_post_meta( $order_id, 'telegram', sanitize_text_field( $_POST['telegram'] ) );
		}

	}

	add_action( 'woocommerce_admin_order_data_after_billing_address', 'traveling_store_checkout_messenger_field_display_admin_order_meta', 10, 1 );
	function traveling_store_checkout_messenger_field_display_admin_order_meta($order) {

		$whatsapp_value = get_post_meta( $order->id, 'whatsapp', true ) == 1 ? 'Yes' : 'No';
		$viber_value = get_post_meta( $order->id, 'viber', true ) == 1 ? 'Yes' : 'No';
		$telegram_value = get_post_meta( $order->id, 'telegram', true ) == 1 ? 'Yes' : 'No';

		echo '<p><strong>' . __( 'WhatsApp' ) . ':</strong> ' . $whatsapp_value . '</p>'
		     . '<p><strong>' . __( 'Viber' ) . ':</strong> ' . $viber_value . '</p>'
		     . '<p><strong>' . __( 'Telegram' ) . ':</strong> ' . $telegram_value . '</p>';

	}

	//  Persons checkout fields
	add_action('checkout_persons_data', 'traveling_store_checkout_persons_data');
	function traveling_store_checkout_persons_data() {

		$cart          = WC()->cart;
		$cart_products = $cart->get_cart();
		$result = array();

		if ( $cart->get_cart_contents_count() !== 0 ) {

			foreach ( $cart_products as $cart_product_key => $cart_product ) {

				$product_id = $cart_product['product_id'];
				$product_persons = $cart_product['booking']['_persons'];

				foreach ( $product_persons as $product_person_id => $product_person_number ) {

					$_person_type = new WC_Product_Booking_Person_Type( $product_person_id );
					$person_type_name = $_person_type->get_name();

					$result[$cart_product_key][$product_person_id]['name'] = $person_type_name;
					$result[$cart_product_key][$product_person_id]['number'] = $product_person_number;

				}


			}

		}

		return $result;

	}

	function traveling_store_checkout_persons_field( $checkout ) {

		$data = traveling_store_checkout_persons_data();

		foreach ( $data as $cart_item_key => $person_type_array ) {

			$cart_product = WC()->cart->get_cart_item( $cart_item_key );
			$_product = new WC_Product_Booking($cart_product['product_id']);
			$product_name = $_product->get_name();

			echo '<div class="cart_item">'
				.'<div class="title-row"><div class="title">' . $product_name . '</div></div> '
				.'<div class="persons">';

			foreach ( $person_type_array as $person_type_id => $person_type ) {

				for ( $i = 1; $i <= $person_type['number']; $i ++ ) {

					echo '<div class="inputRowWrap">'
					     . '<div class="inputLabel">' . $person_type['name'] . ' #' . $i . '</div>'
					     . '<div class="inputRow twoInputs">'
					     . '<div class="inputWrap"><input type="text" placeholder="First name" name="first_name_' . $person_type_id . '_' . $i . '" required></div>'
					     . '<div class="inputWrap"><input type="text" placeholder="Last name" name="last_name_' . $person_type_id . '_' . $i . '" required></div>'
					     . '</div>'
					     . '</div>';

				}

			}

			echo '</div></div>';

		}

	}

	add_action( 'woocommerce_checkout_update_order_meta', 'traveling_store_checkout_persons_field_update_order_meta' );
	function traveling_store_checkout_persons_field_update_order_meta( $order_id ) {

		$data = traveling_store_checkout_persons_data();

		if ( count($data) > 0 ) {

			update_post_meta( $order_id, 'persons_data', $data );

			foreach ( $data as $cart_item_key => $person_type_array ) {

				foreach ( $person_type_array as $person_type_id => $person_type ) {

					for ( $i = 1; $i <= $person_type['number']; $i ++ ) {

						$first_name = 'first_name_' . $person_type_id . '_' . $i;
						$last_name  = 'last_name_' . $person_type_id . '_' . $i;

						if ( ! empty( $_POST[ $first_name ] ) ) {
							update_post_meta( $order_id, $first_name, sanitize_text_field( $_POST[ $first_name ] ) );
						}

						if ( ! empty( $_POST[ $last_name ] ) ) {
							update_post_meta( $order_id, $last_name, sanitize_text_field( $_POST[ $last_name ] ) );
						}
					}

				}

			}

		}

	}

	add_action( 'woocommerce_admin_order_data_after_shipping_address', 'traveling_store_checkout_persons_field_display_admin_order_meta', 10, 1 );
	function traveling_store_checkout_persons_field_display_admin_order_meta( $order ) {

		$data = $order->get_meta('persons_data');

		if ( count($data) > 0 ) {

			echo '<h3>Persons data</h3>';

			foreach ( $data as $cart_item_key => $person_type_array ) {

				foreach ( $person_type_array as $person_type_id => $person_type ) {

					for ( $i = 1; $i <= $person_type['number']; $i ++ ) {

						echo '<strong>' . $person_type_array[$person_type_id]['name'] . ' #' . $i . '</strong>';

						$first_name = 'first_name_' . $person_type_id . '_' . $i;
						$last_name  = 'last_name_' . $person_type_id . '_' . $i;

						$first_name_value = get_post_meta( $order->id, $first_name, true );
						$last_name_value  = get_post_meta( $order->id, $last_name, true );

						echo '<div><p><span>First name: </span><span>' . $first_name_value . '</span></p>';
						echo '<p><span>Last name: </span><span>' . $last_name_value . '</span></p></div>';

					}

				}

			}

		}

	}
