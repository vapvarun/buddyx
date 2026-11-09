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
	<?php
	/**
	 * Fires immediately inside <head>.
	 */
	do_action( 'buddyx_head_top' );
	?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php
	/**
	 * Fires at the end of <head>, before </head>.
	 */
	do_action( 'buddyx_head_bottom' );
	?>
</head>

<?php $classes = get_body_class(); ?>
<body class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

<?php
/**
 * Fires right after <body> opens (also fires buddyx_wp_body_open).
 */
do_action( 'buddyx_body_top' );
?>

<?php buddyx_site_loader(); ?>
<?php buddyx_wp_body_open(); ?>
<div id="page" class="site">
<?php
/**
 * Fires at the top of the #page wrapper.
 */
do_action( 'buddyx_page_top' );
?>
	<a class="skip-link screen-reader-text" href="<?php echo esc_url( '#primary' ); ?>"><?php esc_html_e( 'Skip to content', 'buddyx' ); ?></a>

	<?php
	/**
	 * Fires before the <header id="masthead"> element.
	 */
	do_action( 'buddyx_header_before' );
	?>

	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
		<div class="site-header-wrapper">
			<div class="container">
				<header id="masthead" class="site-header">
					<?php get_template_part( 'template-parts/header/custom_header' ); ?>

					<?php get_template_part( 'template-parts/header/branding' ); ?>

					<?php get_template_part( 'template-parts/header/navigation' ); ?>
				</header><!-- #masthead -->
			</div>
		</div>
	<?php } ?>

	<?php
	/**
	 * Fires after the </header> element.
	 */
	do_action( 'buddyx_header_after' );
	?>

<?php
if ( ! in_array( 'page-template-full-width', $classes, true ) ) {
	?>
	<div class="container">
<?php } ?>
