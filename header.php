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
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php
	if ( ! buddyx()->is_amp() ) {
		?>
		<script>document.documentElement.classList.remove( 'no-js' );</script>
		<?php
	}
	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php site_loader(); ?>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'buddyx' ); ?></a>

	<div class="site-header-wrapper">
		<div class="container">
			<header id="masthead" class="site-header">
				<?php get_template_part( 'template-parts/header/custom_header' ); ?>

				<?php get_template_part( 'template-parts/header/branding' ); ?>

				<?php get_template_part( 'template-parts/header/navigation' ); ?>
			</header><!-- #masthead -->
		</div>
    </div>
<?php
$classes = get_body_class();
if(! in_array('page-template-full-width',$classes) ) { ?>
	<div class="container">
<?php } ?>