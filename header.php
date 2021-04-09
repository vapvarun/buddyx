<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php buddyx_site_loader(); ?>
<?php buddyx_wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="<?php echo esc_url( '#primary' ); ?>"><?php esc_html_e( 'Skip to content', 'buddyx' ); ?></a>

	<div class="site-header-wrapper">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
			<div class="container">
				<header id="masthead" class="site-header">
					<?php get_template_part( 'template-parts/header/custom_header' ); ?>

					<?php get_template_part( 'template-parts/header/branding' ); ?>

					<?php get_template_part( 'template-parts/header/navigation' ); ?>
				</header><!-- #masthead -->
			</div>
		<?php } ?>
	</div>
<?php
$classes = get_body_class();
if ( ! in_array( 'page-template-full-width', $classes ) ) {
	?>
	<div class="container">
<?php } ?>
