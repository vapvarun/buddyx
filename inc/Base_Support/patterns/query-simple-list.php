<?php
/**
 * Title: Simple item list
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Simple item list', 'buddyx' ),
	'categories' => array( 'buddyx-query' ),
	'content'    => '<!-- wp:query {"queryId":46,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","categoryIds":[],"tagIds":[],"order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"list","columns":3},"className":"is-style-default"} -->
    <div class="wp-block-query is-style-default"><!-- wp:post-template -->
    <!-- wp:group {"style":{"spacing":{"padding":{"bottom":"10px"}}},"layout":{"type":"flex","allowOrientation":false}} -->
    <div class="wp-block-group" style="padding-bottom:10px"><!-- wp:post-featured-image {"isLink":true,"width":"100px","height":"50px","style":{"spacing":{"margin":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}}} /-->

    <!-- wp:group {"style":{"spacing":{"blockGap":"6px","padding":{"left":"10px"}}},"className":"is-style-no-margin"} -->
    <div class="wp-block-group is-style-no-margin" style="padding-left:10px"><!-- wp:post-title {"level":6,"isLink":true,"style":{"typography":{"fontSize":"15px"},"spacing":{"margin":{"top":"0px","right":"0px","bottom":"5px","left":"0px"}}}} /-->

    <!-- wp:post-date {"style":{"typography":{"fontSize":"14px"},"color":{"text":"#6f8099"}}} /--></div>
    <!-- /wp:group --></div>
    <!-- /wp:group -->
    <!-- /wp:post-template --></div>
    <!-- /wp:query -->',
);
