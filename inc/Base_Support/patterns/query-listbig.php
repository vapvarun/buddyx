<?php
/**
 * Title: List with big images
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'List with big images', 'buddyx' ),
	'categories' => array( 'buddyx-query' ),
	'content'    => '<!-- wp:group {"metadata":{"categories":["buddyx-query"],"patternName":"buddyx/query-listbig","name":"Title: List with big images"},"align":"wide","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignwide"><!-- wp:query {"queryId":10,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
    <div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default"}} -->
    <!-- wp:columns {"align":"wide","className":"gspbquery-animate gs-move-up","style":{"spacing":{"blockGap":"0px"}}} -->
    <div class="wp-block-columns alignwide gspbquery-animate gs-move-up"><!-- wp:column {"width":"55%"} -->
    <div class="wp-block-column" style="flex-basis:55%"><!-- wp:post-featured-image {"isLink":true,"height":"300px","className":"gs-hover-scale-img","style":{"spacing":{"margin":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}}} /--></div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center","width":"45%","className":"is-style-default","backgroundColor":"background"} -->
    <div class="wp-block-column is-vertically-aligned-center is-style-default has-background-background-color has-background" style="flex-basis:45%"><!-- wp:group {"className":"gs-mobile-paddingdisable","style":{"spacing":{"padding":{"top":"10%","right":"10%","bottom":"10%","left":"10%"}}}} -->
    <div class="wp-block-group gs-mobile-paddingdisable" style="padding-top:10%;padding-right:10%;padding-bottom:10%;padding-left:10%"><!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","allowOrientation":false}} -->
    <div class="wp-block-group"><!-- wp:post-terms {"term":"category","separator":"  ","className":"is-style-buddyxquery-tags-nounder","style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"textTransform":"uppercase","fontSize":"0.8rem"}}} /-->

    <!-- wp:paragraph {"className":"is-style-no-margin","style":{"typography":{"fontSize":"14px"},"color":{"text":"#bfbfbf"}}} -->
    <p class="is-style-no-margin has-text-color" style="color:#bfbfbf;font-size:14px">|</p>
    <!-- /wp:paragraph -->

    <!-- wp:post-author {"showAvatar":false,"style":{"typography":{"fontSize":"0.8rem"}}} /--></div>
    <!-- /wp:group -->

    <!-- wp:post-title {"isLink":true,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.81rem"}},"typography":{"fontSize":"22px"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"},":hover":{"color":{"text":"var:preset|color|primary"}}}}}} /-->

    <!-- wp:post-date {"style":{"color":{"text":"#bfbfbf"}},"fontSize":"small"} /--></div>
    <!-- /wp:group --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns -->
    <!-- /wp:post-template -->

    <!-- wp:spacer {"height":"40px"} -->
    <div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
    <!-- /wp:spacer -->

    <!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
    <!-- wp:query-pagination-previous /-->

    <!-- wp:query-pagination-numbers /-->

    <!-- wp:query-pagination-next /-->
    <!-- /wp:query-pagination --></div>
    <!-- /wp:query --></div>
    <!-- /wp:group -->',
);
