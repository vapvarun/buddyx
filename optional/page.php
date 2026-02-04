<?php
/**
 * The template for displaying all pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );

?>
	<?php do_action( 'buddyx_before_content' ); ?>

		<main id="primary" class="site-main">
			<?php

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content/entry', 'page' );
			}
			?>
		</main><!-- #primary -->
		<?php get_sidebar();?>

	<?php do_action( 'buddyx_after_content' ); ?>
<?php
get_footer();
