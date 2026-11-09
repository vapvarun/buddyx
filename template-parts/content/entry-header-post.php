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

if ( has_post_format( 'video' ) ) {
	$format_class = 'video';
} elseif ( has_post_format( 'audio' ) ) {
	$format_class = 'audio';
} elseif ( has_post_format( 'quote' ) ) {
	$format_class = 'quote';
} elseif ( has_post_format( 'link' ) ) {
	$format_class = 'link';
} elseif ( has_post_format( 'gallery' ) ) {
	$format_class = 'gallery';
} elseif ( has_post_format( 'image' ) ) {
	$format_class = 'image';
} else {
	$format_class = 'standard';
}

if ( 'yes' === $title_overwrite ) {
	$content_classes[] = 'buddyx-section-' . $title_position;
} else {
	$content_classes[] = get_theme_mod( 'single_post_title_layout', buddyx_defaults( 'single-post-title-layout' ) );
}

if ( has_post_thumbnail() ) {
	$content_classes[] = 'has-featured-image';
}

$content_classes = implode( ' ', $content_classes );

while ( have_posts() ) {
	the_post(); ?>
	<div class="buddyx-post-section <?php echo esc_attr( $content_classes ); ?> <?php echo esc_attr( $format_class ); ?>">
		
		<div class="entry-media-image">
		
			<?php get_template_part( 'template-parts/content/entry_media', get_post_type() ); ?>
		
		</div><!-- .entry-media-image -->
		<header class="entry-header entry-header--default">
			<?php
			// Single posts have no sub-header (the title is here), so the Breadcrumbs
			// setting prints its trail at the top of the post header instead.
			$show_breadcrumbs = buddyx_is_truthy( get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) ) );
			if ( $show_breadcrumbs ) {
				/** This action is documented in inc/extra.php */
				do_action( 'buddyx_before_breadcrumb' );
				buddyx_the_breadcrumb();
				/** This action is documented in inc/extra.php */
				do_action( 'buddyx_after_breadcrumb' );
			}

			// The trail already names the category, so the chip would repeat it right below.
			if ( ! $show_breadcrumbs ) {
				get_template_part( 'template-parts/content/entry_categories', get_post_type() );
			}

			get_template_part( 'template-parts/content/entry_title', get_post_type() );

			get_template_part( 'template-parts/content/entry_meta', get_post_type() );
			?>
		</header><!-- .entry-header -->

	</div><!-- .buddyx-post-section -->
	<?php
}
