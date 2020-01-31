<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package buddyx
 */

namespace Brndle\Brndle;

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

	<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'buddyx' ); ?>" aria-controls="primary-menu" aria-expanded="false"
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

	<div class="primary-menu-container">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_attr_e( 'Menu', 'buddyx' ); ?></h3>
			<a href="#" class="menu-close">close</a>
		</div>
		<?php buddyx()->display_primary_nav_menu( [ 'menu_id' => 'primary-menu' ] ); ?>
		<?php site_menu_icon(); ?>
	</div>
	<?php if ( class_exists( 'BuddyPress' ) ) { ?>
		<div class="buddypress-icons-wrapper">
			<?php get_template_part( 'template-parts/header/buddypress-profile' ); ?>
		</div>
	<?php } ?>
</nav><!-- #site-navigation -->
