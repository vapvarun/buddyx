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

$post_layout = get_theme_mod( 'blog_layout_option', buddyx_defaults( 'blog-layout-option' ) );

?>

	<?php do_action( 'buddyx_sub_header' ); ?>
	
	<?php do_action( 'buddyx_before_content' ); ?>

	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
		<?php if ( ! is_woocommerce() && $default_sidebar == 'left' && ! is_cart() && $default_sidebar == 'left' && ! is_checkout() && $default_sidebar == 'left' && ! is_account_page() && $default_sidebar == 'left' || ! is_woocommerce() && $default_sidebar == 'both' && ! is_cart() && $default_sidebar == 'both' && ! is_checkout() && $default_sidebar == 'both' && ! is_account_page() && $default_sidebar == 'both' ) : ?>
			<aside id="secondary" class="left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_left_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
		<?php
	} else {
		if ( $default_sidebar == 'left' || $default_sidebar == 'both' ) :
			?>
		<aside id="secondary" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?> 
	<?php } ?>
	
	<main id="primary" class="site-main">
		
		<?php
		if ( have_posts() ) {

			$classes = get_body_class();
			if ( in_array( 'blog', $classes ) || in_array( 'archive', $classes ) || in_array( 'search', $classes ) ) {

				$args = array(
					'post_layout' => $post_layout,
					'body_class'  => $classes,
				);
				get_template_part( 'template-parts/layout/entry', $post_layout, $args );


			} else {
				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', get_post_type() );
				}
			}

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

	</main><!-- #primary -->
	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
		<?php if ( ! is_woocommerce() && $default_sidebar == 'right' && ! is_cart() && $default_sidebar == 'right' && ! is_checkout() && $default_sidebar == 'right' && ! is_account_page() && $default_sidebar == 'right' || ! is_woocommerce() && $default_sidebar == 'both' && ! is_cart() && $default_sidebar == 'both' && ! is_checkout() && $default_sidebar == 'both' && ! is_account_page() && $default_sidebar == 'both' ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
		<?php } else { ?>
			<?php if ( $default_sidebar == 'right' || $default_sidebar == 'both' ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
	<?php } ?>

	<?php do_action( 'buddyx_after_content' ); ?>
<?php
get_footer();
