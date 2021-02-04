<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

if ( ! buddyx()->is_primary_nav_menu_active() ) {
	return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'buddyx' ); ?>"
	<?php
	if ( buddyx()->is_amp() ) {
		?>
		[class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' "
		<?php
	}
	?>
>
	<?php
	if ( buddyx()->is_amp() ) {
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
		<?php get_template_part( 'template-parts/header/buddypress-profile' ); ?>
	</div>

	<button id="menu-toggle" class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'buddyx' ); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php
		if ( buddyx()->is_amp() ) {
			?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php
		}
		?>
	>
	<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container buddyx-mobile-menu">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e( 'Menu', 'buddyx' ); ?></h3>
			<a href="<?php echo esc_url( '#' ); ?>" class="menu-close" <?php if ( buddyx()->is_amp() ) { ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php } ?>><?php esc_html_e( 'Close', 'buddyx' ); ?></a>
		</div>
		<?php buddyx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
		<div class="moible-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
	</div>

	<div class="primary-menu-container buddyx-desktop-menu">
		<?php buddyx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
		<div class="moible-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
	</div>
	<div class="buddypress-icons-wrapper">
		<div class="desktop-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
		<?php get_template_part( 'template-parts/header/buddypress-profile' ); ?>
	</div>
</nav><!-- #site-navigation -->
