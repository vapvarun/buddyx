<?php
/**
 * Grid with cover images
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Grid with cover images', 'buddyx' ),
	'categories' => array( 'buddyx-query' ),
	'content'    => '<!-- wp:group {"metadata":{"categories":["buddyx-query"],"patternName":"buddyx/query-cover-grid","name":"Grid with cover images"},"align":"wide","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignwide"><!-- wp:query {"queryId":22,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
    <div class="wp-block-query alignwide"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
    <!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"customOverlayColor":"#89857f","isUserOverlayColor":true,"minHeight":250,"minHeightUnit":"px","style":{"border":{"radius":"15px"},"color":{"duotone":"unset"}}} -->
    <div class="wp-block-cover" style="border-radius:15px;min-height:250px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim" style="background-color:#89857f"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"10px","padding":{"top":"8px","right":"8px","bottom":"8px","left":"8px"}}}} -->
    <div class="wp-block-group" style="padding-top:8px;padding-right:8px;padding-bottom:8px;padding-left:8px"><!-- wp:post-terms {"term":"category","separator":"  ","className":"is-style-buddyx-tags-nounder","style":{"typography":{"textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"fontSize":"xsmall"} /-->

    <!-- wp:post-title {"isLink":true,"style":{"typography":{"lineHeight":"1.4"},"elements":{"link":{"color":{"text":"var:preset|color|textonprimary"}}}},"textColor":"base","fontSize":"big"} /-->

    <!-- wp:group {"style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","allowOrientation":false}} -->
    <div class="wp-block-group"><!-- wp:post-author {"showAvatar":false,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"color":{"text":"#fcfcfc"}},"fontSize":"xsmall"} /-->

    <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"xsmall"} -->
    <p class="has-base-color has-text-color has-link-color has-xsmall-font-size">/</p>
    <!-- /wp:paragraph -->

    <!-- wp:post-date {"style":{"color":{"text":"#c1cfdf"}},"fontSize":"xsmall"} /--></div>
    <!-- /wp:group --></div>
    <!-- /wp:group --></div></div>
    <!-- /wp:cover -->
    <!-- /wp:post-template -->

    <!-- wp:spacer {"height":"10px"} -->
    <div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
    <!-- /wp:spacer -->

    <!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
    <!-- wp:query-pagination-previous /-->

    <!-- wp:query-pagination-numbers /-->

    <!-- wp:query-pagination-next /-->
    <!-- /wp:query-pagination --></div>
    <!-- /wp:query --></div>
    <!-- /wp:group -->',
);
