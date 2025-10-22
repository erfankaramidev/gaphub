<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reusable comment template.
 * 
 * @var \Gaphub\Comments\CommentData[] $comments
 */

?>

<div class="gh-comment-list">
	<?php if ( ! empty( $comments ) ) : ?>
		<h3 class="gh-comments-title">
			<?php
			printf(
				esc_html__( 'Comments (%d)', 'gaphub' ),
				count( $comments )
			);
			?>
		</h3>
		<ol>
			<?php foreach ( $comments as $comment ) : ?>
				<?php include GH_PATH . 'templates/parts/comment-display.php'; ?>
			<?php endforeach; ?>
		</ol>
	<?php elseif ( comments_open() ) : ?>
		<p class="gh-no-comments">
			<?php esc_html_e( 'No comments yet. Be the first to comment!', 'gaphub' ); ?>
		</p>
	<?php endif; ?>
</div>