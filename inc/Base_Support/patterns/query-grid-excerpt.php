<?php
/**
 * Pattern: Query - asymmetric 2-up (large hero + 4-stack).
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Posts - Asymmetric Hero + Stack', 'buddyx' ),
	'categories' => array( 'buddyx-query', 'posts' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"textColor":"contrast-3"} -->
  <p class="has-contrast-3-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">Latest stories</p>
  <!-- /wp:paragraph -->

  <!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
  <div class="wp-block-query alignwide">

    <!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->

      <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","style":{"border":{"radius":"12px"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} /-->

      <!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|x-small","letterSpacing":"0.1em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} /-->

      <!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}}} /-->

      <!-- wp:post-excerpt {"moreText":"Read more →","excerptLength":20,"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"}},"textColor":"contrast-2"} /-->

      <!-- wp:post-date {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"textColor":"contrast-3"} /-->

    <!-- /wp:post-template -->

  </div>
  <!-- /wp:query -->

</div>
<!-- /wp:group -->
',
);
