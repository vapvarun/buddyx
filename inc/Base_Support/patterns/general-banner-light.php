<?php
/**
 * Pattern: Banner light — asymmetric CTA strip on warm surface.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Banner — Light Asymmetric', 'buddyx' ),
	'categories' => array( 'buddyx-cta', 'banner' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"surface-1","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull has-surface-1-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">

    <!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">For builders</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}}} -->
      <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Plan a site in an <em>afternoon</em>, not a weekend.</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);line-height:1.6">Open a page, drop a hero, drop a CTA, ship. The visual rigor comes from the system.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">
      <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left","orientation":"vertical"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
      <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}}} -->
        <div class="wp-block-button"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="#start" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Get the theme</a></div>
        <!-- /wp:button -->
        <!-- wp:button {"className":"is-style-link-arrow"} -->
        <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#patterns">See the pattern library</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
