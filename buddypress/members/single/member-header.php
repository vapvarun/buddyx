<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>

<div class="item-header-cover-image-wrapper hide-header-cover-image">
	<div id="item-header-cover-image">
		<div id="item-header-avatar">
				<?php if ( bp_is_my_profile() && ! bp_disable_avatar_uploads() ) { ?>
						<a href="<?php bp_members_component_link( 'profile', 'change-avatar' ); ?>" class="link-change-profile-image bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Change Profile Photo', 'buddyx' ); ?>">
							<?php
							if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
								?>
									<i class="bb-icon-bf bb-icon-camera"></i>
								<?php } else { ?>
									<i class="fa fa-camera"></i>
								<?php
								}
								?>
						</a>
				<?php } ?>
				<?php bp_displayed_user_avatar( 'type=full' ); ?>
		</div><!-- #item-header-avatar -->

		<div id="item-header-content">

			<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
				<h2 class="user-nicename">@<?php bp_displayed_user_mentionname(); ?></h2>
			<?php endif; ?>

			<?php
			if ( is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ) {
				if ( true === bp_member_type_enable_disable() && true === bp_member_type_display_on_profile() ) {
					echo bp_get_user_member_type( bp_displayed_user_id() );
				}
			}
			?>

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

			<?php
			if ( function_exists( 'bp_get_user_social_networks_urls' ) ) :
				echo bp_get_user_social_networks_urls(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
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

				if ( function_exists( 'bp_nouveau_member_header_bubble_buttons' ) ) {
					bp_nouveau_member_header_bubble_buttons(
						array(
							'container'         => 'div',
							'button_element'    => 'button',
							'container_classes' => array( 'bb_more_options', 'header-dropdown' ),
							'is_tooltips'       => false,
							'button_attr'       => array(
								'hover_type' => 'static',
							),
						)
					);
				}
				?>
			</div><!-- .member-header-actions-wrap -->

		</div><!-- #item-header-content -->

	</div><!-- #item-header-cover-image -->
</div><!-- .item-header-cover-image-wrapper -->
