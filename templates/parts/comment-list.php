<?php

declare(strict_types=1);

use Gaphub\Core\CommentRenderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reusable comment template.
 * 
 * @var \Gaphub\Comments\CommentData[] $comments
 * @var int $total_comment_count
 */

?>

<div class="gh-comment-list">
	<h3 class="gh-comments-title">
		<?php
		printf(
			esc_html__( 'Comments (%d)', 'gaphub' ),
			$total_comment_count
		);
		?>
	</h3>
	<ol id="gh-comment-list" class="gh-comment-list">
		<?php CommentRenderer::render_comments_list( $comments, 1 ) ?>
	</ol>
</div>