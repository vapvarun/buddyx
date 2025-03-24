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

// Cache the current user ID once.
$current_user_id = 0;

if ( is_user_logged_in() ) {
	if ( function_exists( 'bp_loggedin_user_id' ) ) {
		$current_user_id = bp_loggedin_user_id();
	} else {
		$current_user_id = get_current_user_id();
	}
}

// Only proceed with BuddyPress components if user is logged in and BuddyPress exists.
if ( class_exists( 'BuddyPress' ) && $current_user_id ) {
	global $bp;

	// Create an array to store all user data we'll need.
	$user_data = array(
		'messages_count'      => 0,
		'notifications_count' => 0,
		'messages_url'        => '',
		'notifications_url'   => '',
	);

	// Get messages data if component is active.
	if ( bp_is_active( 'messages' ) ) {
		$user_data['messages_count'] = messages_get_unread_count( $current_user_id );
		$user_data['messages_url']   = bp_loggedin_user_domain() . bp_get_messages_slug();

		// Output messages icon with counter.
		?>
		<div class="bp-msg">
			<a class="bp-icon-wrap" href="<?php echo esc_url( $user_data['messages_url'] ); ?>" title="<?php esc_attr_e( 'Messages', 'buddyx' ); ?>">
				<span class="fa fa-envelope"></span>
				<?php if ( $user_data['messages_count'] > 0 ) : ?>
					<sup class="count"><?php echo esc_html( $user_data['messages_count'] > 9 ? '9+' : $user_data['messages_count'] ); ?></sup>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}

	// Get notifications data if component is active.
	if ( bp_is_active( 'notifications' ) ) {
		$user_data['notifications_count'] = bp_notifications_get_unread_notification_count( $current_user_id );
		$user_data['notifications_url']   = bp_loggedin_user_domain() . $bp->notifications->slug;

		// Output notifications with dropdown.
		?>
		<div class="user-notifications">
			<a class="bp-icon-wrap" href="<?php echo esc_url( $user_data['notifications_url'] ); ?>" title="<?php esc_attr_e( 'Notifications', 'buddyx' ); ?>">
				<span class="fa fa-bell"></span>
				<?php if ( $user_data['notifications_count'] > 0 ) : ?>
					<sup class="count"><?php echo esc_html( $user_data['notifications_count'] > 9 ? '9+' : $user_data['notifications_count'] ); ?></sup>
				<?php endif; ?>
			</a>
			<div id="bp-notify" class="bp-header-submenu bp-dropdown bp-notify">
				<?php
				// Limit database query to only fetch what we need.
				if ( bp_has_notifications(
					array(
						'user_id'  => $current_user_id,
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
}

// User Account Section.
if ( is_user_logged_in() ) {
	$loggedin_user = wp_get_current_user();
	if ( ( $loggedin_user instanceof WP_User ) ) {
		// Determine user profile URL based on BP version.
		// Use function_exists check to prevent errors.
		if ( function_exists( 'buddypress' ) && version_compare( buddypress()->version, '12.0', '>=' ) ) {
			$user_link = function_exists( 'bp_members_get_user_url' ) ? bp_members_get_user_url( $current_user_id ) : '#';
		} else {
			$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $current_user_id ) : '#';
		}

		// Get avatar once and store in variable to avoid multiple function calls.
		$user_avatar = get_avatar( $loggedin_user->user_email, 100 );

		echo '<div class="user-link-wrap">';
		echo '<a class="user-link" href="' . esc_url( $user_link ) . '">';
		?>
		<span class="bp-user"><?php echo esc_html( $loggedin_user->display_name ); ?></span>
		<?php
		echo wp_kses_post( $user_avatar );
		echo '</a>';

		// Load user profile menu only when needed.
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
	// Not logged in - show login and register buttons.
	// Cache theme mod values to prevent multiple DB calls.
	$login_enabled    = get_theme_mod( 'site_login_link', true );
	$register_enabled = get_theme_mod( 'site_register_link', true );

	if ( $login_enabled ) {
		// Login Page URL - get once.
		$login_page_id  = get_theme_mod( 'buddyx_login_page', 0 );
		$login_page_url = $login_page_id ? get_permalink( $login_page_id ) : wp_login_url();
		?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $login_page_url ); ?>" class="btn-login" title="<?php esc_attr_e( 'Login', 'buddyx' ); ?>">
				<span class="fa fa-user"></span><?php esc_html_e( 'Log in', 'buddyx' ); ?>
			</a>
		</div>
		<?php
	}

	// Registration link - check once if registration is allowed.
	$can_register = get_option( 'users_can_register' ) ||
		( function_exists( 'bp_is_active' ) && bp_get_option( 'bp-enable-membership-requests' ) );

	if ( $can_register && $register_enabled ) {
		$registration_page_id  = get_theme_mod( 'buddyx_registration_page', 0 );
		$registration_page_url = $registration_page_id ? get_permalink( $registration_page_id ) : wp_registration_url();
		?>
		<div class="bp-icon-wrap">
			<a href="<?php echo esc_url( $registration_page_url ); ?>" class="btn-register" title="<?php esc_attr_e( 'Register', 'buddyx' ); ?>">
				<span class="fa fa-sign-in"></span><?php esc_html_e( 'Register', 'buddyx' ); ?>
			</a>
		</div>
		<?php
	}
}