<?php

declare(strict_types=1);

namespace Gaphub\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utility class to manage and render comment templates.
 * 
 * @since 1.0.0
 */
final class CommentRenderer {

	/**
	 * Renders a single comment template.
	 * 
	 * @param \Gaphub\Comments\CommentData $comment The comment to render.
	 * @param int $depth The current comment depth.
	 */
	public static function render_comment( $comment, $depth ) {
		include GH_PATH . 'templates/parts/comment-display.php';
	}

	/**
	 * Renders an array of comments.
	 * 
	 * @param \Gaphub\Comments\CommentData[] $comments Array of comments to render.
	 * @param int $depth The current comment depth.
	 */
	public static function render_comments_list( $comments, $depth = 1 ) {
		foreach ( $comments as $comment ) {
			self::render_comment( $comment, $depth );
		}
	}

	/**
	 * Renders comment pagination.
	 * 
	 * @param int $top_level_comment_count
	 */
	public static function render_pagination( $top_level_comment_count ) {
		if ( get_option( 'page_comments' ) ) {
			$per_page    = (int) get_option( 'comments_per_page' );
			$total_pages = ( $per_page > 0 ) ? ceil( $top_level_comment_count / $per_page ) : 1;

			echo '<nav class="gh-comment-pagination" aria-label="Comments pagination">';
			echo paginate_comments_links( [
				'total'     => $total_pages,
				'current'   => get_query_var( 'cpage' ) ? (int) get_query_var( 'cpage' ) : 1,
				'prev_text' => '&laquo; <span class="screen-reader-text">' . __( 'Previous', 'gaphub' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'gaphub' ) . '</span> &raquo;',
				'type'      => 'plain'
			] );
			echo '</nav>';
		}
	}
}
