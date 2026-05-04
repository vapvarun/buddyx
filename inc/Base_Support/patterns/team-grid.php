<?php
/**
 * Pattern: Team - 4-up grid.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Team - 4-up Grid', 'buddyx' ),
	'categories' => array( 'buddyx-about', 'team' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
  <div class="wp-block-group">
    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
    <p class="has-text-align-center has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">The team</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading {"textAlign":"center","level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|60"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--60);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Four people, <em>one</em> standard.</h2>
    <!-- /wp:heading -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|40"}}}} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"50%"},"spacing":{"padding":{"top":"100%"}}},"backgroundColor":"surface-1","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-1-background-color has-background" style="border-radius:50%;padding-top:100%"></div>
      <!-- /wp:group -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"4px"}}}} -->
      <p style="margin-top:var(--wp--preset--spacing--30);margin-bottom:4px;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Aria Patel</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Founder · Editor-in-chief</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"50%"},"spacing":{"padding":{"top":"100%"}}},"backgroundColor":"surface-2","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-2-background-color has-background" style="border-radius:50%;padding-top:100%"></div>
      <!-- /wp:group -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"4px"}}}} -->
      <p style="margin-top:var(--wp--preset--spacing--30);margin-bottom:4px;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">James Okonkwo</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Lead Designer</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"50%"},"spacing":{"padding":{"top":"100%"}}},"backgroundColor":"surface-1","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-1-background-color has-background" style="border-radius:50%;padding-top:100%"></div>
      <!-- /wp:group -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"4px"}}}} -->
      <p style="margin-top:var(--wp--preset--spacing--30);margin-bottom:4px;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Priya Raman</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Engineering Lead</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"50%"},"spacing":{"padding":{"top":"100%"}}},"backgroundColor":"surface-2","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-2-background-color has-background" style="border-radius:50%;padding-top:100%"></div>
      <!-- /wp:group -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"4px"}}}} -->
      <p style="margin-top:var(--wp--preset--spacing--30);margin-bottom:4px;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Sofia Lindqvist</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Editor-at-large</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
