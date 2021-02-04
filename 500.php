<?php
/**
 * The template for displaying 500 pages (internal server errors)
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );

?>
	<?php do_action( 'buddyx_before_content' ); ?>
	
	<main id="primary" class="site-main">
		<?php get_template_part( 'template-parts/content/error', '500' ); ?>
	</main><!-- #primary -->

	<?php do_action( 'buddyx_after_content' ); ?>
<?php
get_footer();
