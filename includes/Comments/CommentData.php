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

	/**
	 * @var CommentData[]
	 */
	private $children = [];

	public function __construct( \WP_Comment $comment ) {
		$this->comment = $comment;
	}

	public function get_id() {
		return (int) $this->comment->comment_ID;
	}

	public function get_parent_id() {
		return (int) $this->comment->comment_parent;
	}

	public function get_author() {
		return get_comment_author( $this->comment );
	}

	public function get_avatar_url() {
		return get_avatar_url( get_comment_author_email( $this->comment ) );
	}

	public function get_content() {
		return get_comment_text( $this->comment );
	}

	public function get_date( $format = '' ) {
		return get_comment_date( $format, $this->comment );
	}

	/**
	 * @param CommentData $comment
	 */
	public function add_child( $comment ) {
		$this->children[] = $comment;
	}

	/**
	 * @return CommentData[]
	 */
	public function get_children() {
		return $this->children;
	}

	public function has_children() {
		return ! empty( $this->children );
	}
}
