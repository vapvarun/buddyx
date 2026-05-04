<?php
/**
 * Pattern: Features - alternating image/text rows.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Features - Alternating Rows', 'buddyx' ),
	'categories' => array( 'buddyx-features', 'featured' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|70"}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">
    <!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">
      <!-- wp:group {"style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"75%"}}},"backgroundColor":"surface-1","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-1-background-color has-background" style="border-radius:20px;padding-top:75%"></div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">01 - Type system</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
      <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Eight type sizes. <em>One</em> ratio.</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.55"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--large);line-height:1.55">A fluid clamp() scale from 14px x-small to 96px mega. Inter for body, Newsreader for editorial. Self-hosted, ~140kb total, GDPR-clean.</p>
      <!-- /wp:paragraph -->
      <!-- wp:buttons -->
      <div class="wp-block-buttons">
        <!-- wp:button {"className":"is-style-link-arrow"} -->
        <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#docs">See the type spec</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">
    <!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">02 - Color tokens</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
      <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Semantic tokens, <em>not</em> a color picker.</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.55"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--large);line-height:1.55">base, contrast, accent, surface - every pattern composes against a 12-token palette. Verified WCAG AA on light and dark surfaces.</p>
      <!-- /wp:paragraph -->
      <!-- wp:buttons -->
      <div class="wp-block-buttons">
        <!-- wp:button {"className":"is-style-link-arrow"} -->
        <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#tokens">Open the token map</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">
      <!-- wp:group {"style":{"border":{"radius":"20px"},"spacing":{"padding":{"top":"75%"}}},"backgroundColor":"surface-2","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-2-background-color has-background" style="border-radius:20px;padding-top:75%"></div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
