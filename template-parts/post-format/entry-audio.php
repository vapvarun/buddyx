<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_audio       = get_post_meta( get_the_ID(), '_buddyx_post_audio', true );
$post_audio_embed = wp_oembed_get( $post_audio );

if ( $post_audio != '' && $post_audio_embed != '' ) { ?>
	<div class="buddyx-audio-block buddyx-post-thumbnail">		
		<?php echo $post_audio_embed; // WPCS: XSS ok. ?>
	</div>	
	<?php
}

if ( $post_audio != '' && $post_audio_embed == '' ) {
	?>
	<div class="buddyx-audio-block buddyx-post-thumbnail">					
		<?php echo do_shortcode( '[audio src="' . $post_audio . '"]' ); // WPCS: XSS ok. ?>
	</div>
	<?php
}
