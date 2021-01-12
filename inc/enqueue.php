<?php
/**
 * Theme enqueue scripts
 *
 * @package Pentamint_WP_Theme
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'pentamint_wp_theme_scripts' );

if ( ! function_exists( 'pentamint_wp_theme_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function pentamint_wp_theme_scripts() {
		wp_enqueue_style( 'pentamint_wp_theme-style', get_stylesheet_uri(), array() , filemtime(get_stylesheet_directory() . '/style.css'), false );

		// *** To be removed later after project is completed ***
		wp_enqueue_style( 'pentamint_extra-style',  get_template_directory_uri() . '/style-pm.css', array() , filemtime(get_template_directory() . '/style-pm.css'), false );

		wp_enqueue_script( 'pentamint_wp_theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	
		wp_enqueue_script( 'pentamint_wp_theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	
		/** Start Custom Scripts **/
		// Initiate Default Wordpress jQuery
		wp_enqueue_script('jquery');
	
		// Bootstrap Support
		wp_enqueue_script( 'popper.js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array(), null, true );
		wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array(), null, true );

		// Bootstrap Datepicker //
		if ( is_page( 'application' ) ) {
			wp_enqueue_style( 'bootstrap-datepicker', get_template_directory_uri() . '/css/bootstrap-datepicker.min.css', false );
			wp_enqueue_script( 'bootstrap-datepicker', get_template_directory_uri() . '/js/bootstrap-datepicker.min.js', array(), null, true );
			wp_enqueue_script( 'iamport-payment', 'https://cdn.iamport.kr/js/iamport.payment-1.1.7.js', array(), null, false );
		}

		// Theme Custom
		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap', false );
		wp_enqueue_style( 'nanum-squareround', 'https://cdn.jsdelivr.net/gh/moonspam/NanumSquare@1.0/nanumsquare.css', false );
		wp_enqueue_style( 'animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css', true );
	
		wp_enqueue_script( 'ofi-min-js', get_template_directory_uri() . '/js/ofi.min.js', array(), '3.2.4', true );	
		wp_enqueue_script( 'main_js', get_template_directory_uri() . '/js/main.js', array('jquery'), filemtime(get_template_directory() . '/js/main.js'), true );
		/** End Custom Scripts **/
	
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}
} // endif function_exists( 'pentamint_wp_theme_scripts' ).
