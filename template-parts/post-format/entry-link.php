<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_link_title = get_post_meta( get_the_ID(), '_buddyx_post_link_title', true );
$post_link_url   = get_post_meta( get_the_ID(), '_buddyx_post_link_url', true );

if ( '' !== $post_link_title && '' !== $post_link_url ) { ?>
	
	<div class="buddyx-link-block buddyx-post-thumbnail">
		<?php get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() ); ?>
		<a class="buddyx-link-format" href="<?php echo esc_url( $post_link_url ); ?>"> 
			<?php echo buddyx_get_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from the theme icon map. ?><?php echo esc_html( $post_link_title ); ?> →
		</a>
	</div>
	
	<?php
}
