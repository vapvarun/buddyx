<?php
/**
 * Banner block
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Banner block', 'buddyx' ),
	'categories' => array( 'buddyx-general' ),
	'content'    => '<!-- wp:cover {"url":"' . esc_url( get_template_directory_uri() ) . '/assets/images/general-banner.png","id":7208,"dimRatio":0,"minHeight":100,"align":"wide","style":{"spacing":{"padding":{"bottom":"4vw","left":"4vw","top":"3vw","right":"4vw"}},"border":{"radius":"10px"}}} -->
    <div class="wp-block-cover alignwide" style="border-radius:10px;padding-top:3vw;padding-right:4vw;padding-bottom:4vw;padding-left:4vw;min-height:100px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-7208" alt="" src="' . esc_url( get_template_directory_uri() ) . '/assets/images/general-banner.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:columns {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
    <div class="wp-block-columns has-white-color has-text-color has-link-color"><!-- wp:column {"width":"60%"} -->
    <div class="wp-block-column" style="flex-basis:60%"><!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"var:preset|spacing|20","left":"0"}}},"textColor":"base","fontSize":"small"} -->
    <p class="has-base-color has-text-color has-small-font-size" style="margin-top:0;margin-right:0;margin-bottom:var(--wp--preset--spacing--20);margin-left:0">Build. Animate. Earn</p>
    <!-- /wp:paragraph -->

    <!-- wp:heading {"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"x-large"} -->
    <h2 class="wp-block-heading has-base-color has-text-color has-link-color has-x-large-font-size" id="put-your-site-on-next-level-with-buddyx" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0">Put Your Site on Next Level with BuddyX</h2>
    <!-- /wp:heading -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":"var:preset|spacing|40"}},"fontSize":"small","layout":{"type":"flex","flexWrap":"wrap"}} -->
    <div class="wp-block-group has-small-font-size" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
    <p class="has-base-color has-text-color has-link-color">Premium Support</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
    <p class="has-base-color has-text-color has-link-color">14 day money-back</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
    <p class="has-base-color has-text-color has-link-color">Cancel any time</p>
    <!-- /wp:paragraph --></div>
    <!-- /wp:group --></div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":""} -->
    <div class="wp-block-column is-vertically-aligned-center"><!-- wp:buttons {"className":"gs-tablet-center","layout":{"type":"flex","justifyContent":"center","verticalAlignment":"top"}} -->
    <div class="wp-block-buttons gs-tablet-center"><!-- wp:button {"gradient":"creative-btn","className":"is-style-fill","style":{"spacing":{"padding":{"right":"var:preset|spacing|70","left":"var:preset|spacing|70","top":"0.9rem","bottom":"0.9rem"}}}} -->
    <div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-creative-btn-gradient-background has-background wp-element-button" href="#" style="padding-top:0.9rem;padding-right:var(--wp--preset--spacing--70);padding-bottom:0.9rem;padding-left:var(--wp--preset--spacing--70)" target="_blank" rel="noreferrer noopener">Check Now</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns --></div></div>
    <!-- /wp:cover -->',
);
