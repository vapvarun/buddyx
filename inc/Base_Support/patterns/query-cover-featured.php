<?php
/**
 * Pattern: Query - recent posts grid (4 cards with featured image).
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Posts - 4-up Featured Cards', 'buddyx' ),
	'categories' => array( 'buddyx-query', 'posts' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.14em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"contrast-3"} -->
  <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.14em;text-transform:uppercase">From the journal</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|50"}}}} -->
  <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.02em;line-height:1.1">Recent <em>writing</em>.</h2>
  <!-- /wp:heading -->

  <!-- wp:query {"queryId":1,"query":{"perPage":4,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
  <div class="wp-block-query alignwide">

    <!-- wp:post-template {"layout":{"type":"grid","columnCount":4}} -->

      <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","style":{"border":{"radius":"16px"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} /-->

      <!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|x-small","letterSpacing":"0.1em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} /-->

      <!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}}} /-->

      <!-- wp:post-date {"style":{"typography":{"fontSize":"var:preset|font-size|small"}},"textColor":"contrast-3"} /-->

    <!-- /wp:post-template -->

  </div>
  <!-- /wp:query -->

</div>
<!-- /wp:group -->
',
);
