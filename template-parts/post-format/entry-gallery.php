<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_gallery = get_post_meta( get_the_ID(), '_buddyx_image_gallery', true );
if ( $post_gallery != '' ) {
	$post_gallery_class = ( is_single() ) ? 'single-buddyx-gallery-post' : 'archive-buddyx-gallery-post';
	?>
	<div class="buddyx-gallery-block buddyx-post-thumbnail <?php echo esc_attr( $post_gallery_class ); ?>">
		<?php
		$gallery_ids = implode( ',', array_map( 'absint', explode( ',', $post_gallery ) ) );
		echo do_shortcode( '[gallery size="medium" ids="' . $gallery_ids . '"]' ); // WPCS: XSS ok.
		?>
	</div>
	<?php
}
