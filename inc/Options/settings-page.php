<?php
/**
 * Content to display in Theme Settings admin page.
 * React loads in the app container div buddyx-settings-page.
 *
 * @package buddyx
 */

wp_enqueue_style(
	'wp-components'
);
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Theme Settings', 'buddyx' ); ?></h1>
	<div id="buddyx-settings-page"></div>
</div><?php
