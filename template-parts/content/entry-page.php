<?php
/**
 * Template part for displaying a post's content
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<?php do_action( 'buddyx_entry_content_before' ); ?>

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
	// Show comments only when the post type supports it and when comments are open or at least one comment exists.
	if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
	?>
</div><!-- .entry-content -->

<?php do_action( 'buddyx_entry_content_after' ); ?>
