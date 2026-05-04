<?php
/**
 * Pattern: Steps - numbered process row.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Steps - Numbered Process', 'buddyx' ),
	'categories' => array( 'buddyx-features', 'featured' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
  <div class="wp-block-group">
    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
    <p class="has-text-align-center has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">How it works</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading {"textAlign":"center","level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|60"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--60);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">From sketch to ship in <em>four</em> moves.</h2>
    <!-- /wp:heading -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:separator {"className":"is-style-gradient","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
      <hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30)"/>
      <!-- /wp:separator -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.1em","fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.1em">01 / DISCOVER</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Audit and align</h3>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">A 30-minute call to map goals, audience and the must-haves on day one.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:separator {"className":"is-style-gradient","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
      <hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30)"/>
      <!-- /wp:separator -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.1em","fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.1em">02 / DESIGN</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Compose the system</h3>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Type, color and pattern stack tuned in Site Editor - visible at every step.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:separator {"className":"is-style-gradient","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
      <hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30)"/>
      <!-- /wp:separator -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.1em","fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.1em">03 / DEVELOP</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Wire up content</h3>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Real copy, real images, real performance budget. Patterns become pages.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:separator {"className":"is-style-gradient","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
      <hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30)"/>
      <!-- /wp:separator -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.1em","fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.1em">04 / DELIVER</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Ship and iterate</h3>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Launch day handoff plus 30 days of measure-and-tune support.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
