<?php
/**
 * Template Name: Left Sidebar
 *
 * Template Post Type: post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );
buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

?>

<div class="single-post-main-wrapper buddyx-content--large">

	<?php
	/** This action is documented in archive.php */
	do_action( 'buddyx_sub_header' );
	?>

	<?php
	if ( 'post' === get_post_type() ) {
		get_template_part( 'template-parts/content/entry-header', get_post_type() );
	}
	?>
	
	<?php
	/** This action is documented in 404.php */
	do_action( 'buddyx_before_content' );
	?>

	<aside id="secondary-left" class="left-sidebar widget-area">
		<div class="sticky-sidebar">
			<?php buddyx()->display_left_sidebar(); ?>
		</div>
	</aside>
	
	<main id="primary" class="site-main">
		
		<?php
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', get_post_type() );

		}
		?>

	</main><!-- #primary -->

	<?php
	/** This action is documented in 404.php */
	do_action( 'buddyx_after_content' );
	?>

</div>
<?php
get_footer();
