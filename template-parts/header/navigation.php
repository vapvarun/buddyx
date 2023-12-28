<?php

/**
 * Template part for displaying the header navigation menu
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

if (!buddyx()->is_primary_nav_menu_active()) {
	// return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e('Main menu', 'buddyx'); ?>" <?php
																																				if (buddyx()->is_amp()) {
																																				?> [class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' " <?php
																																											}
																																												?>>
	<?php
	if (buddyx()->is_amp()) {
	?>
		<amp-state id="siteNavigationMenu">
			<script type="application/json">
				{
					"expanded": false
				}
			</script>
		</amp-state>
	<?php
	}
	?>

	<div class="buddypress-icons-wrapper buddyx-mobile-icon">
		<?php buddyx_site_menu_icon(); ?>
		<?php get_template_part('template-parts/header/buddypress-profile'); ?>
	</div>

	<button id="menu-toggle" class="menu-toggle" aria-label="<?php esc_attr_e('Open menu', 'buddyx'); ?>" aria-controls="primary-menu" aria-expanded="false" <?php
																																								if (buddyx()->is_amp()) {
																																								?> on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )" [aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'" <?php
																																											}
																																												?>>
		<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container buddyx-mobile-menu">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e('Menu', 'buddyx'); ?></h3>
			<a href="<?php echo esc_url('#'); ?>" class="menu-close" <?php if (buddyx()->is_amp()) { ?> on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )" [aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'" <?php } ?>><?php esc_html_e('Close', 'buddyx'); ?></a>
		</div>
		<div class="buddyx-mobile-user">
			<?php if (is_user_logged_in()) : ?>
				<?php
				$current_user = wp_get_current_user();
				$default_link = get_author_posts_url(get_current_user_id());
				$user_link = $default_link;

				if (function_exists('buddypress')) {
					$bp_func = version_compare(buddypress()->version, '12.0', '>=') ? 'bp_members_get_user_url' : 'bp_core_get_user_domain';
					$user_link = function_exists($bp_func) ? call_user_func($bp_func, get_current_user_id()) : $default_link;
				}
				?>
				<div class="user-wrap">
					<a href="<?php echo esc_url($user_link); ?>"><?php echo get_avatar(get_current_user_id(), 100); ?></a>
					<div>
						<a href="<?php echo esc_url($user_link); ?>">
							<span class="user-name"><?php echo esc_html($current_user->display_name); ?></span>
						</a>
						<?php if (function_exists('bp_is_active') && bp_is_active('settings')) : ?>
							<?php $settings_link = trailingslashit(bp_loggedin_user_domain() . bp_get_settings_slug()); ?>
							<div class="my-account-link">
								<a class="ab-item" aria-haspopup="true" href="<?php echo esc_url($settings_link); ?>">
									<?php esc_html_e('My Account', 'buddyx'); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<hr />
		</div>

		<?php buddyx()->display_primary_nav_menu(array('menu_id' => 'primary-menu')); ?>
		<?php
		if (is_user_logged_in()) {
			if (has_nav_menu('user_menu')) {
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
		}
		?>
	</div>

	<div class="primary-menu-container buddyx-desktop-menu">
		<?php buddyx()->display_primary_nav_menu(array('menu_id' => 'primary-menu')); ?>
	</div>
	<div class="buddypress-icons-wrapper">
		<div class="desktop-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
		<?php get_template_part('template-parts/header/buddypress-profile'); ?>
	</div>
</nav><!-- #site-navigation -->