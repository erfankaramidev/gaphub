<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Comment template.
 * 
 * @var \Gaphub\Comments\CommentData $comment
 */

?>

<li class="gh-comment-item" id="gh-comment-<?php echo $comment->get_id(); ?>">
	<article class="gh-comment-body">
		<footer class="gh-comment-meta">
			<div class="gh-comment-author-avatar">
				<img src="<?php echo esc_url( $comment->get_avatar_url() ); ?>"
					alt="<?php echo esc_attr( $comment->get_author() ); ?>" width="48" height="48" loading="lazy" />
			</div>
			<div class="gh-comment-author-name">
				<?php echo esc_html( $comment->get_author() ); ?>
			</div>
			<div class="gh-comment-date">
				<?php echo esc_html( $comment->get_date() ); ?>
			</div>
		</footer>
		<div class="gh-comment-content">
			<p>
				<?php echo wpautop( wp_kses_post( $comment->get_content() ) ); ?>
			</p>
		</div>
	</article>
</li>