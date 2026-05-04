<?php
/**
 * Pattern: Query — horizontal list with split image+text.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Posts — Horizontal List Split', 'buddyx' ),
	'categories' => array( 'buddyx-query', 'posts' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:heading {"level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|50"}}}} -->
  <h2 class="wp-block-heading has-newsreader-accent" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.02em">Long <em>reads</em>.</h2>
  <!-- /wp:heading -->

  <!-- wp:query {"queryId":1,"query":{"perPage":4,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
  <div class="wp-block-query alignwide">

    <!-- wp:post-template -->

      <!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|50"},"margin":{"bottom":"var:preset|spacing|40"}}}} -->
      <div class="wp-block-columns alignwide are-vertically-aligned-center" style="margin-bottom:var(--wp--preset--spacing--40)">

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%">
          <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","style":{"border":{"radius":"12px"}}} /-->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">
          <!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|x-small","letterSpacing":"0.1em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} /-->
          <!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|20"}}}} /-->
          <!-- wp:post-excerpt {"moreText":"Read more →","excerptLength":24,"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"}},"textColor":"contrast-2"} /-->
          <!-- wp:post-date {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"textColor":"contrast-3"} /-->
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
