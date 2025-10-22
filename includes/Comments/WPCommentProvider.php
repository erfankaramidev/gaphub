<?php

declare(strict_types=1);

namespace Gaphub\Comments;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WordPress Comment provider.
 * 
 * @since 1.0.0
 */
final class WPCommentProvider implements CommentProviderInterface {

	/**
	 * @inheritDoc
	 */
	public function get_comments( int $post_id ): array {
		$comments_data = [];

		$wp_comments = get_comments( [
			'post_id' => $post_id,
			'status'  => 'approve',
			'type'    => 'comment'
		] );

		foreach ( $wp_comments as $wp_comment ) {
			$comments_data[] = new CommentData( $wp_comment );
		}

		return $comments_data;
	}

	/**
	 * @inheritDoc
	 */
	public function get_form( int $post_id ): string {
		ob_start();
		comment_form( [], $post_id );
		return ob_get_clean();
	}
}
