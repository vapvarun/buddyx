<?php
/**
 * Pattern: About — story / mission.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'About — Story', 'buddyx' ),
	'categories' => array( 'buddyx-about', 'about' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|80"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-top">

    <!-- wp:column {"verticalAlignment":"top","width":"38%"} -->
    <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:38%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">About us</p>
      <!-- /wp:paragraph -->
      <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"0"}}}} -->
      <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:0;font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">A small studio with a <em>specific</em> opinion.</h2>
      <!-- /wp:heading -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"top","width":"62%"} -->
    <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:62%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.55"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
      <p style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--large);line-height:1.55">We build WordPress themes for people who care about the words they put on the page. That sounds like a small thing until you compare a sentence set in Newsreader at 1.55 line-height to the same sentence set in Times at 1.2 — at which point it becomes the only thing.</p>
      <!-- /wp:paragraph -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.65"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--medium);line-height:1.65">We started in 2017 because every wp.org theme we tried was either a kitchen-sink page builder or a one-page checkbox. We wanted the third thing — a coherent system, with restraint.</p>
      <!-- /wp:paragraph -->

      <!-- wp:buttons -->
      <div class="wp-block-buttons">
        <!-- wp:button {"className":"is-style-link-arrow"} -->
        <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#manifesto">Read the design manifesto</a></div>
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
