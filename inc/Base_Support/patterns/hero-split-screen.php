<?php
/**
 * Pattern: Hero — split-screen 5/7 (text left, image cover right).
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Hero — Split Screen', 'buddyx' ),
	'categories' => array( 'buddyx-hero', 'header' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">

    <!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">For modern businesses</p>
      <!-- /wp:paragraph -->

      <!-- wp:heading {"level":1,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|xx-large","fontWeight":"700","lineHeight":"1.05","letterSpacing":"-0.025em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
      <h1 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--xx-large);font-weight:700;line-height:1.05;letter-spacing:-0.025em">A studio site that <em>doesn\'t</em> need a designer.</h1>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.55"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--large);line-height:1.55">Twenty-seven editorial-grade patterns assemble in minutes. Open a page, drop a hero, drop a CTA, ship. The visual rigor comes from the system, not from your spare hours.</p>
      <!-- /wp:paragraph -->

      <!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
      <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}}} -->
        <div class="wp-block-button"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="#start" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Get started</a></div>
        <!-- /wp:button -->
        <!-- wp:button {"className":"is-style-link-arrow"} -->
        <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#docs">Read the docs</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->

    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">

      <!-- wp:group {"className":"is-style-bordered","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}},"border":{"radius":"24px"}},"backgroundColor":"surface-1","layout":{"type":"constrained"}} -->
      <div class="wp-block-group is-style-bordered has-surface-1-background-color has-background" style="border-radius:24px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"500","lineHeight":"1.3","fontStyle":"italic","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
        <p style="margin-bottom:var(--wp--preset--spacing--40);font-family:var(--wp--preset--font-family--newsreader);font-size:var(--wp--preset--font-size--x-large);font-style:italic;font-weight:500;line-height:1.3">"Shipped a marketing site for our agency in two afternoons. Looks like we hired a brand studio."</p>
        <!-- /wp:paragraph -->

        <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
        <div class="wp-block-columns are-vertically-aligned-center">

          <!-- wp:column {"verticalAlignment":"center","width":"60px"} -->
          <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60px">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"border":{"radius":"999px"}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background" style="border-radius:999px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
              <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"700","lineHeight":"1"}}} -->
              <p class="has-text-align-center" style="font-size:var(--wp--preset--font-size--medium);font-weight:700;line-height:1">M</p>
              <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
          </div>
          <!-- /wp:column -->

          <!-- wp:column {"verticalAlignment":"center"} -->
          <div class="wp-block-column is-vertically-aligned-center">
            <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"bottom":"0"}}}} -->
            <p style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Maya Chen</p>
            <!-- /wp:paragraph -->
            <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","lineHeight":"1.4"},"spacing":{"margin":{"top":"4px"}}},"textColor":"contrast-3"} -->
            <p class="has-contrast-3-color has-text-color" style="margin-top:4px;font-size:var(--wp--preset--font-size--small);line-height:1.4">Founder · Mosaic Studio</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:column -->

        </div>
        <!-- /wp:columns -->

      </div>
      <!-- /wp:group -->

    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
