<?php
/**
 * Template part for displaying a post's content
 *
 * @package buddyx
 */

namespace Brndle\Brndle;

?>

<div class="entry-content">
	<?php
	the_content(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'buddyx' ),
				[
					'span' => [
						'class' => [],
					],
				]
			),
			get_the_title()
		)
	);

	wp_link_pages(
		[
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'buddyx' ),
			'after'  => '</div>',
		]
	);
	?>
</div><!-- .entry-content -->
