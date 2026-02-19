<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

if ( ! buddyx()->is_primary_nav_menu_active() ) {
	// return;
}

// Pre-fetch common variables.
$is_amp    = buddyx()->is_amp();
$amp_state = '';
$amp_attrs = '';

// Set up AMP attributes if needed.
if ( $is_amp ) {
	$amp_state = '<amp-state id="siteNavigationMenu">
        <script type="application/json">
            {
                "expanded": false
            }
        </script>
    </amp-state>';

	$amp_attrs = 'on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
        [aria-expanded]="siteNavigationMenu.expanded ? \'true\' : \'false\'"';
}

// Navigation class - prepare once, use multiple times.
$nav_class = 'main-navigation nav--toggle-sub nav--toggle-small';

?>

<nav id="site-navigation" class="<?php echo esc_attr( $nav_class ); ?>" aria-label="<?php esc_attr_e( 'Main menu', 'buddyx' ); ?>"
	<?php if ( $is_amp ) : ?>
	[class]=" siteNavigationMenu.expanded ? '<?php echo esc_attr( $nav_class ); ?> nav--toggled-on' : '<?php echo esc_attr( $nav_class ); ?>' "
	<?php endif; ?>
>
	<?php echo $amp_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	
	<div class="buddypress-icons-wrapper buddyx-mobile-icon">
		<?php buddyx_site_menu_icon(); ?>
		<?php get_template_part( 'template-parts/header/buddypress-profile' ); ?>
	</div>

	<button id="menu-toggle" class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'buddyx' ); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php echo $is_amp ? $amp_attrs : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	>
	<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container buddyx-mobile-menu">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e( 'Menu', 'buddyx' ); ?></h3>
			<a href="<?php echo esc_url( '#' ); ?>" class="menu-close" <?php echo $is_amp ? $amp_attrs : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Close', 'buddyx' ); ?></a>
		</div>

		<?php
		// Mobile user section - only process if user is logged in.
		if ( is_user_logged_in() ) {
			$current_user    = wp_get_current_user();
			$current_user_id = get_current_user_id();

			// Determine user profile URL based on BP version - do this once.
			if ( function_exists( 'buddypress' ) && version_compare( buddypress()->version, '12.0', '>=' ) ) {
				$user_link = function_exists( 'bp_members_get_user_url' ) ? bp_members_get_user_url( $current_user_id ) : get_author_posts_url( $current_user_id );
			} else {
				$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $current_user_id ) : get_author_posts_url( $current_user_id );
			}

			// Get avatar once.
			$user_avatar = get_avatar( $current_user_id, 100 );

			// Mobile user profile area.
			?>
			<div class="buddyx-mobile-user">
				<div class="user-wrap">
					<a href="<?php echo esc_url( $user_link ); ?>"><?php echo wp_kses_post( $user_avatar ); ?></a>
					<div>
						<a href="<?php echo esc_url( $user_link ); ?>"><span class="user-name"><?php echo esc_html( $current_user->display_name ); ?></span></a>
						<?php
						if ( function_exists( 'bp_is_active' ) && bp_is_active( 'settings' ) ) {
							$settings_link = trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() );
							?>
							<div class="my-account-link"><a class="ab-item" aria-haspopup="true" href="<?php echo esc_url( $settings_link ); ?>"><?php esc_html_e( 'My Account', 'buddyx' ); ?></a></div>
							<?php
						}
						?>
					</div>
				</div>
				<hr />
			</div>
			<?php
		}

		// Mobile primary menu.
		buddyx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) );

		// Mobile user profile menu - only process if user is logged in and menu exists.
		if ( is_user_logged_in() && has_nav_menu( 'user_menu' ) ) {
			echo '<hr />';
			wp_nav_menu(
				array(
					'theme_location' => 'user_menu',
					'menu_id'        => 'mobile-user-profile-menu',
					'fallback_cb'    => '',
					'container'      => false,
					'menu_class'     => 'mobile-user-profile-menu menu',
				)
			);
		}
		?>
	</div>

	<div class="primary-menu-container buddyx-desktop-menu">
		<?php buddyx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</div>
	<div class="buddypress-icons-wrapper">
		<div class="desktop-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
		<?php get_template_part( 'template-parts/header/buddypress-profile' ); ?>
	</div>
</nav><!-- #site-navigation -->
