<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );
buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

if ( get_post_type() == 'post' ) {
	$default_sidebar = get_theme_mod( 'single_post_sidebar_option', buddyx_defaults( 'single-post-sidebar-option' ) );
} else {
	$default_sidebar = get_theme_mod( 'sidebar_option', buddyx_defaults( 'sidebar-option' ) );
}

$single_post_content_width = '';
$classes                   = '';

if ( get_post_type() == 'post' ) {
	$single_post_content_width = get_theme_mod( 'single_post_content_width', buddyx_defaults( 'single-post-content-width' ) );

	// Sidebar Classes
	if ( $default_sidebar == 'left' ) {
		$classes = 'has-single-post-left-sidebar';
	} elseif ( $default_sidebar == 'right' ) {
		$classes = 'has-single-post-right-sidebar';
	} elseif ( $default_sidebar == 'both' ) {
		$classes = 'has-single-post-both-sidebar';
	} else {
		$classes = 'has-single-post-no-sidebar';
	}
}
?>
<div class="single-post-main-wrapper buddyx-content--<?php echo esc_attr( $single_post_content_width ); ?> <?php echo esc_attr( $classes ); ?>">

	<?php do_action( 'buddyx_sub_header' ); ?>

	<?php
	if ( get_post_type() == 'post' ) {
		get_template_part( 'template-parts/content/entry-header', get_post_type() );
	}
	?>

	<?php do_action( 'buddyx_before_content' ); ?>

	<?php if ( $default_sidebar == 'left' || $default_sidebar == 'both' ) : ?>
		<aside id="secondary" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', get_post_type() );
		}
		?>

	</main><!-- #primary -->

	<?php if ( $default_sidebar == 'right' || $default_sidebar == 'both' ) : ?>
		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_right_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<?php do_action( 'buddyx_after_content' ); ?>
</div>
<?php
get_footer();
