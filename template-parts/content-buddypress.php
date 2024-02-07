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
	the_content(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'buddyx' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		)
	);

	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'buddyx' ),
			'after'  => '</div>',
		)
	);

	if ( is_singular( get_post_type() ) ) {

		// Show comments only when the post type supports it and when comments are open or at least one comment exists.
		if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
			comments_template();
		}
	}
	?>
</div><!-- .entry-content -->

<?php do_action( 'buddyx_entry_content_after' ); ?>
