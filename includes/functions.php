<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function buddyforms_modal_include_assets() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && defined( 'DOING_CRON' ) && DOING_CRON ) {
		return;
	}
	if ( BuddyFormsModal::getNeedAssets() ) {
		wp_enqueue_script( 'buddyforms-modal-script', BUDDYFORMS_MODAL_ASSETS . 'js/script.js', array( 'jquery' ), BuddyFormsModal::getVersion() );
		wp_localize_script( 'buddyforms-modal-script', 'buddyformsModal', array(
			'ajax'     => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( __DIR__ . 'buddyforms-contact-author' ),
		) );
		wp_enqueue_style( 'buddyforms-modal-style', BUDDYFORMS_MODAL_ASSETS . 'css/style.css', array(), BuddyFormsModal::getVersion() );
		add_thickbox();
	}
}

add_action( 'wp_footer', 'buddyforms_modal_include_assets' );
