<?php
/**
 * Pattern: Hero - typography-led, single-column.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Hero - Typography-led', 'buddyx' ),
	'categories' => array( 'buddyx-hero', 'header' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"background":{"backgroundImage":{"url":""},"backgroundSize":"cover"}},"backgroundColor":"base-2","layout":{"type":"constrained","contentSize":"880px"}} -->
<div class="wp-block-group alignfull has-base-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--90);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--90);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.18em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
  <p class="has-text-align-center has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.18em;text-transform:uppercase">- Theme of the year, by you</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"textAlign":"center","level":1,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|mega","fontWeight":"700","lineHeight":"1.0","letterSpacing":"-0.03em"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|40"}}}} -->
  <h1 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--mega);font-weight:700;letter-spacing:-0.03em;line-height:1.0">Build sites that <em>read</em> like editorial.</h1>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.5"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|50"}}},"textColor":"contrast-2"} -->
  <p class="has-text-align-center has-contrast-2-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--large);line-height:1.5">A typography-first WordPress theme with a designer-grade pattern library. Inter for body, Newsreader for editorial accents, Site Editor everything. No page builder, no plugin lock-in.</p>
  <!-- /wp:paragraph -->

  <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
  <div class="wp-block-buttons">
    <!-- wp:button {"backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}}} -->
    <div class="wp-block-button"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="#download" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Download free</a></div>
    <!-- /wp:button -->
    <!-- wp:button {"className":"is-style-link-arrow"} -->
    <div class="wp-block-button is-style-link-arrow"><a class="wp-block-button__link wp-element-button" href="#preview">Preview the patterns</a></div>
    <!-- /wp:button -->
  </div>
  <!-- /wp:buttons -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"0"}}},"textColor":"contrast-3"} -->
  <p class="has-text-align-center has-contrast-3-color has-text-color" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:0;font-size:var(--wp--preset--font-size--small)">★★★★★ &nbsp;Rated 5/5 on wordpress.org</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
',
);
