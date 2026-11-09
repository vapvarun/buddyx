<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$video_url = get_post_meta( get_the_ID(), '_buddyx_post_video', true );
if ( ( '' !== $video_url && ! has_post_thumbnail() ) || is_single() ) {  ?>
	<div class="buddyx-video-block buddyx-post-thumbnail">
		<?php
		$post_video = wp_oembed_get( $video_url );
		if ( '' !== (string) $post_video ) {
			echo $post_video; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted oEmbed HTML from wp_oembed_get(); escaping would strip the embed iframe.
		} else {
			echo do_shortcode( '[video  src="' . esc_url( $video_url ) . '"]' );
		}
		?>
	</div>
	<?php
} else {
	?>
	<div class="buddyx-post-thumbnail">
		<div class="buddyx-video-post-thumbnail-wrap">
			<div class="buddyx-play-icon"><?php echo buddyx_get_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from the theme icon map. ?></div>
			<?php get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() ); ?>
		</div>
	</div>
	<?php
}
