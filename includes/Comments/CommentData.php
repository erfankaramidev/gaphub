<?php

declare(strict_types=1);

namespace Gaphub\Comments;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalizes WP_Comment object into a consistent data object for use in templates.
 * 
 * @since 1.0.0
 */
final class CommentData {

	private $comment;

	public function __construct( \WP_Comment $comment ) {
		$this->comment = $comment;
	}

	public function get_id() {
		return $this->comment->comment_ID;
	}

	public function get_author(): string {
		return get_comment_author( $this->comment );
	}

	public function get_avatar_url(): string {
		return get_avatar_url( $this->comment->comment_author_email );
	}

	public function get_content(): string {
		return get_comment_text( $this->comment );
	}

	public function get_date( $format = '' ): string {
		return get_comment_date( $format, $this->comment );
	}
}
