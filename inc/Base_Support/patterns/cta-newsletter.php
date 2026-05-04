<?php
/**
 * Pattern: CTA - newsletter signup strip.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'CTA - Newsletter Signup', 'buddyx' ),
	'categories' => array( 'buddyx-cta', 'call-to-action' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"surface-1","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull has-surface-1-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">

    <!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Monthly dispatch</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}}} -->
      <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Quiet, useful, <em>monthly</em>.</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);line-height:1.6">One email a month with the patterns we shipped, the bugs we squashed and the design rabbit-holes we fell down. Unsubscribe in one click.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%">

      <!-- wp:buttons {"layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
      <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}},"width":100} -->
        <div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="#subscribe" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Subscribe - it\'s free</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|x-small"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:0;font-size:var(--wp--preset--font-size--x-small)">No spam. Unsubscribe in one click. We never share your address.</p>
      <!-- /wp:paragraph -->

    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
