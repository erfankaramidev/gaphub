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
	public function get_comments( $post_id ) {
		// Get all approved comments for this post.
		$wp_comments = get_comments( [
			'post_id' => $post_id,
			'status'  => 'approve',
			'type'    => 'comment',
		] );

		// Map of CommentData objects.
		$comments_map = [];
		foreach ( $wp_comments as $wp_comment ) {
			$comments_map[ $wp_comment->comment_ID ] = new CommentData( $wp_comment );
		}

		// Create the hierarchy,
		$top_level_comments = [];
		foreach ( $comments_map as $comment_id => $comment ) {
			if ( $comment->get_parent_id() && isset( $comments_map[ $comment->get_parent_id()] ) ) {
				// This is a child comment.
				$comments_map[ $comment->get_parent_id()]->add_child( $comment );
			} else {
				// This is a top-level comment.
				$top_level_comments[] = $comment;
			}
		}

		// Handle the pagination.
		$per_page = (int) get_option( 'comments_per_page' );
		if ( $per_page > 0 && get_option( 'page_comments' ) ) {
			$page   = get_query_var( 'cpage' ) ? (int) get_query_var( 'cpage' ) : 1;
			$offset = ( $page - 1 ) * $per_page;

			return array_slice( $top_level_comments, $offset, $per_page );
		}

		// Return if pagination is not enabled.
		return $top_level_comments;
	}

	/**
	 * @inheritDoc
	 */
	public function get_comments_count( $post_id ) {
		$args = [
			'post_id' => $post_id,
			'status'  => 'approve',
			'type'    => 'comment',
			'count'   => true,
			'parent'  => 0
		];

		return get_comments( $args );
	}

	/**
	 * @inheritDoc
	 */
	public function get_total_comments_count( $post_id ) {
		$args = [
			'post_id' => $post_id,
			'status'  => 'approve',
			'type'    => 'comment',
			'count'   => true,
		];

		return get_comments( $args );
	}

	/**
	 * @inheritDoc
	 */
	public function get_form( $post_id ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = $req ? " aria-required='true'" : '';

		$fields = [
			'author' =>
				'<div class="gh-comment-field gh-comment-field-author"><label for="author">' . __( 'Name', 'gaphub' ) . ( $req ? ' *' : '' ) . '</label> ' .
				'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' /></div>',
			'email'  =>
				'<div class="gh-comment-field gh-comment-field-email"><label for="email">' . __( 'Email', 'gaphub' ) . ( $req ? ' *' : '' ) . '</label> ' .
				'<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" ' . $aria_req . ' /></div>',
			'url'    =>
				'<div class="gh-comment-field gh-comment-field-url"><label for="url">' . __( 'Website', 'gaphub' ) . '</label> ' .
				'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" /></div>'
		];

		$comment_field =
			'<div class="gh-comment-field gh-comment-field-comment"><label for="comment">' . _x( 'Comment', 'noun', 'gaphub' ) . ' *</label>' .
			'<textarea id="comment" name="comment" aria-required="true" rows="4"></textarea></div>';

		$comment_form_args = [
			'id_form'           => 'gh-comment-form',
			'id_submit'         => 'gh-comment-submit',
			'class_submit'      => 'gh-comment-submit',
			'title_reply'       => __( 'Leave a comment', 'gaphub' ),
			'title_reply_to'    => __( 'Leave a reply to %s', 'gaphub' ),
			'cancel_reply_link' => __( 'Cancel reply', 'gaphub' ),
			'label_submit'      => __( 'Post Comment', 'gaphub' ),
			'fields'            => $fields,
			'comment_field'     => $comment_field
		];

		ob_start();
		comment_form( $comment_form_args, $post_id );
		return ob_get_clean();
	}
}
