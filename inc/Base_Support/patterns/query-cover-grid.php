<?php
/**
 * Pattern: Query - 3-column editorial grid with category labels.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Posts - 3-up Editorial Grid', 'buddyx' ),
	'categories' => array( 'buddyx-query', 'posts' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base-2","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
  <div class="wp-block-query alignwide">

    <!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->

      <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","style":{"border":{"radius":"12px"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} /-->

      <!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|x-small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} /-->

      <!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}}} /-->

      <!-- wp:post-excerpt {"moreText":"Read more →","excerptLength":18,"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.55"}},"textColor":"contrast-2"} /-->

    <!-- /wp:post-template -->

  </div>
  <!-- /wp:query -->

</div>
<!-- /wp:group -->
',
);
