<?php
/**
 * Template Name: Full Width - Small container
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

<div class="single-post-main-wrapper buddyx-content--small">

	<?php do_action( 'buddyx_sub_header' ); ?>

	<?php
	if ( get_post_type() == 'post' ) {
		get_template_part( 'template-parts/content/entry-header', get_post_type() );
	}
	?>
	
	<?php do_action( 'buddyx_before_content' ); ?>
	
	<main id="primary" class="site-main">
		
		<?php
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', get_post_type() );

		}
		?>

	</main><!-- #primary -->

	<?php do_action( 'buddyx_after_content' ); ?>

</div>
<?php
get_footer();
