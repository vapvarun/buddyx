<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );
buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

$default_sidebar = get_theme_mod( 'sidebar_option', buddyx_defaults( 'sidebar-option' ) );

?>

	<?php
	/** This action is documented in archive.php */
	do_action( 'buddyx_sub_header' );
	?>
	
	<?php
	/** This action is documented in 404.php */
	do_action( 'buddyx_before_content' );
	?>

	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
		<?php if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() && ( 'left' === $default_sidebar || 'both' === $default_sidebar ) ) : ?>
			<aside id="secondary-left" class="left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_left_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
		<?php
	} else {
		if ( 'left' === $default_sidebar || 'both' === $default_sidebar ) :
			?>
		<aside id="secondary-left" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>
	<?php } ?>
	
	<main id="primary" class="site-main">
		
	<?php
	if ( have_posts() ) {
		?>
			<?php
			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content/entry', 'page' );
			}
			?>
			<?php

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
	} else {
		get_template_part( 'template-parts/content/error' );
	}
	?>

	</main><!-- #primary -->
	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
		<?php if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() && ( 'right' === $default_sidebar || 'both' === $default_sidebar ) ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
		<?php } else { ?>
			<?php if ( 'right' === $default_sidebar || 'both' === $default_sidebar ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
	<?php } ?>

	<?php
	/** This action is documented in 404.php */
	do_action( 'buddyx_after_content' );
	?>
<?php
get_footer();
