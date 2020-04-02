<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function buddyforms_modal_shortcode( $attr, $content ) {
	$attr = shortcode_atts( array(
		'form_slug' => '',
		'width'     => '600px',
		'height'    => '100%',
		'title'     => __( 'Open', 'bf-modal' ),
	), $attr );
	if ( empty( $attr['form_slug'] ) ) {
		$form_slug = buddyforms_get_form_slug_from_content( $content );
	} else {
		$form_slug = $attr['form_slug'];
	}
	if ( ! empty( $form_slug ) ) {
		if ( ! empty( $attr['title'] ) ) {
			if ( empty( $attr['modal_title'] ) ) {
				$attr['modal_title'] = $attr['title'];
			}
			if ( empty( $attr['trigger_title'] ) ) {
				$attr['trigger_title'] = $attr['title'];
			}
		}
		BuddyFormsModal::setNeedAssets( true, $form_slug );
		ob_start();
		$trigger_link = sprintf( "<a id=\"buddyforms-modal-%s\" href=\"#TB_inline?width=%s&height=%s&inlineId=buddyforms_modal_%s\" title=\"%s\" class=\"bf-thickbox buddyforms-modal-trigger\">%s</a><div id=\"buddyforms_modal_%s\" style=\"display:none;\">", $form_slug, $attr['width'], $attr['height'], $form_slug, $attr['modal_title'], $attr['trigger_title'], $form_slug );
		$form_content = sprintf( '[bf form_slug="%s"]', $form_slug );
		echo $trigger_link;
		echo do_shortcode( $form_content );
		$content = ob_get_clean();

		return $content . '</div>';
	}

	return '';
}

add_shortcode( 'bf_modal', 'buddyforms_modal_shortcode' );
