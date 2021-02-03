<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

namespace BuddyX\Buddyx;

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$woocommerce_sidebar = get_theme_mod( 'woocommerce_sidebar_option', buddyx_defaults( 'woocommerce-sidebar-option' ) );

?>

<div class="site-sub-header">
	<div class="container">
		<header class="woocommerce-products-header">
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
				<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<?php
					$breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					buddyx_the_breadcrumb();
				}
				?>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_archive_description.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>
		</header>
	</div>
</div>

<?php

do_action( 'buddyx_before_content' );

if ( class_exists( 'WooCommerce' ) ) {
	?>
	<?php if ( is_woocommerce() ) { ?>
		<?php if ( $woocommerce_sidebar == 'left' || $woocommerce_sidebar == 'both' ) : ?>
			<aside id="secondary" class="woo-left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_woocommerce_left_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
	<?php } ?>
	<?php
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// do_action( 'woocommerce_sidebar' );
?>
<?php if ( class_exists( 'WooCommerce' ) ) { ?>
	<?php if ( is_woocommerce() ) { ?>
		<?php if ( $woocommerce_sidebar == 'right' || $woocommerce_sidebar == 'both' ) : ?>
			<aside id="secondary" class="woo-primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_woocommerce_right_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
	<?php } ?>
	<?php
}

do_action( 'buddyx_after_content' );

get_footer( 'shop' );
