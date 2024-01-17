<?php
/**
 * Small footer
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Small footer', 'buddyx' ),
	'categories' => array( 'buddyx' ),
	'blockTypes' => array( 'core/template-part/footer' ),
	'content'    => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","right":"0","bottom":"20px","left":"0"},"margin":{"top":"0","bottom":"0"}}},"className":"has-background-color","layout":{"type":"default"}} -->
	<div class="wp-block-group has-background-color" style="margin-top:0;margin-bottom:0;padding-top:20px;padding-right:0;padding-bottom:20px;padding-left:0"><!-- wp:group {"align":"full","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"},"fontSize":"small"} -->
	<div class="wp-block-group alignfull has-small-font-size"><!-- wp:site-title {"style":{"layout":{"selfStretch":"fixed","flexSize":"25%"}},"fontSize":"small"} /-->
	
	<!-- wp:group {"style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
	<div class="wp-block-group"><!-- wp:paragraph {"align":"center","fontSize":"small"} -->
	<p class="has-text-align-center has-small-font-size"> Powered by <a rel="nofollow" href="https://wbcomdesigns.com/downloads/buddyx-theme/">BuddyX WordPress Theme</a> </p>
	<!-- /wp:paragraph --></div>
	<!-- /wp:group -->
	
	<!-- wp:social-links {"size":"has-small-icon-size","style":{"spacing":{"blockGap":{"top":"0","left":"12px"}},"layout":{"selfStretch":"fixed","flexSize":"25%"}},"className":"is-style-logos-only","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
	<ul class="wp-block-social-links has-small-icon-size is-style-logos-only"><!-- wp:social-link {"url":"#","service":"twitter"} /-->
	
	<!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
	<!-- /wp:social-links --></div>
	<!-- /wp:group --></div>
	<!-- /wp:group -->',
);
