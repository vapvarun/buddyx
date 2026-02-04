<?php
/**
 * Template Name: Custom Page Template
 *
 * When active, by adding the heading above and providing a custom name
 * this template becomes available in a drop-down panel in the editor.
 *
 * Filename can be anything.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/#creating-custom-page-templates-for-global-use
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
