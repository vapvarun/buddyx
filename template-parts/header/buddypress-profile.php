<?php
/**
 * BuddyX notification nav
 *
 * Displays in the notification navbar
 *
 * @package buddyx
 * @since 1.0.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// User Messages.
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'messages' ) ) { ?>
	<div class="bp-msg">
		<a class="bp-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_messages_slug() ); ?>">
			<span class="fa fa-envelope"></span>
			<?php if ( messages_get_unread_count( bp_loggedin_user_id() ) > 0 ) : ?>
				<?php if ( messages_get_unread_count( bp_loggedin_user_id() ) > 9 ) : ?>
					<sup class="count"><?php esc_html_e( '9+', 'buddyx' ); ?></sup>
				<?php else : ?>
					<sup class="count"><?php echo esc_html( messages_get_unread_count() ); ?></sup>
				<?php endif; ?>
			<?php endif; ?>
		</a>
	</div>
	<?php
}
// User notifications.
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'notifications' ) ) {
	global $bp;
	?>
	<div class="user-notifications">
		<a class="bp-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>" title="<?php esc_attr( 'Notifications', 'buddyx' ); ?>">
			<span class="fa fa-bell"></span>
			<?php if ( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) > 0 ) : ?>
				<?php if ( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) > 9 ) : ?>
					<sup class="count"><?php esc_html_e( '9+', 'buddyx' ); ?></sup>
				<?php else : ?>
					<sup class="count"><?php echo esc_html( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) ); ?></sup>
				<?php endif; ?>
			<?php endif; ?>
		</a>
		<div id="bp-notify" class="bp-header-submenu bp-dropdown bp-notify">
			<?php
			if ( bp_has_notifications(
				array(
					'user_id'  => bp_loggedin_user_id(),
					'per_page' => 10,
					'max'      => 10,
				)
			) ) :
				?>
				<div class="bp-dropdown-inner">
					<?php
					while ( bp_the_notifications() ) :
						bp_the_notification();
						?>
						<div class="dropdown-item">
							<div class="dropdown-item-title notification ellipsis"><?php bp_the_notification_description(); ?></div>
							<p class="mute"><?php bp_the_notification_time_since(); ?></p>
						</div>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<div class="alert-message">
					<div class="alert alert-warning" role="alert"><?php esc_html_e( 'No notifications found.', 'buddyx' ); ?></div>
				</div>
			<?php endif; ?>
			<div class="dropdown-footer">
				<a href="<?php echo esc_url( trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() . '/unread' ) ); ?>" class="button"><?php esc_html_e( 'All Notifications', 'buddyx' ); ?></a>
			</div>
		</div>
	</div>
	<?php
}
// User Account Details.
if ( is_user_logged_in() ) {
	$loggedin_user = wp_get_current_user();
	if ( ( $loggedin_user instanceof WP_User ) ) {
		$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
		echo '<div class="user-link-wrap">';
		echo '<a class="user-link" href="' . esc_url( $user_link ) . '">';
		?>
	<span class="bp-user"><?php echo esc_html( $loggedin_user->display_name ); ?></span>
		<?php
		echo get_avatar( $loggedin_user->user_email, 100 );
		echo '</a>';
		wp_nav_menu(
			array(
				'theme_location' => 'user_menu',
				'menu_id'        => 'user-profile-menu',
				'fallback_cb'    => '',
				'container'      => false,
				'menu_class'     => 'user-profile-menu',
			)
		);
		echo '</div>';
	}
} else {
	global $wbtm_buddyx_settings;
	$login_page_url = wp_login_url();
	if ( isset( $settings['buddyx_pages']['buddyx_login_page'] ) && ( $wbtm_buddyx_settings['buddyx_pages']['buddyx_login_page'] != '-1' ) ) {
		$login_page_id  = $wbtm_buddyx_settings['buddyx_pages']['buddyx_login_page'];
		$login_page_url = get_permalink( $login_page_id );
	}
		$registration_page_url = wp_registration_url();
	if ( isset( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] ) && ( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] != '-1' ) ) {
		$registration_page_id  = $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'];
		$registration_page_url = get_permalink( $registration_page_id );
	}
	?>
	<?php if ( true == get_theme_mod( 'site_login_link', true ) ) : ?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $login_page_url ); ?>" class="btn-login" title="<?php esc_attr_e( 'Login', 'buddyx' ); ?>"> <span class="fa fa-user"></span><?php esc_html_e( 'Log in', 'buddyx' ); ?></a>
		</div>
	<?php endif; ?>
	<?php
	if ( get_option( 'users_can_register' ) && true == get_theme_mod( 'site_register_link', true ) ) {
		?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $registration_page_url ); ?>" class="btn-register" title="<?php esc_attr_e( 'Register', 'buddyx' ); ?>"><span class="fa fa-address-book"></span><?php esc_html_e( 'Register', 'buddyx' ); ?></a>
		</div>
		<?php
	} elseif ( function_exists( 'bp_is_active' ) && bp_get_option( 'bp-enable-membership-requests' ) && true === get_theme_mod( 'site_register_link', true ) ) {
		?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $registration_page_url ); ?>" class="btn-register" title="<?php esc_attr_e( 'Register', 'buddyx' ); ?>"><span class="fa fa-address-book"></span><?php esc_html_e( 'Register', 'buddyx' ); ?></a>
		</div>
		<?php
	}
}
