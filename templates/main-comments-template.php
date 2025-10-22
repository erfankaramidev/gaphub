<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * =============================================================
 * This is the main comments template file for theme overrides.
 * =============================================================
 */

$post_id = get_the_ID();

$provider = \Gaphub\Comments\CommentProviderFactory::get_provider( $post_id );

$comments  = $provider->get_comments( $post_id );
$form_html = $provider->get_form( $post_id );

echo '<div id="comments" class="gh-global-wrapper">';

// If comments are open, show the comment form.
if ( comments_open() ) {
	echo "<div class=\"gh-comment-form-wrapper\">$form_html</div>";
}

// Show the comments.
include GH_PATH . 'templates/parts/comment-loop.php';
echo '</div>';
