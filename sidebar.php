<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

if ( ! buddyx()->is_left_sidebar_active() || ! buddyx()->is_right_sidebar_active() ) {
	return;
}

buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

?>

<?php do_action( 'buddyx_sidebar_before' ); ?>

<aside id="secondary" class="primary-sidebar widget-area">
	<div class="sticky-sidebar">
		<?php
		$sidebar = get_theme_mod( 'sidebar_option' );
		switch ( $sidebar ) {
			case 'left':
				buddyx()->display_left_sidebar();
				break;
			case 'right':
				buddyx()->display_right_sidebar();
				break;
			default:
		}
		?>
	</div>
</aside><!-- #secondary -->

<?php do_action( 'buddyx_sidebar_after' ); ?>
