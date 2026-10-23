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
	if ( is_singular() ) {
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
				esc_html( get_the_title() )
			)
		);
	} else {
		// the_excerpt() wraps its own <p>; another one around it nests paragraphs.
		the_excerpt();
	}
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'buddyx' ),
			'after'  => '</div>',
		)
	);
	?>
</div><!-- .entry-content -->

<?php do_action( 'buddyx_entry_content_after' ); ?>
