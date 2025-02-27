<?php
/**
 * Hero block with two columns
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Hero block with two columns', 'buddyx' ),
	'categories' => array( 'buddyx-hero' ),
	'content'    => '<!-- wp:group {"metadata":{"categories":["buddyx-hero"],"patternName":"buddyx/hero-two","name":"Hero block with two columns"},"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|basecolor"}}}},"backgroundColor":"contrastcolor","textColor":"basecolor","layout":{"inherit":true,"type":"constrained"}} -->
    <div class="wp-block-group alignfull has-basecolor-color has-contrastcolor-background-color has-text-color has-background has-link-color"><!-- wp:media-text {"align":"wide","mediaPosition":"right","mediaId":4690,"mediaLink":"' . esc_url( get_theme_file_uri( '/assets/images/hero-white-bg.jpg' ) ) . '","mediaType":"image","mediaWidth":40,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"backgroundColor":"base"} -->
    <div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile has-base-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;grid-template-columns:auto 40%"><div class="wp-block-media-text__content"><!-- wp:heading {"style":{"typography":{"lineHeight":"1.2","fontSize":"44px","fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"bottom":"30px","top":"30px"}}}} -->
    <h2 class="wp-block-heading" id="text-on-left-image-on-right" style="margin-top:30px;margin-bottom:30px;font-size:44px;font-style:normal;font-weight:700;line-height:1.2">Lorem ipsum dolor sit amet, consectetur </h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"style":{"color":{"text":"#8391a3"},"elements":{"link":{"color":{"text":"#8391a3"}}}},"fontSize":"medium"} -->
    <p class="has-text-color has-link-color has-medium-font-size" style="color:#8391a3">Lorem ipsum dolor sit amet, consectetur adipiscing vestibulum. Fringilla nec accumsan eget, facilisis mi justo, luctus pellentesque gravida vitae non diam accumsan posuere, venenatis mi turpis.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
    <div class="wp-block-buttons" style="margin-bottom:var(--wp--preset--spacing--50)"><!-- wp:button {"backgroundColor":"primary","textColor":"textonprimary","className":"is-style-cubebtndark is-style-fill","style":{"spacing":{"padding":{"top":"15px","right":"40px","bottom":"15px","left":"40px"}},"border":{"radius":"3px"},"typography":{"fontSize":"16px"}}} -->
    <div class="wp-block-button has-custom-font-size is-style-cubebtndark is-style-fill" style="font-size:16px"><a class="wp-block-button__link has-textonprimary-color has-primary-background-color has-text-color has-background wp-element-button" style="border-radius:3px;padding-top:15px;padding-right:40px;padding-bottom:15px;padding-left:40px">Get Started</a></div>
    <!-- /wp:button -->

    <!-- wp:button {"backgroundColor":"white","textColor":"black","className":"is-style-cubebtnwhite is-style-fill","style":{"spacing":{"padding":{"left":"40px","right":"40px","top":"15px","bottom":"15px"}},"typography":{"fontSize":"16px"}}} -->
    <div class="wp-block-button has-custom-font-size is-style-cubebtnwhite is-style-fill" style="font-size:16px"><a class="wp-block-button__link has-black-color has-white-background-color has-text-color has-background wp-element-button" style="padding-top:15px;padding-right:40px;padding-bottom:15px;padding-left:40px">Learn More</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/hero-white-bg.jpg" alt="Sample Image" class="wp-image-4690 size-full"/></figure></div>
    <!-- /wp:media-text --></div>
    <!-- /wp:group -->',
);
