<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_quote        = get_post_meta( get_the_ID(), '_buddyx_post_quote', true );
$post_quote_author = get_post_meta( get_the_ID(), '_buddyx_post_quote_author', true );


if ( $post_quote != '' ) {
	$post_thumbnail = ( has_post_thumbnail() ) ? get_the_post_thumbnail_url( get_the_ID(), 'buddyx-featured-large' ) : '';
	?>
		<div class="buddyx-quote-block buddyx-post-thumbnail" style="background-image: url('<?php echo esc_url( $post_thumbnail ); ?>')">
			<blockquote class="wp-block-quote">
				<p><?php echo esc_html( $post_quote ); ?></p>
				<?php if ( $post_quote_author ) : ?>
					<cite><strong><?php echo esc_html( $post_quote_author ); ?></strong></cite>
				<?php endif; ?>
			</blockquote>
		</div>
	<?php
}
