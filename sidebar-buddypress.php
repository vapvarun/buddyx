<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

if ( ! buddyx()->is_buddypress_right_sidebar_active() ) {
	return;
}

buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

?>
<aside id="secondary" class="bp-single-sidebar widget-area">
	<div class="sticky-sidebar">
		<?php dynamic_sidebar( 'buddypress-sidebar-right' ); ?>
	</div>
</aside><!-- #secondary -->
