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

	//  Replace jquery core
	if ( ! function_exists( 'replace_core_jquery_version' ) ) :

		function replace_core_jquery_version() {
			wp_deregister_script( 'jquery-core' );
			wp_register_script( 'jquery-core', "https://code.jquery.com/jquery-3.1.1.min.js", array(), '3.1.1' );
			wp_deregister_script( 'jquery-migrate' );
			wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-3.0.0.min.js", array(), '3.0.0' );
		}

	endif;
	add_action( 'wp_enqueue_scripts', 'replace_core_jquery_version' );

	//  Register styles
	if ( ! function_exists( 'traveling_store_styles_setup' ) ) :

		function traveling_store_styles_setup() {
			$release_version = '1.001';

			wp_enqueue_style( 'traveling-store-animations', get_theme_file_uri( '/css/libs/animations.css' ) );
			wp_enqueue_style( 'traveling-store-mfp-popup', get_theme_file_uri( '/css/libs/mfp-popup.css' ) );
			wp_enqueue_style( 'traveling-store-swiper', get_theme_file_uri( '/css/libs/swiper.min.css' ) );
            wp_enqueue_style( 'traveling-store-jquery-ui', get_theme_file_uri( '/css/libs/jquery-ui.min.css' ) );

            wp_enqueue_style( 'traveling-store-style', get_theme_file_uri( '/style.css' ), array(), $release_version );
		}

	endif;
	add_action( 'wp_enqueue_scripts', 'traveling_store_styles_setup' );

	//  Register scripts
	if ( ! function_exists( 'traveling_store_scripts_setup' ) ) :

		function traveling_store_scripts_setup() {

			global $wp_query;

			$term = $wp_query->get_queried_object();

			$release_version = '1.001';

			wp_enqueue_script( 'traveling-store-animate', get_theme_file_uri( '/js/libs/css3-animate-it.js' ), array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'traveling-store-swiper', get_theme_file_uri( '/js/libs/swiper.min.js' ), array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'traveling-store-magnific-popup', get_theme_file_uri( '/js/libs/mfp.min.js' ), array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'traveling-store-nice-select', get_theme_file_uri( '/js/libs/jquery.nice-select.min.js' ), array( 'jquery' ), '1.0', true );
            wp_enqueue_script( 'traveling-store-jquery-ui', get_theme_file_uri( '/js/libs/jquery-ui.min.js' ), array( 'jquery' ), '1.0', true );

            wp_enqueue_script( 'traveling-store-main', get_theme_file_uri( '/js/main.min.js' ), array( 'jquery' ), $release_version, true );

            if ( is_shop() || is_product_tag() || is_product_category() || taxonomy_is_product_attribute($term->taxonomy) ) {
	            wp_enqueue_script( 'traveling-store-shop', get_theme_file_uri( '/js/ajax/shop.min.js' ), array( 'jquery' ), $release_version, true );
            }

            if ( is_cart() ) {
	            wp_enqueue_script( 'traveling-store-cart', get_theme_file_uri( '/js/ajax/cart.js' ), array( 'jquery' ), $release_version, true );
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

	// test

//	add_action('init', 'test');
//	function test() {
//
//		WC()->cart->empty_cart();
//
//	}