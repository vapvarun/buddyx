<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );
buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );
global $bp;
$buddypress_sidebar         = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );
$buddypress_members_sidebar = get_theme_mod( 'buddypress_members_sidebar_option', buddyx_defaults( 'buddypress-members-sidebar-option' ) );
$buddypress_groups_sidebar  = get_theme_mod( 'buddypress_groups_sidebar_option', buddyx_defaults( 'buddypress-groups-sidebar-option' ) );

?>
	<?php do_action( 'buddyx_sub_header' ); ?>

	<?php do_action( 'buddyx_before_content' ); ?>

	<?php if ( is_active_sidebar( 'buddypress-sidebar-left' ) ) { ?>
		<?php if ( bp_is_current_component( 'members' ) && ! bp_is_user() && ( $buddypress_members_sidebar == 'left' || $buddypress_members_sidebar == 'both' ) ) : ?>
			<aside id="secondary" class="left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_buddypress_left_sidebar(); ?>
				</div>
			</aside>
		<?php elseif ( bp_is_current_component( 'groups' ) && ! bp_is_group() && ! bp_is_group_create() && ! bp_is_user() && ( $buddypress_groups_sidebar == 'left' || $buddypress_groups_sidebar == 'both' ) ) : ?>
			<aside id="secondary" class="left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_buddypress_left_sidebar(); ?>
				</div>
			</aside>
		<?php elseif ( bp_is_current_component( 'activity' ) && ! bp_is_user() && ( $buddypress_sidebar == 'left' || $buddypress_sidebar == 'both' ) ) : ?>
			<aside id="secondary" class="left-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php buddyx()->display_buddypress_left_sidebar(); ?>
				</div>
			</aside>
		<?php endif; ?>
	<?php } ?>

	<main id="primary" class="site-main">

		<?php
		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content-buddypress' );
			}

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

	</main><!-- #primary -->

	<?php if ( ! bp_is_group_single() && ! bp_is_group_create() ) : ?>
        
		<?php get_sidebar( 'buddypress' ); ?>
        
	<?php endif; ?>

	<?php do_action( 'buddyx_after_content' ); ?>
	</div></div>
<?php
get_footer();
