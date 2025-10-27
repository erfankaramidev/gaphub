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
		// If the page is not a comment page, return.
		if ( ! is_singular() || ! ( comments_open() || get_comments_number() ) || post_password_required() ) {
			return;
		}

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
			'--gh-primary-hover'     => $settings->get_option( 'primary_color_hover' ),
			'--gh-comment-bg'        => $settings->get_option( 'comment_bg_color' ),
			'--gh-text-dark'         => $settings->get_option( 'text_dark_color' ),
			'--gh-text-light'        => $settings->get_option( 'text_light_color' ),
			'--gh-font-size-heading' => $settings->get_option( 'heading_font_size' ) . 'px',
			'--gh-border-radius'     => $settings->get_option( 'border_radius' ) . 'px',
			'--gh-border-color'      => $settings->get_option( 'border_color' ),
			'--gh-gap'               => $settings->get_option( 'gap' ) . 'px',
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
