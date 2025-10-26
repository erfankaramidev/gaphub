<?php

declare(strict_types=1);

use Gaphub\Core\CommentRenderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Comment template.
 * 
 * @var \Gaphub\Comments\CommentData $comment
 */

?>

<li class="gh-comment-item" data-comment-id="<?php echo $comment->get_id(); ?>"
	id="gh-comment-<?php echo $comment->get_id(); ?>">
	<article class="gh-comment-body">
		<img class="gh-comment-avatar" src="<?php echo esc_url( $comment->get_avatar_url() ); ?>"
			alt="<?php echo esc_attr( $comment->get_author() ); ?>" loading="lazy" />
		<div class="gh-comment-main">
			<header class="gh-comment-header">
				<span class="gh-author-name"><?php echo esc_html( $comment->get_author() ); ?></span>
				<span
					class="gh-timestamp"><?php echo esc_html( $comment->get_date() . ' at ' . $comment->get_date( 'H:i' ) ); ?></span>
			</header>
			<div class="gh-comment-content">
				<p><?php echo wpautop( wp_kses_post( $comment->get_content() ) ) ?></p>
			</div>
			<footer class="gh-comment-footer">
				<button class="gh-reply-btn" data-comment-id="<?php echo $comment->get_id(); ?>"
					data-comment-author="<?php echo $comment->get_author() ?>">Reply</button>
			</footer>
		</div>
	</article>

	<?php if ( $comment->has_children() ) : ?>
		<ol class="gh-children">
			<?php CommentRenderer::render_comments_list( $comment->get_children() ) ?>
		</ol>
	<?php endif; ?>
</li>