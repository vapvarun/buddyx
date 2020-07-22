<?php
/**
 * Template part for displaying a post's content
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<div class="entry-content">

	<?php
	if ( has_post_thumbnail() && ! post_password_required() && is_singular() ) {

		?>
	
		<figure class="featured-media">
	
			<div class="featured-media-inner section-inner">
	
				<?php the_post_thumbnail(); ?>
	
			</div><!-- .featured-media-inner -->
	
		</figure><!-- .featured-media -->
	
		<?php
	}

	the_content();
		
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'buddyx' ),
			'after'  => '</div>',
		)
	);
	?>
</div><!-- .entry-content -->
