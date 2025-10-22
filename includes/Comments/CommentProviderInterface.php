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
	 * 
	 * @param int $post_id
	 * @return CommentData[] An array of CommentData objects.
	 */
	public function get_comments( int $post_id ): array;

	/**
	 * Get the HTML for the comment form.
	 * 
	 * @param int $post_id
	 * @return string The comment form HTML.
	 */
	public function get_form( int $post_id ): string;
}
