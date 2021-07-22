<?php
/**
 * Template part for displaying a post's header
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$title_overwrite = get_post_meta( get_the_ID(), '_post_title_overwrite', true );
$title_position  = get_post_meta( get_the_ID(), '_post_title_position', true );

$content_classes = array();

if ( $title_overwrite == 'yes' ) {
	$content_classes[] = 'buddyx-section-' . $title_position;
} else {
	$content_classes[] = get_theme_mod( 'single_post_title_layout', buddyx_defaults( 'single-post-title-layout' ) );
}

if ( has_post_thumbnail() ) {
	$content_classes[] = 'has-featured-image';
}

$content_classes = implode( ' ', $content_classes );

?>
<div class="buddyx-post-section <?php echo esc_attr( $content_classes ); ?>">
	
	<div class="entry-media-image">
	
		<?php get_template_part( 'template-parts/content/entry_media', get_post_type() ); ?>
	
	</div><!-- .entry-media-image -->
	<header class="entry-header entry-header--default">
		<?php
		get_template_part( 'template-parts/content/entry_categories', get_post_type() );

		get_template_part( 'template-parts/content/entry_title', get_post_type() );

		get_template_part( 'template-parts/content/entry_meta', get_post_type() );
		?>
	</header><!-- .entry-header -->

</div><!-- .buddyx-post-section --> 
