<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

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
	<?php echo $amp_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed AMP markup built above. ?>
	
	<button id="menu-toggle" class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'buddyx' ); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php echo $is_amp ? $amp_attrs : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed AMP attributes built above. ?>
	>
	<?php echo buddyx_get_icon( 'bars' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from the theme icon map. ?>
	</button>

	<?php
	// One menu for every screen size: below 768px this container is the off-canvas
	// panel; the parts marked bx-mobile-only are hidden on desktop.
	?>
	<div class="primary-menu-container">
		<div class="mobile-menu-heading bx-mobile-only">
			<h3 class="menu-title"><?php esc_html_e( 'Menu', 'buddyx' ); ?></h3>
			<a href="<?php echo esc_url( '#' ); ?>" class="menu-close" <?php echo $is_amp ? $amp_attrs : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed AMP attributes built above. ?>><?php esc_html_e( 'Close', 'buddyx' ); ?></a>
		</div>

		<?php
		// Mobile user section - only process if user is logged in.
		if ( is_user_logged_in() ) {
			$mobile_user     = wp_get_current_user();
			$current_user_id = get_current_user_id();

			$user_link = buddyx_member_url( $current_user_id );

			// Get avatar once.
			$user_avatar = get_avatar( $current_user_id, 100 );

			// Mobile user profile area.
			?>
			<div class="buddyx-mobile-user bx-mobile-only">
				<div class="user-wrap">
					<a href="<?php echo esc_url( $user_link ); ?>"><?php echo wp_kses_post( $user_avatar ); ?></a>
					<div>
						<a href="<?php echo esc_url( $user_link ); ?>"><span class="user-name"><?php echo esc_html( $mobile_user->display_name ); ?></span></a>
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

		buddyx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) );
		?>
		<div class="bx-mobile-only">
			<?php
			// Member account menu - only for logged-in members when the menu exists.
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

			// Visitor color-mode toggle inside the mobile menu.
			// Component decides whether to render based on customizer settings.
			/**
			 * Fires inside the mobile navigation actions (template-parts/header/navigation.php).
			 */
			do_action( 'buddyx_mobile_menu_actions' );
			?>
		</div>
	</div>

	<div class="buddypress-icons-wrapper">
		<?php buddyx_site_menu_icon(); ?>
		<?php get_template_part( 'template-parts/header/buddypress-profile' ); ?>
	</div>
</nav><!-- #site-navigation -->
