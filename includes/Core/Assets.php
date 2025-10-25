<?php

declare(strict_types=1);

namespace Gaphub\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue assets
 * 
 * @since 1.0.0
 */
final class Assets {

	public function register_hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles_and_scripts' ] );
	}

	public function enqueue_styles_and_scripts() {
		// ====== Styles ======
		wp_enqueue_style(
			'gaphub-style',
			GH_URL . 'assets/css/gaphub-style.css',
			[],
			GH_VERSION
		);

		// Get settings from admin
		$settings = \Gaphub\Plugin::instance()->get_settings();
		$css_vars = [
			'--gh-primary-color'     => $settings->get_option( 'primary_color' ),
			'--gh-comment-bg'        => $settings->get_option( 'comment_bg_color' ),
			'--gh-font-size-base'    => $settings->get_option( 'base_font_size_px' ) . 'px',
			'--gh-font-size-heading' => $settings->get_option( 'heading_font_size_px' ) . 'px',
			'--gh-border-radius'     => $settings->get_option( 'field_border_radius_px' ) . 'px',
			'--gh-gap'               => $settings->get_option( 'gap_px' ) . 'px',
			'--gh-input-padding'     => $settings->get_option( 'input_padding_px' ) . 'px',
		];

		$inline_css = ':root {';
		foreach ( $css_vars as $key => $value ) {
			$inline_css .= esc_html( $key ) . ': ' . esc_html( $value ) . ';';
		}
		$inline_css .= '}';

		wp_add_inline_style( 'gaphub-style', $inline_css );

		// ====== Scripts ======
		wp_enqueue_script(
			'gaphub-script',
			GH_URL . 'assets/js/gaphub-script.js',
			[],
			GH_VERSION,
			true
		);
	}
}
