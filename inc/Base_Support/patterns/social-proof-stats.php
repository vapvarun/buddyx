<?php
/**
 * Pattern: Social proof - stats row.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Stats Row', 'buddyx' ),
	'categories' => array( 'buddyx-social-proof', 'featured' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"surface-2","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-surface-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(3rem, 5vw + 1rem, 5rem)","fontWeight":"500","lineHeight":"1","fontFamily":"var:preset|font-family|newsreader","letterSpacing":"-0.02em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <p style="margin-bottom:var(--wp--preset--spacing--20);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(3rem, 5vw + 1rem, 5rem);font-weight:500;line-height:1;letter-spacing:-0.02em">27</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.08em","textTransform":"uppercase","fontWeight":"500"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small);font-weight:500;letter-spacing:0.08em;text-transform:uppercase">Block patterns</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(3rem, 5vw + 1rem, 5rem)","fontWeight":"500","lineHeight":"1","fontFamily":"var:preset|font-family|newsreader","letterSpacing":"-0.02em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <p style="margin-bottom:var(--wp--preset--spacing--20);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(3rem, 5vw + 1rem, 5rem);font-weight:500;line-height:1;letter-spacing:-0.02em">5★</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.08em","textTransform":"uppercase","fontWeight":"500"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small);font-weight:500;letter-spacing:0.08em;text-transform:uppercase">wp.org rating</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(3rem, 5vw + 1rem, 5rem)","fontWeight":"500","lineHeight":"1","fontFamily":"var:preset|font-family|newsreader","letterSpacing":"-0.02em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <p style="margin-bottom:var(--wp--preset--spacing--20);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(3rem, 5vw + 1rem, 5rem);font-weight:500;line-height:1;letter-spacing:-0.02em">12</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.08em","textTransform":"uppercase","fontWeight":"500"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small);font-weight:500;letter-spacing:0.08em;text-transform:uppercase">Color tokens</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"clamp(3rem, 5vw + 1rem, 5rem)","fontWeight":"500","lineHeight":"1","fontFamily":"var:preset|font-family|newsreader","letterSpacing":"-0.02em"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
      <p style="margin-bottom:var(--wp--preset--spacing--20);font-family:var(--wp--preset--font-family--newsreader);font-size:clamp(3rem, 5vw + 1rem, 5rem);font-weight:500;line-height:1;letter-spacing:-0.02em">8</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.08em","textTransform":"uppercase","fontWeight":"500"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small);font-weight:500;letter-spacing:0.08em;text-transform:uppercase">Type sizes</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
