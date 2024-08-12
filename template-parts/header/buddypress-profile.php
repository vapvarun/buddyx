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
		<a class="bp-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_messages_slug() ); ?>" title="<?php esc_attr_e( 'Messages', 'buddyx' ); ?>">
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
		<a class="bp-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>" title="<?php esc_attr_e( 'Notifications', 'buddyx' ); ?>">
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
		if ( function_exists( 'buddypress' ) && version_compare( buddypress()->version, '12.0', '>=' ) ) {
			$user_link = function_exists( 'bp_members_get_user_url' ) ? bp_members_get_user_url( get_current_user_id() ) : '#';
		} else {
			$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
		}
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
	// Login Page Redirect.
	$login_page_id  = get_theme_mod( 'buddyx_login_page', 0 );
	$login_page_url = ( $login_page_id ) ? get_permalink( $login_page_id ) : wp_login_url();

	// Register Page Redirect.
	$registration_page_id  = get_theme_mod( 'buddyx_registration_page', 0 );
	$registration_page_url = ( $registration_page_id ) ? get_permalink( $registration_page_id ) : wp_registration_url();

	?>
	<?php if ( true === get_theme_mod( 'site_login_link', true ) ) : ?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $login_page_url ); ?>" class="btn-login" title="<?php esc_attr_e( 'Login', 'buddyx' ); ?>">
				<span class="fa fa-user"></span><?php esc_html_e( 'Log in', 'buddyx' ); ?>
			</a>
		</div>
	<?php endif; ?>
	<?php
	if ( get_option( 'users_can_register' ) && true === get_theme_mod( 'site_register_link', true ) ) {
		?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $registration_page_url ); ?>" class="btn-register" title="<?php esc_attr_e( 'Register', 'buddyx' ); ?>">
				<span class="fa fa-sign-in"></span><?php esc_html_e( 'Register', 'buddyx' ); ?>
			</a>
		</div>
		<?php
	} elseif ( function_exists( 'bp_is_active' ) && bp_get_option( 'bp-enable-membership-requests' ) && true === get_theme_mod( 'site_register_link', true ) ) {
		?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $registration_page_url ); ?>" class="btn-register" title="<?php esc_attr_e( 'Register', 'buddyx' ); ?>">
				<span class="fa fa-sign-in"></span><?php esc_html_e( 'Register', 'buddyx' ); ?>
			</a>
		</div>
		<?php
	}
}
