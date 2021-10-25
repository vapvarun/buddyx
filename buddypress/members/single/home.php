<?php
/**
 * BuddyPress - Members Home
 *
 * @since   1.0.0
 * @version 3.0.0
 */
?>

<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>

<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

	<?php bp_nouveau_member_header_template_part(); ?>

</div><!-- #item-header -->

<div class="site-wrapper member-home">
	<div class="bp-wrap">
		<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

			<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>

		<?php endif; ?>

		<div id="item-body" class="item-body">

			<?php bp_nouveau_member_template_part(); ?>

		</div><!-- #item-body -->
	</div><!-- // .bp-wrap -->
	<?php
	if ( function_exists( 'buddypress' ) && buddypress()->buddyboss ) {
		if ( is_active_sidebar( 'single_member' ) && bp_is_user() && ! bp_is_user_settings() && ! bp_is_user_messages() && ! bp_is_user_notifications() && ! bp_is_user_profile_edit() && ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() && ! bp_is_user_front() ) {
			?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php dynamic_sidebar( 'single_member' ); ?>
				</div>
			</aside>
			<?php
		}
	} elseif ( class_exists( 'BuddyPress' ) ) {
		if ( is_active_sidebar( 'single_member' ) && bp_is_user() && ! bp_is_user_settings() && ! bp_is_user_messages() && ! bp_is_user_notifications() && ! bp_is_user_profile_edit() && ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() && ! bp_is_user_front() && function_exists( 'bp_is_members_invitations_screen' ) && ! bp_is_members_invitations_screen() ) {
			?>
			<aside id="secondary" class="primary-sidebar widget-area">
				<div class="sticky-sidebar">
					<?php dynamic_sidebar( 'single_member' ); ?>
				</div>
			</aside>
			<?php
		}
	}
	?>
</div><!-- .site-wrapper -->

<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>
