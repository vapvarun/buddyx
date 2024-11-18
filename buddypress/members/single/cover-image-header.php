<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since 3.0.0
 * @version 7.0.0
 */

?>

<?php if ( ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() ) : ?>
	<div id="cover-image-container">
		<div id="header-cover-image">
			<?php if ( bp_is_my_profile() ) { ?>
				<a href="<?php echo esc_url( bp_get_members_component_link( 'profile', 'change-cover-image' ) ); ?>" class="link-change-cover-image bp-tooltip" data-bp-tooltip-pos="right" data-bp-tooltip="<?php esc_attr_e( 'Change Cover Photo', 'buddyx' ); ?>">
					<i class="fa fa-camera"></i>
				</a>
			<?php } ?>
		</div>
	</div><!-- #cover-image-container -->    

	<div class="item-header-cover-image-wrapper">
		<div id="item-header-cover-image">
			<div id="item-header-avatar">
				<?php if ( bp_is_my_profile() && ! bp_disable_avatar_uploads() ) { ?>
				<a href="<?php bp_members_component_link( 'profile', 'change-avatar' ); ?>" class="link-change-profile-image bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Change Profile Photo', 'buddyx' ); ?>">
					<i class="fa fa-camera"></i>
				</a>
				<?php } ?>
				<div class="avatar-wrap">
					<?php
					buddyx_user_status( bp_displayed_user_id() );
					bp_displayed_user_avatar( 'type=full' );
					?>
				</div>
			</div><!-- #item-header-avatar -->

			<div id="item-header-content">

				<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
					<h2 class="user-nicename">@<?php bp_displayed_user_mentionname(); ?></h2>
				<?php else : ?>
					<h2 class="user-nicename"><?php echo esc_html( bp_get_displayed_user_fullname() ); ?></h2>
				<?php endif; ?>

				<?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>

				<?php if ( bp_nouveau_member_has_meta() ) : ?>
					<div class="item-meta">

						<?php bp_nouveau_member_meta(); ?>

					</div><!-- #item-meta -->
					<div class="buddyx-badge">
						<?php
						if ( function_exists( 'buddyx_profile_achievements' ) ) :
							buddyx_profile_achievements();
						endif;
						?>
					</div><!-- .buddyx-badge -->
				<?php endif; ?>

				<?php
				if ( function_exists( 'bp_member_type_list' ) ) :
					bp_member_type_list(
						bp_displayed_user_id(),
						array(
							'label'        => array(
								'plural'   => __( 'Member Types', 'buddyx' ),
								'singular' => __( 'Member Type', 'buddyx' ),
							),
							'list_element' => 'span',
						)
					);
				endif;
				?>

				<div class="member-header-actions-wrap">
					<?php
					bp_nouveau_member_header_buttons(
						array(
							'container'         => 'ul',
							'button_element'    => 'button',
							'container_classes' => array( 'member-header-actions' ),
						)
					);
					?>
				</div><!-- .member-header-actions-wrap -->

			</div><!-- #item-header-content -->

		</div><!-- #item-header-cover-image -->
	</div><!-- .item-header-cover-image-wrapper -->
	<?php
endif;
?>
