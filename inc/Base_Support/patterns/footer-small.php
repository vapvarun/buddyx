<?php
/**
 * Pattern: Footer - small / minimal copyright bar.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Footer - Minimal Bar', 'buddyx' ),
	'categories' => array( 'buddyx-footer', 'footer' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|base-3","width":"1px"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="border-top-color:var(--wp--preset--color--base-3);border-top-width:1px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">

    <!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-2"} -->
      <p class="has-contrast-2-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)"><strong>BuddyX</strong> · © 2026 · v5.0.3</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
      <!-- wp:paragraph {"align":"right","style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-text-align-right has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)"><a href="#privacy">Privacy</a> · <a href="#terms">Terms</a> · <a href="#rss">RSS</a></p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
