<?php
/**
 * Pattern: CTA — full-bleed gradient with centered headline.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'CTA — Full-bleed Gradient', 'buddyx' ),
	'categories' => array( 'buddyx-cta', 'call-to-action' ),
	'content'    => '
<!-- wp:group {"align":"full","gradient":"accent-bold","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group alignfull has-accent-bold-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.18em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"base"} -->
  <p class="has-text-align-center has-base-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.18em;text-transform:uppercase">Ready to ship?</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"textAlign":"center","level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|xx-large","fontWeight":"700","lineHeight":"1.05","letterSpacing":"-0.025em"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}},"textColor":"base"} -->
  <h2 class="wp-block-heading has-text-align-center has-newsreader-accent has-base-color has-text-color" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--xx-large);font-weight:700;line-height:1.05;letter-spacing:-0.025em">Your next site, <em>shipped</em> by Friday.</h2>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.5"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"textColor":"base"} -->
  <p class="has-text-align-center has-base-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--large);line-height:1.5">Free on wordpress.org. Premium support and starter sites available through the Wbcom membership.</p>
  <!-- /wp:paragraph -->

  <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
  <div class="wp-block-buttons">
    <!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}}} -->
    <div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#download" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Download free</a></div>
    <!-- /wp:button -->
    <!-- wp:button {"textColor":"base","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px","width":"1px","color":"#FFFFFF"}}} -->
    <div class="wp-block-button"><a class="wp-block-button__link has-base-color has-text-color has-border-color wp-element-button" href="#membership" style="border-color:#FFFFFF;border-width:1px;border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">See membership</a></div>
    <!-- /wp:button -->
  </div>
  <!-- /wp:buttons -->

</div>
<!-- /wp:group -->
',
);
