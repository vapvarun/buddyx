<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$video_url = get_post_meta( get_the_ID(), '_buddyx_post_video', true );
if ( ( $video_url != '' && ! has_post_thumbnail() || is_single() ) ) {  ?>
	<div class="buddyx-video-block buddyx-post-thumbnail">
		<?php
		$post_video = wp_oembed_get( $video_url );
		if ( $post_video != '' ) {
			echo $post_video; // WPCS: XSS ok.
		} else {
			echo do_shortcode( '[video  src="' . $video_url . '"]' );
		}
		?>
	</div>
	<?php
} else {
	?>
	<div class="buddyx-post-thumbnail">
		<div class="buddyx-video-post-thumbnail-wrap">
			<div class="buddyx-play-icon"><i class="fa fa-play" aria-hidden="true"></i></div>
			<?php get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() ); ?>
		</div>
	</div>
	<?php
}
