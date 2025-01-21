<?php
/**
 * Simple footer with social icons
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Simple footer with social icons', 'buddyx' ),
	'categories' => array( 'buddyx-footer' ),
	'blockTypes' => array( 'core/template-part/footer' ),
	'content'    => '<!-- wp:group {"metadata":{"categories":["buddyx-footer"],"patternName":"buddyx/footer-simple","name":"Simple footer with social icons"},"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"border":{"top":{"color":"#cfcfcf45","width":"1px"}},"color":{"text":"#f0f0f0"},"typography":{"fontSize":"14px"},"spacing":{"padding":{"right":"15px","left":"15px","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"black","layout":{"inherit":true,"type":"constrained"}} -->
    <div class="wp-block-group alignfull has-black-background-color has-text-color has-background has-link-color" style="border-top-color:#cfcfcf45;border-top-width:1px;color:#f0f0f0;padding-top:var(--wp--preset--spacing--40);padding-right:15px;padding-bottom:var(--wp--preset--spacing--40);padding-left:15px;font-size:14px"><!-- wp:group {"align":"wide","layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
    <div class="wp-block-group alignwide"><!-- wp:paragraph -->
    <p>© Your Company LLC</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"fontSize":"small"} -->
    <p class="has-small-font-size"><a href="#">Privacy Policy</a> · <a href="#">Terms of Service</a> · <a href="#">Contact Us</a></p>
    <!-- /wp:paragraph -->

    <!-- wp:social-links {"iconColor":"black","iconColorValue":"#000","iconBackgroundColor":"white","iconBackgroundColorValue":"#fff","className":"is-style-default"} -->
    <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default"><!-- wp:social-link {"url":"#","service":"facebook","rel":""} /-->

    <!-- wp:social-link {"url":"#","service":"instagram","rel":""} /-->

    <!-- wp:social-link {"url":"#","service":"twitter"} /--></ul>
    <!-- /wp:social-links --></div>
    <!-- /wp:group --></div>
    <!-- /wp:group -->

    <!-- wp:paragraph -->
    <p></p>
    <!-- /wp:paragraph -->',
);
