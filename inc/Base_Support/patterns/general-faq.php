<?php
/**
 * Pattern: FAQ — editorial two-column with mixed-typography headline.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'FAQ — Editorial', 'buddyx' ),
	'categories' => array( 'buddyx-pricing-faq', 'text' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|80"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-top">

    <!-- wp:column {"verticalAlignment":"top","width":"40%"} -->
    <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:40%">

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"500"}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:500;letter-spacing:0.12em;text-transform:uppercase">Frequently asked</p>
      <!-- /wp:paragraph -->

      <!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|xx-large","fontWeight":"700","lineHeight":"1.05","letterSpacing":"-0.025em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
      <h2 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--xx-large);font-weight:700;line-height:1.05;letter-spacing:-0.025em">The questions you\'re <em style="font-family:var(--wp--preset--font-family--newsreader);font-weight:400;font-style:italic">actually</em> asking.</h2>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.5"}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="font-size:var(--wp--preset--font-size--large);line-height:1.5">Eight things buyers ask before they hit purchase, answered without fluff.</p>
      <!-- /wp:paragraph -->

      <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
      <div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
        <!-- wp:button {"className":"is-style-link-arrow"} -->
        <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#contact">Still curious? Talk to us</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->

    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"top","width":"60%","className":"buddyx-faq-stack"} -->
    <div class="wp-block-column is-vertically-aligned-top buddyx-faq-stack" style="flex-basis:60%">

      <!-- wp:details -->
      <details class="wp-block-details"><summary>What ships in the box?</summary>
      <!-- wp:paragraph -->
      <p>Twenty-seven editorial-grade block patterns across hero, about, services, social proof, pricing, FAQ, CTA, footer and query — plus full Site Editor support, Inter and Newsreader self-hosted, light and dark style variations, and a ten-step spacing system.</p>
      <!-- /wp:paragraph --></details>
      <!-- /wp:details -->

      <!-- wp:details -->
      <details class="wp-block-details"><summary>Do I need a page builder?</summary>
      <!-- wp:paragraph -->
      <p>No. Every pattern composes from core WordPress blocks — group, columns, cover, heading, paragraph, image, gallery, buttons, list. Drop a pattern in, edit the copy, ship.</p>
      <!-- /wp:paragraph --></details>
      <!-- /wp:details -->

      <!-- wp:details -->
      <details class="wp-block-details"><summary>Can I switch the typography?</summary>
      <!-- wp:paragraph -->
      <p>Pick the Editorial style variation in Site Editor and the entire site flips to Newsreader serif headings. Or set any registered family in Global Styles. Inter and Newsreader are bundled — no Google Fonts API call.</p>
      <!-- /wp:paragraph --></details>
      <!-- /wp:details -->

      <!-- wp:details -->
      <details class="wp-block-details"><summary>Is it accessible?</summary>
      <!-- wp:paragraph -->
      <p>WCAG 2.1 AA. Brand-blue focus rings via :focus-visible, real label associations on form inputs, no aria-hidden on focusable elements, and keyboard parity verified across every pattern at 1280px and 390px.</p>
      <!-- /wp:paragraph --></details>
      <!-- /wp:details -->

      <!-- wp:details -->
      <details class="wp-block-details"><summary>What about updates and support?</summary>
      <!-- wp:paragraph -->
      <p>Free theme updates via wp.org auto-update. Public issue tracker on GitHub. Detailed changelog with every release. Premium support is available through the Wbcom Designs membership.</p>
      <!-- /wp:paragraph --></details>
      <!-- /wp:details -->

    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
