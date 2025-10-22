<?php

declare(strict_types=1);

namespace Gaphub\Comments;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creates and returns the proper CommentProvider based on the context.
 */
final class CommentProviderFactory {

	/**
	 * Gets the appropriate comment provider.
	 * 
	 * @param int $post_id
	 * @return CommentProviderInterface
	 */
	public static function get_provider( int $post_id ): CommentProviderInterface {
		$post_type = get_post_type( $post_id );

		// Default to the standard WP provider.
		return new WPCommentProvider();
	}
}
