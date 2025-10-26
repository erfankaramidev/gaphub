<?php

declare(strict_types=1);

namespace Gaphub\Comments;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the contract for fetching and displaying comments.
 * 
 * @since 1.0.0
 */
interface CommentProviderInterface {

	/**
	 * Get comments for a specific post.
	 * @return CommentData[] An array of CommentData objects.
	 */
	public function get_comments( $post_id );

	/**
	 * Get the HTML for the comment form.
	 * @return string The comment form HTML.
	 */
	public function get_form( $post_id );

	/**
	 * Gets the count of top-level comments for pagination.
	 * @return int
	 */
	public function get_comments_count( $post_id );

	/**
	 * Gets the total count of all comments for display.
	 * @return int
	 */
	public function get_total_comments_count( $post_id );
}
