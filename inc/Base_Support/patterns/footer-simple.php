<?php
/**
 * Pattern: Footer - simple centered logo + nav + social row.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Footer - Simple Centered', 'buddyx' ),
	'categories' => array( 'buddyx-footer', 'footer' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base-2","layout":{"type":"constrained","contentSize":"880px"}} -->
<div class="wp-block-group alignfull has-base-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:heading {"textAlign":"center","level":3,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
  <h3 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.02em">BuddyX</h3>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"textColor":"contrast-2"} -->
  <p class="has-text-align-center has-contrast-2-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--medium);line-height:2"><a href="#patterns">Patterns</a> · <a href="#about">About</a> · <a href="#blog">Journal</a> · <a href="#docs">Docs</a> · <a href="#contact">Contact</a></p>
  <!-- /wp:paragraph -->

  <!-- wp:social-links {"iconColor":"contrast-2","iconColorValue":"#3D3D3D","openInNewTab":true,"size":"has-small-icon-size","align":"center","layout":{"type":"flex","justifyContent":"center"}} -->
  <ul class="wp-block-social-links aligncenter has-small-icon-size has-icon-color">
    <!-- wp:social-link {"url":"#","service":"twitter"} /-->
    <!-- wp:social-link {"url":"#","service":"github"} /-->
    <!-- wp:social-link {"url":"#","service":"mastodon"} /-->
    <!-- wp:social-link {"url":"#","service":"rss"} /-->
  </ul>
  <!-- /wp:social-links -->

  <!-- wp:separator {"className":"is-style-dotted","style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|30"}}}} -->
  <hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--30)"/>
  <!-- /wp:separator -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
  <p class="has-text-align-center has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)">© 2026 BuddyX · Free on wordpress.org · v5.0.3</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
',
);
