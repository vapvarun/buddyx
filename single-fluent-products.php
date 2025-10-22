<?php
/**
 * The template for displaying FluentCart single products
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );
buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

// Get FluentCart product sidebar option
$fluentcart_sidebar = get_theme_mod( 'fluentcart_product_sidebar', 'none' );

?>
<div class="single-post-main-wrapper">

	<?php do_action( 'buddyx_sub_header' ); ?>

	<?php do_action( 'buddyx_before_content' ); ?>

	<?php if ( $fluentcart_sidebar == 'left' || $fluentcart_sidebar == 'both' ) : ?>
		<aside id="secondary" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_fluentcart_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry_content', get_post_type() );

		}
		?>

	</main><!-- #primary -->

	<?php if ( $fluentcart_sidebar == 'right' || $fluentcart_sidebar == 'both' ) : ?>
		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_fluentcart_right_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<?php do_action( 'buddyx_after_content' ); ?>
</div>
<?php
get_footer();
