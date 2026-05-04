<?php
/**
 * Pattern: Query — minimal date + title list (table-of-contents style).
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Posts — Minimal Date + Title List', 'buddyx' ),
	'categories' => array( 'buddyx-query', 'posts' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
  <p class="has-contrast-3-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Archive</p>
  <!-- /wp:paragraph -->

  <!-- wp:query {"queryId":1,"query":{"perPage":8,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
  <div class="wp-block-query">

    <!-- wp:post-template -->

      <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|10","left":"var:preset|spacing|30"},"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"top":{"color":"var:preset|color|base-3","width":"1px"}}}} -->
      <div class="wp-block-columns are-vertically-aligned-center" style="border-top-color:var(--wp--preset--color--base-3);border-top-width:1px;padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)">

        <!-- wp:column {"verticalAlignment":"center","width":"100px"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:100px">
          <!-- wp:post-date {"format":"M j","style":{"typography":{"fontSize":"var:preset|font-size|small","fontWeight":"500"}},"textColor":"contrast-3"} /-->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
          <!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|medium","fontWeight":"500","lineHeight":"1.4"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} /-->
        </div>
        <!-- /wp:column -->

      </div>
      <!-- /wp:columns -->

    <!-- /wp:post-template -->

  </div>
  <!-- /wp:query -->

</div>
<!-- /wp:group -->
',
);
