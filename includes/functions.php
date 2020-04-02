<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Include the form to the global variable
 *
 * @param $buddyforms_global_js_data
 * @param $form_slug
 *
 * @return array()
 */
function buddyforms_modal_add_form_to_global( $buddyforms_global_js_data, $form_slug ) {
	if ( ! empty( $form_slug ) && empty( $buddyforms_global_js_data[ $form_slug ] ) && BuddyFormsModal::getNeedAssets() ) {
		global $buddyforms;
		if ( ! empty( $form_slug ) && ! empty( $buddyforms ) && isset( $buddyforms[ $form_slug ] ) ) {
			$options                                 = buddyforms_filter_frontend_js_form_options( $buddyforms[ $form_slug ], $form_slug, null );
			$buddyforms_global_js_data[ $form_slug ] = $options;
		}
	}

	return $buddyforms_global_js_data;
}

add_filter( 'buddyforms_global_localize_scripts', 'buddyforms_modal_add_form_to_global', 90, 2 );

/**
 * Include the assets in the frontend
 */
function buddyforms_modal_include_assets() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && defined( 'DOING_CRON' ) && DOING_CRON ) {
		return;
	}
	if ( BuddyFormsModal::getNeedAssets() ) {
		wp_enqueue_style( 'buddyforms-thickbox', BUDDYFORMS_MODAL_ASSETS . 'css/bf-thickbox.css', array(), BuddyFormsModal::getVersion() );
		wp_enqueue_script( 'buddyforms-thickbox', BUDDYFORMS_MODAL_ASSETS . 'js/bf-thickbox.js', array( 'jquery' ), BuddyFormsModal::getVersion() );
		wp_localize_script('buddyforms-thickbox', 'thickboxL10n', array(
			'next'             => __( 'Next &gt;' ),
			'prev'             => __( '&lt; Prev' ),
			'image'            => __( 'Image' ),
			'of'               => __( 'of' ),
			'close'            => __( 'Close' ),
			'noiframes'        => __( 'This feature requires inline frames. You have iframes disabled or your browser does not support them.' ),
			'loadingAnimation' => includes_url( 'js/thickbox/loadingAnimation.gif' ),
		));

		wp_enqueue_script( 'buddyforms-modal-script', BUDDYFORMS_MODAL_ASSETS . 'js/script.js', array( 'jquery', 'buddyforms-thickbox' ), BuddyFormsModal::getVersion() );
		wp_localize_script( 'buddyforms-modal-script', 'buddyformsModal', array(
			'ajax'           => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( __DIR__ . 'buddyforms-contact-author' ),
			'tb_pathToImage' => includes_url( 'js/thickbox/loadingAnimation.gif', 'relative' ),
		) );
		wp_enqueue_style( 'buddyforms-modal-style', BUDDYFORMS_MODAL_ASSETS . 'css/style.css', array(), BuddyFormsModal::getVersion() );

		add_thickbox();
	}
}

add_action( 'wp_footer', 'buddyforms_modal_include_assets' );
