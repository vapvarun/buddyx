<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );
global $bp;
$buddypress_sidebar         = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );
$buddypress_members_sidebar = get_theme_mod( 'buddypress_members_sidebar_option', buddyx_defaults( 'buddypress-members-sidebar-option' ) );
$buddypress_groups_sidebar  = get_theme_mod( 'buddypress_groups_sidebar_option', buddyx_defaults( 'buddypress-groups-sidebar-option' ) );

do_action( 'buddyx_sidebar_before' );

if ( function_exists( 'bp_is_active' ) ) {
	?>

	<?php if ( bp_is_current_component( 'activity' ) && ! bp_is_user() && $buddypress_sidebar == 'right' || bp_is_current_component( 'activity' ) && ! bp_is_user() && $buddypress_sidebar == 'both' ) : ?>

		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_buddypress_right_sidebar(); ?>
			</div>
		</aside>

	<?php elseif ( bp_is_current_component( 'members' ) && ! bp_is_user() && $buddypress_members_sidebar == 'right' || bp_is_current_component( 'members' ) && ! bp_is_user() && $buddypress_members_sidebar == 'both' ) : ?>

		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_buddypress_members_right_sidebar(); ?>
			</div>
		</aside>

	<?php elseif ( bp_is_current_component( 'groups' ) && ! bp_is_group() && ! bp_is_user() && $buddypress_groups_sidebar == 'right' || bp_is_current_component( 'groups' ) && ! bp_is_group() && ! bp_is_user() && $buddypress_groups_sidebar == 'both' ) : ?>

		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php buddyx()->display_buddypress_groups_right_sidebar(); ?>
			</div>
		</aside>

		<?php
	endif;
}

do_action( 'buddyx_sidebar_after' ); ?>
