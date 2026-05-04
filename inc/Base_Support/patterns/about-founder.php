<?php
/**
 * Pattern: About — founder spotlight with editorial pull-quote.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'About — Founder Spotlight', 'buddyx' ),
	'categories' => array( 'buddyx-about', 'about' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"surface-1","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull has-surface-1-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|70"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">

    <!-- wp:column {"verticalAlignment":"center","width":"38%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:38%">
      <!-- wp:group {"style":{"border":{"radius":"50%"},"spacing":{"padding":{"top":"50%"}},"background":{"backgroundSize":"cover","backgroundPosition":"50% 50%"}},"backgroundColor":"contrast-3","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-contrast-3-background-color has-background" style="border-radius:50%;padding-top:50%"></div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"62%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:62%">

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
      <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Founder, Editor</p>
      <!-- /wp:paragraph -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|x-large","lineHeight":"1.3","fontStyle":"italic","fontWeight":"500","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|40"}}}} -->
      <p style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--40);font-family:var(--wp--preset--font-family--newsreader);font-size:var(--wp--preset--font-size--x-large);font-style:italic;font-weight:500;line-height:1.3">"I wanted a theme that respected typography the way magazines do — and accessibility the way the web should. Three years later, here we are."</p>
      <!-- /wp:paragraph -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"bottom":"0"}}}} -->
      <p style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Aria Patel</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"4px","bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:4px;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Founder · Wbcom Designs</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
