<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

namespace BuddyX\Buddyx;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

$woocommerce_sidebar = get_theme_mod( 'woocommerce_sidebar_option', buddyx_defaults( 'woocommerce-sidebar-option' ) );

do_action( 'buddyx_before_content' );

if ( class_exists( 'WooCommerce' ) ) { ?>
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
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );

?>

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

	<?php

	if ( class_exists( 'WooCommerce' ) ) {
		?>
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

	/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
