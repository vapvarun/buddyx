<?php
/**
 * Footer Mega Menu
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Mega menu in 5 columns', 'buddyx' ),
	'categories' => array( 'buddyx-footer' ),
	'blockTypes' => array( 'core/template-part/footer' ),
	'content'    => '<!-- wp:group {"align":"full","style":{"typography":{"fontSize":"18px"},"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"15px","right":"15px"}},"border":{"top":{"color":"#cfcfcf45","width":"1px"}},"color":{"text":"#f0f0f0"}},"backgroundColor":"black","layout":{"inherit":true,"type":"constrained"}} -->
    <div class="wp-block-group alignfull has-black-background-color has-text-color has-background" style="border-top-color:#cfcfcf45;border-top-width:1px;color:#f0f0f0;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--60);padding-right:15px;padding-bottom:var(--wp--preset--spacing--60);padding-left:15px;font-size:18px"><!-- wp:columns {"align":"wide","className":"gs-tablet-collapse","style":{"elements":{"link":{"color":[]}},"spacing":{"padding":{"top":"20px","bottom":"0px"}}}} -->
    <div class="wp-block-columns alignwide gs-tablet-collapse has-link-color" style="padding-top:20px;padding-bottom:0px"><!-- wp:column {"width":"30%"} -->
    <div class="wp-block-column" style="flex-basis:30%"><!-- wp:paragraph {"style":{"typography":{"fontSize":"20px"}}} -->
    <p style="font-size:20px">Follow Us</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"style":{"typography":{"fontSize":"16px"}}} -->
    <p style="font-size:16px">Lorem ipsum dolor sit amet, consectetur adipiscing lectus. Vestibulum mi justo, luctus eu pellentesque vitae gravida non.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secondary-button","style":{"typography":{"fontSize":"16px"}}} -->
    <div class="wp-block-button has-custom-font-size is-style-secondary-button" style="font-size:16px"><a class="wp-block-button__link wp-element-button" href="#">Subscribe</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"5%"} -->
    <div class="wp-block-column" style="flex-basis:5%"></div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"15%"} -->
    <div class="wp-block-column" style="flex-basis:15%"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
    <p style="margin-bottom:var(--wp--preset--spacing--50);font-style:normal;font-weight:700">Our Mission</p>
    <!-- /wp:paragraph -->

    <!-- wp:list {"className":"is-style-nounderline","style":{"elements":{"link":{"color":{"text":"var:preset|color|lightgrey"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"right":"0px","left":"0px"}}},"textColor":"lightgrey"} -->
    <ul style="padding-right:0px;padding-left:0px;font-size:16px" class="wp-block-list is-style-nounderline has-lightgrey-color has-text-color has-link-color"><!-- wp:list-item {"style":{"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><a href="#">Start Here</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><a href="#">Our Mission</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><a href="#">Brand Guide</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><a href="#">Social Media</a></li>
    <!-- /wp:list-item --></ul>
    <!-- /wp:list --></div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"15%"} -->
    <div class="wp-block-column" style="flex-basis:15%"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
    <p style="margin-bottom:var(--wp--preset--spacing--50);font-style:normal;font-weight:700">Services</p>
    <!-- /wp:paragraph -->

    <!-- wp:list {"className":"is-style-nounderline","style":{"elements":{"link":{"color":{"text":"var:preset|color|lightgrey"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"right":"0px","left":"0px"}}},"textColor":"lightgrey"} -->
    <ul style="padding-right:0px;padding-left:0px;font-size:16px" class="wp-block-list is-style-nounderline has-lightgrey-color has-text-color has-link-color"><!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Web Design</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Development</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Copywriting</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item -->
    <li><a href="#">Cross media</a></li>
    <!-- /wp:list-item --></ul>
    <!-- /wp:list --></div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"15%"} -->
    <div class="wp-block-column" style="flex-basis:15%"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
    <p style="margin-bottom:var(--wp--preset--spacing--50);font-style:normal;font-weight:700">Connect</p>
    <!-- /wp:paragraph -->

    <!-- wp:list {"className":"is-style-nounderline","style":{"elements":{"link":{"color":{"text":"var:preset|color|lightgrey"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"right":"0px","left":"0px"}}},"textColor":"lightgrey"} -->
    <ul style="padding-right:0px;padding-left:0px;font-size:16px" class="wp-block-list is-style-nounderline has-lightgrey-color has-text-color has-link-color"><!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Facebook</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Instagram</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item -->
    <li><a href="#">TikTok</a></li>
    <!-- /wp:list-item --></ul>
    <!-- /wp:list --></div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"15%"} -->
    <div class="wp-block-column" style="flex-basis:15%"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
    <p style="margin-bottom:var(--wp--preset--spacing--50);font-style:normal;font-weight:700">Resources</p>
    <!-- /wp:paragraph -->

    <!-- wp:list {"className":"is-style-nounderline","style":{"elements":{"link":{"color":{"text":"var:preset|color|lightgrey"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"right":"0px","left":"0px"}}},"textColor":"lightgrey"} -->
    <ul style="padding-right:0px;padding-left:0px;font-size:16px" class="wp-block-list is-style-nounderline has-lightgrey-color has-text-color has-link-color"><!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Bloggers</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item {"style":{"typography":{"fontSize":"15px"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}}}} -->
    <li style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:15px"><a href="#">Video releases</a></li>
    <!-- /wp:list-item -->

    <!-- wp:list-item -->
    <li><a href="#">Politics</a></li>
    <!-- /wp:list-item --></ul>
    <!-- /wp:list --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns --></div>
    <!-- /wp:group -->

    <!-- wp:group {"align":"full","className":"has-small-font-size","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"15px","right":"15px"}},"color":{"text":"#f0f0f0"},"border":{"top":{"color":"#cfcfcf45","width":"1px"},"right":{},"bottom":{}}},"backgroundColor":"contrast","layout":{"inherit":true,"type":"constrained"}} -->
    <div class="wp-block-group alignfull has-small-font-size has-contrast-background-color has-text-color has-background has-link-color" style="border-top-color:#cfcfcf45;border-top-width:1px;color:#f0f0f0;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--40);padding-right:15px;padding-bottom:var(--wp--preset--spacing--40);padding-left:15px"><!-- wp:group {"align":"wide","layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
    <div class="wp-block-group alignwide"><!-- wp:site-title {"level":0,"style":{"layout":{"selfStretch":"fit","flexSize":null},"typography":{"fontSize":"20px","textTransform":"uppercase"}}} /-->

    <!-- wp:paragraph {"style":{"typography":{"fontSize":"13px"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
    <p class="has-base-color has-text-color has-link-color" style="font-size:13px"><a href="#">Privacy Policy</a> · <a href="#">Terms of Service</a> · <a href="#">Contact Us</a></p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"fontSize":"xsmall"} -->
    <p class="has-xsmall-font-size">All rights reserved</p>
    <!-- /wp:paragraph --></div>
    <!-- /wp:group --></div>
    <!-- /wp:group -->

    <!-- wp:paragraph -->
    <p></p>
    <!-- /wp:paragraph -->',
);
