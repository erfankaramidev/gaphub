<?php

declare(strict_types=1);
use Gaphub\Core\CommentRenderer;

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

$comments                = $provider->get_comments( $post_id );
$form_html               = $provider->get_form( $post_id );
$total_comment_count     = $provider->get_total_comments_count( $post_id );
$top_level_comment_count = $provider->get_comments_count( $post_id );

echo '<div id="comments" class="gh-global-wrapper">';

// If comments are open, show the comment form.
if ( comments_open() ) {
	echo "<div class=\"gh-comment-form-wrapper\">$form_html</div>";
}

if ( ! empty( $comments ) ) {
	CommentRenderer::render_pagination( $top_level_comment_count );

	include GH_PATH . 'templates/parts/comment-list.php';

	CommentRenderer::render_pagination( $top_level_comment_count );
} else if ( comments_open() ) {
	echo '<div class="gh-comment-list><p class="gh-no-comments">' . esc_html__( 'No comments yet.', 'gaphub' ) . '</p></div>"';
}

echo '</div>';
