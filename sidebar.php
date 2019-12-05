<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyx
 */

namespace Brndle\Brndle;

if ( ! buddyx()->is_primary_sidebar_active() ) {
	return;
}

buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

?>
<aside id="secondary" class="primary-sidebar widget-area">
	<?php buddyx()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
