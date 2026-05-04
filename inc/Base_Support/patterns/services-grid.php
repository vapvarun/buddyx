<?php
/**
 * Pattern: Services - 3-up grid with editorial typography.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Services - 3-up Grid', 'buddyx' ),
	'categories' => array( 'buddyx-features', 'services' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|80"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-top">

    <!-- wp:column {"verticalAlignment":"top","width":"38%"} -->
    <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:38%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">What we make</p>
      <!-- /wp:paragraph -->

      <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
      <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Six services. <em>One</em> standard.</h2>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.6">From the first sketch to the last shipped pixel - every project gets the same care.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"top","width":"62%"} -->
    <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:62%">

      <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
      <div class="wp-block-columns">

        <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
          <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Brand identity</h3>
          <!-- /wp:heading -->
          <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
          <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Logo, type, color and voice - the system you build everything else on.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
          <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Web design</h3>
          <!-- /wp:heading -->
          <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
          <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Editorial layouts, typography rhythm, motion. Built around what you say, not around a stock template.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

      </div>
      <!-- /wp:columns -->

      <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"},"margin":{"top":"var:preset|spacing|40"}}}} -->
      <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--40)">

        <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
          <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Content strategy</h3>
          <!-- /wp:heading -->
          <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
          <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Architecture, voice, editorial calendar. Words first, layout second, polish third.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
          <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Accessibility audit</h3>
          <!-- /wp:heading -->
          <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
          <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">WCAG 2.1 AA verification with screen reader testing and a remediation backlog.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

      </div>
      <!-- /wp:columns -->

      <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"},"margin":{"top":"var:preset|spacing|40"}}}} -->
      <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--40)">

        <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
          <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Performance tuning</h3>
          <!-- /wp:heading -->
          <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
          <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Core Web Vitals from triage to green. We tune the front-end and the queries behind it.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
          <h3 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:600;line-height:1.3;letter-spacing:-0.01em">Ongoing support</h3>
          <!-- /wp:heading -->
          <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} -->
          <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.55">Updates, incident response, monthly reports. The site keeps shipping after launch day.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

      </div>
      <!-- /wp:columns -->

    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
