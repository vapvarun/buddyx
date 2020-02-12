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

$post_layout = get_theme_mod( 'blog_layout_option');
$post_per_row = 'column-' . get_theme_mod( 'post_per_row');

?>

	<?php do_action( 'buddyx_sub_header' ); ?>
	
	<?php do_action( 'buddy_before_content' ); ?>

	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
		<?php if ( ! is_woocommerce() && get_theme_mod( 'sidebar_option' ) == 'left' && ! is_cart() && get_theme_mod( 'sidebar_option' ) == 'left' && ! is_checkout() && get_theme_mod( 'sidebar_option' ) == 'left' && ! is_account_page() && get_theme_mod( 'sidebar_option' ) == 'left' || ! is_woocommerce() && get_theme_mod( 'sidebar_option' ) == 'both' && ! is_cart() && get_theme_mod( 'sidebar_option' ) == 'both' && ! is_checkout() && get_theme_mod( 'sidebar_option' ) == 'both' && ! is_account_page() && get_theme_mod( 'sidebar_option' ) == 'both' ) : ?>
			<aside id="secondary" class="left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_left_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
	<?php }else {
		if ( get_theme_mod( 'sidebar_option' ) == 'left' || get_theme_mod( 'sidebar_option' ) == 'both' ) : ?>
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

			//get_template_part( 'template-parts/content/page_header' );

			$classes = get_body_class();
			if(in_array('blog',$classes) || in_array('archive',$classes) || in_array('search',$classes)){ ?>
			<div class="post-layout <?php echo $post_layout . " " . $post_per_row ?>">
			<div class="grid-sizer"></div>
			<?php 
				while ( have_posts() ) {
					the_post();
	
					get_template_part( 'template-parts/content/entry', 'layout' );
				} ?>
				</div>
			<?php 
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
		<?php if ( ! is_woocommerce() && get_theme_mod( 'sidebar_option' ) == 'right' && ! is_cart() && get_theme_mod( 'sidebar_option' ) == 'right' && ! is_checkout() && get_theme_mod( 'sidebar_option' ) == 'right' && ! is_account_page() && get_theme_mod( 'sidebar_option' ) == 'right' || ! is_woocommerce() && get_theme_mod( 'sidebar_option' ) == 'both' && ! is_cart() && get_theme_mod( 'sidebar_option' ) == 'both' && ! is_checkout() && get_theme_mod( 'sidebar_option' ) == 'both' && ! is_account_page() && get_theme_mod( 'sidebar_option' ) == 'both' ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
		<?php }else { ?>
			<?php if ( get_theme_mod( 'sidebar_option' ) == 'right' || get_theme_mod( 'sidebar_option' ) == 'both' ) : ?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
		<?php } ?>

	<?php do_action( 'buddy_after_content' ); ?>
<?php
get_footer();
