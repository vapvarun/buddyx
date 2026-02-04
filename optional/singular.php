<?php
/**
 * The template for displaying all single posts and pages
 *
 * If posts and pages use the same template, singular.php can be used.
 * This template is ignored if single.php and/or page.php is present.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/post-template-files/#singular-php
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

				get_template_part( 'template-parts/content/entry', get_post_type() );
			}
			?>
		</main><!-- #primary -->
		<?php get_sidebar();?>

	<?php do_action( 'buddyx_after_content' ); ?>
<?php
get_footer();
