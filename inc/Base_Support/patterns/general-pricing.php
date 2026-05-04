<?php
/**
 * Pattern: Pricing — 3-tier with elevated middle column.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Pricing — 3-tier Editorial', 'buddyx' ),
	'categories' => array( 'buddyx-pricing-faq', 'featured' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
  <div class="wp-block-group">
    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
    <p class="has-text-align-center has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Pricing</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading {"textAlign":"center","level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|60"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--60);font-size:var(--wp--preset--font-size--x-large);font-weight:700;line-height:1.1;letter-spacing:-0.02em">Three plans. <em>One</em> bill a year.</h2>
    <!-- /wp:heading -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">

    <!-- wp:column {"verticalAlignment":"center"} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:group {"className":"is-style-bordered","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"24px"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
      <div class="wp-block-group is-style-bordered has-base-background-color has-background" style="border-radius:24px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Starter</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(2.5rem, 4vw + 1rem, 4rem)","fontWeight":"700","lineHeight":"1","letterSpacing":"-0.02em","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|10"}}}} -->
        <p style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--10);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(2.5rem, 4vw + 1rem, 4rem);font-weight:700;line-height:1;letter-spacing:-0.02em">$0</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--small)">Free, forever, on wp.org</p>
        <!-- /wp:paragraph -->

        <!-- wp:list {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.8"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
        <ul class="wp-block-list" style="margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--medium);line-height:1.8">
          <!-- wp:list-item --><li>27 premium patterns</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Inter + Newsreader self-hosted</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Light + Editorial style variations</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Community support</li><!-- /wp:list-item -->
        </ul>
        <!-- /wp:list -->

        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"stretch"}} -->
        <div class="wp-block-buttons">
          <!-- wp:button {"className":"is-style-outline-accent","width":100} -->
          <div class="wp-block-button is-style-outline-accent has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button">Download free</a></div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->

      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center"} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:group {"backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"24px"}},"layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background" style="border-radius:24px;padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent-3"} -->
        <p class="has-accent-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Studio · Most popular</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(2.5rem, 4vw + 1rem, 4rem)","fontWeight":"700","lineHeight":"1","letterSpacing":"-0.02em","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|10"}}}} -->
        <p style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--10);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(2.5rem, 4vw + 1rem, 4rem);font-weight:700;line-height:1;letter-spacing:-0.02em">$49<span style="font-size:var(--wp--preset--font-size--medium);font-weight:400;font-family:var(--wp--preset--font-family--inter)">/yr</span></p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}}} -->
        <p style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--small)">For freelancers and small teams</p>
        <!-- /wp:paragraph -->

        <!-- wp:list {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.8"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
        <ul class="wp-block-list" style="margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--medium);line-height:1.8">
          <!-- wp:list-item --><li>Everything in Starter</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>3 starter sites (Agency, Magazine, Studio)</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Premium support, 48h response</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Unlimited installs on your sites</li><!-- /wp:list-item -->
        </ul>
        <!-- /wp:list -->

        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"stretch"}} -->
        <div class="wp-block-buttons">
          <!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"border":{"radius":"999px"}},"width":100} -->
          <div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" style="border-radius:999px">Choose Studio</a></div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->

      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center"} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:group {"className":"is-style-bordered","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"24px"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
      <div class="wp-block-group is-style-bordered has-base-background-color has-background" style="border-radius:24px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Agency</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(2.5rem, 4vw + 1rem, 4rem)","fontWeight":"700","lineHeight":"1","letterSpacing":"-0.02em","fontFamily":"var:preset|font-family|newsreader"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|10"}}}} -->
        <p style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--10);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(2.5rem, 4vw + 1rem, 4rem);font-weight:700;line-height:1;letter-spacing:-0.02em">$129<span style="font-size:var(--wp--preset--font-size--medium);font-weight:400;font-family:var(--wp--preset--font-family--inter)">/yr</span></p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}},"textColor":"contrast-3"} -->
        <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--small)">For studios shipping multiple client sites</p>
        <!-- /wp:paragraph -->

        <!-- wp:list {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.8"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
        <ul class="wp-block-list" style="margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--medium);line-height:1.8">
          <!-- wp:list-item --><li>Everything in Studio</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>10 starter sites</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Priority support, 24h response</li><!-- /wp:list-item -->
          <!-- wp:list-item --><li>Brandable client handoff docs</li><!-- /wp:list-item -->
        </ul>
        <!-- /wp:list -->

        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"stretch"}} -->
        <div class="wp-block-buttons">
          <!-- wp:button {"className":"is-style-outline-accent","width":100} -->
          <div class="wp-block-button is-style-outline-accent has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button">Choose Agency</a></div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->

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
