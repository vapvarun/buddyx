<?php
/**
 * Pattern: Social proof — 3-up testimonials with Newsreader pull-quotes.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Testimonials — Editorial 3-up', 'buddyx' ),
	'categories' => array( 'buddyx-social-proof', 'testimonials' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base-2","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
  <div class="wp-block-group">
    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
    <p class="has-text-align-center has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Loved by</p>
    <!-- /wp:paragraph -->

    <!-- wp:heading {"textAlign":"center","level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|60"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--60);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">2,847 makers and <em>counting</em>.</h2>
    <!-- /wp:heading -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"is-style-card","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"20px"}},"layout":{"type":"constrained"}} -->
      <div class="wp-block-group is-style-card has-base-background-color has-background" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.2em"}},"textColor":"accent"} -->
        <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.2em">★★★★★</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.4","fontStyle":"normal","fontWeight":"500","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|40"}}}} -->
        <p style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--40);font-family:var(--wp--preset--font-family--newsreader);font-size:var(--wp--preset--font-size--large);font-style:normal;font-weight:500;line-height:1.4">"Replaced our $200/mo page builder. The patterns alone justified the switch — agency clients ask which Webflow site they\'re looking at."</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"bottom":"0"}}}} -->
        <p style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">James Okonkwo</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"4px","bottom":"0"}}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="margin-top:4px;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Lead Designer · Northwind</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"is-style-card","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"20px"}},"layout":{"type":"constrained"}} -->
      <div class="wp-block-group is-style-card has-base-background-color has-background" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.2em"}},"textColor":"accent"} -->
        <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.2em">★★★★★</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.4","fontStyle":"normal","fontWeight":"500","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|40"}}}} -->
        <p style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--40);font-family:var(--wp--preset--font-family--newsreader);font-size:var(--wp--preset--font-size--large);font-style:normal;font-weight:500;line-height:1.4">"Our LCP dropped from 4.1s to 1.7s the day we switched. Self-hosted fonts and zero builder bloat."</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"bottom":"0"}}}} -->
        <p style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Priya Raman</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"4px","bottom":"0"}}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="margin-top:4px;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Frontend Lead · Mercato</p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"className":"is-style-card","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"20px"}},"layout":{"type":"constrained"}} -->
      <div class="wp-block-group is-style-card has-base-background-color has-background" style="border-radius:20px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.2em"}},"textColor":"accent"} -->
        <p class="has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.2em">★★★★★</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.4","fontStyle":"normal","fontWeight":"500","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|40"}}}} -->
        <p style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--40);font-family:var(--wp--preset--font-family--newsreader);font-size:var(--wp--preset--font-size--large);font-style:normal;font-weight:500;line-height:1.4">"Finally, a wp.org theme that doesn\'t look like a wp.org theme. Editorial typography, real spacing rhythm, accessibility actually built in."</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"bottom":"0"}}}} -->
        <p style="margin-bottom:0;font-size:var(--wp--preset--font-size--medium);font-weight:600;line-height:1.3">Sofia Lindqvist</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"4px","bottom":"0"}}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="margin-top:4px;margin-bottom:0;font-size:var(--wp--preset--font-size--small)">Editor-in-chief · Slowdown Mag</p>
        <!-- /wp:paragraph -->
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
