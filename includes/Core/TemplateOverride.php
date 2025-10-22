<?php

declare(strict_types=1);

namespace Gaphub\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Override default comment template class.
 * 
 * @since 1.0.0
 */
final class TemplateOverride {

	public function register_hooks() {
		add_filter( 'comments_template', [ $this, 'override_comments_template' ] );
	}

	public function override_comments_template( $theme_template ) {
		if ( ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
			return GH_PATH . 'templates/main-comments-template.php';
		}

		return $theme_template;
	}
}
