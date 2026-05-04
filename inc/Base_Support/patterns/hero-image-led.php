<?php
/**
 * Pattern: Hero — image-led full-bleed cover.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Hero — Image-led', 'buddyx' ),
	'categories' => array( 'buddyx-hero', 'header' ),
	'content'    => '
<!-- wp:group {"align":"full","gradient":"dark-velvet","style":{"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|100","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"880px"}} -->
<div class="wp-block-group alignfull has-dark-velvet-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--100);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--100);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.18em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent-3"} -->
  <p class="has-text-align-center has-accent-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.18em;text-transform:uppercase">— New in 5.0.3</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"textAlign":"center","level":1,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|mega","fontWeight":"700","lineHeight":"1.0","letterSpacing":"-0.03em"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|40"}}},"textColor":"base"} -->
  <h1 class="wp-block-heading has-text-align-center has-newsreader-accent has-base-color has-text-color" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--mega);font-weight:700;letter-spacing:-0.03em;line-height:1.0">An <em>editorial</em> theme for serious work.</h1>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.5"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"textColor":"contrast-2"} -->
  <p class="has-text-align-center has-contrast-2-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--large);line-height:1.5">Twenty-seven patterns. Two self-hosted families. One coherent design system. Built for sites that have something to say.</p>
  <!-- /wp:paragraph -->

  <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
  <div class="wp-block-buttons">
    <!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}}} -->
    <div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#start" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Start building</a></div>
    <!-- /wp:button -->
  </div>
  <!-- /wp:buttons -->

</div>
<!-- /wp:group -->
',
);
