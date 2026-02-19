<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>
	<?php
	$classes = get_body_class();
	if ( ! in_array( 'page-template-full-width', $classes ) ) {
		?>
		</div><!-- .container -->
	<?php } ?>

	<?php do_action( 'buddyx_footer_before' ); ?>

	<footer id="colophon" class="site-footer">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
			<div class="site-footer-wrapper">
				<div class="container">
					<?php
					$footer_sidebars = array( 'footer-1', 'footer-2', 'footer-3', 'footer-4' );
					$active_sidebars = array_filter( $footer_sidebars, 'is_active_sidebar' );

					if ( ! empty( $active_sidebars ) ) :
						?>
						<div class="footer-inner" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'buddyx' ); ?>">
							<?php foreach ( $active_sidebars as $sidebar ) : ?>
								<div class="footer-widget">
									<?php dynamic_sidebar( $sidebar ); ?>
								</div>
							<?php endforeach; ?>
						</div><!-- .footer-inner -->
					<?php endif; ?>    
				</div><!-- .container -->
			</div><!-- .site-footer-wrapper -->

			<?php get_template_part( 'template-parts/footer/info' ); ?>
		<?php endif; ?>
	</footer><!-- #colophon -->

	<?php do_action( 'buddyx_footer_after' ); ?>

<?php do_action( 'buddyx_page_bottom' ); ?>

</div><!-- #page -->

<div class="mobile-menu-close"></div>

<?php do_action( 'buddyx_body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>
