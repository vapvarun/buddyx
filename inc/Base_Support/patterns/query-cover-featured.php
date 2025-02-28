<?php
/**
 * Featured section with cover images
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Featured section with cover images', 'buddyx' ),
	'categories' => array( 'buddyx-query' ),
	'content'    => '<!-- wp:group {"tagName":"main","metadata":{"categories":["buddyx-hero"],"patternName":"buddyx/parts-indextext","name":"Featured section with cover images"},"align":"full","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|70","top":"var:preset|spacing|30","right":"var:preset|spacing|50","left":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","wideSize":"1000px"}} -->
    <main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)"><!-- wp:query {"queryId":22,"query":{"perPage":"1","pages":"1","offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide","className":"is-style-default"} -->
    <div class="wp-block-query alignwide is-style-default"><!-- wp:post-template {"layout":{"type":"default","columnCount":2}} -->
    <!-- wp:group {"style":{"elements":{"link":{"color":{"text":"var:preset|color|textonbutton"}}},"spacing":{"padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|70","bottom":"var:preset|spacing|80","left":"var:preset|spacing|70"},"margin":{"bottom":"var:preset|spacing|70"}},"border":{"radius":"15px"}},"backgroundColor":"base","textColor":"textonbutton","layout":{"type":"default"}} -->
    <div class="wp-block-group has-textonbutton-color has-base-background-color has-text-color has-background has-link-color" style="border-radius:15px;margin-bottom:var(--wp--preset--spacing--70);padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--70)"><!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"70%"} -->
    <div class="wp-block-column" style="flex-basis:70%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
    <div class="wp-block-group"><!-- wp:post-terms {"term":"category","separator":"  ","className":"is-style-gs-tags-rounded","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"xsmall"} /-->

    <!-- wp:post-title {"level":1,"isLink":true,"style":{"typography":{"lineHeight":"1.4"}},"fontSize":"large"} /-->

    <!-- wp:post-excerpt {"className":"is-style-text-clamp-two"} /-->

    <!-- wp:group {"style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","allowOrientation":false}} -->
    <div class="wp-block-group"><!-- wp:post-author {"showAvatar":false,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"xsmall"} /-->

    <!-- wp:paragraph {"fontSize":"xsmall"} -->
    <p class="has-xsmall-font-size">/</p>
    <!-- /wp:paragraph -->

    <!-- wp:post-date {"fontSize":"xsmall"} /--></div>
    <!-- /wp:group --></div>
    <!-- /wp:group --></div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"30%"} -->
    <div class="wp-block-column" style="flex-basis:30%"><!-- wp:post-featured-image {"height":"100%","style":{"border":{"radius":"10px"}}} /--></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns --></div>
    <!-- /wp:group -->
    <!-- /wp:post-template --></div>
    <!-- /wp:query -->

    <!-- wp:query {"queryId":22,"query":{"perPage":4,"pages":0,"offset":"1","postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"wide","className":"is-style-default"} -->
    <div class="wp-block-query alignwide is-style-default"><!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
    <!-- wp:group {"style":{"spacing":{"blockGap":"15px"}}} -->
    <div class="wp-block-group"><!-- wp:post-featured-image {"height":"278px","style":{"border":{"radius":"12px"}}} /-->

    <!-- wp:post-terms {"term":"category","separator":"  ","className":"is-style-buddyx-tags-nounder","style":{"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"xsmall"} /-->

    <!-- wp:post-title {"isLink":true,"style":{"typography":{"lineHeight":"1.4"}},"fontSize":"big"} /-->

    <!-- wp:post-excerpt {"className":"is-style-text-clamp-two"} /-->

    <!-- wp:group {"style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","allowOrientation":false}} -->
    <div class="wp-block-group"><!-- wp:post-author {"showAvatar":false,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"xsmall"} /-->

    <!-- wp:paragraph {"textColor":"lightgrey","fontSize":"xsmall"} -->
    <p class="has-lightgrey-color has-text-color has-xsmall-font-size">/</p>
    <!-- /wp:paragraph -->

    <!-- wp:post-date {"textColor":"lightgrey","fontSize":"xsmall"} /--></div>
    <!-- /wp:group --></div>
    <!-- /wp:group -->
    <!-- /wp:post-template -->

    <!-- wp:spacer {"height":"30px"} -->
    <div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
    <!-- /wp:spacer -->

    <!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
    <!-- wp:query-pagination-previous /-->

    <!-- wp:query-pagination-numbers /-->

    <!-- wp:query-pagination-next /-->
    <!-- /wp:query-pagination --></div>
    <!-- /wp:query --></main>
    <!-- /wp:group -->',
);
