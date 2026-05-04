<?php
/**
 * Pattern: Footer - central CTA + nav.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Footer - Central CTA', 'buddyx' ),
	'categories' => array( 'buddyx-footer', 'footer' ),
	'content'    => '
<!-- wp:group {"align":"full","gradient":"warm-glow","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"880px"}} -->
<div class="wp-block-group alignfull has-warm-glow-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.18em","textTransform":"uppercase","fontWeight":"600"}},"textColor":"accent"} -->
  <p class="has-text-align-center has-accent-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.18em;text-transform:uppercase">Let\'s build something</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"textAlign":"center","level":2,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","lineHeight":"1.1","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|40"}}}} -->
  <h2 class="wp-block-heading has-text-align-center has-newsreader-accent" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--40);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.02em;line-height:1.1">A site you\'re <em>actually</em> proud of.</h2>
  <!-- /wp:heading -->

  <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
  <div class="wp-block-buttons">
    <!-- wp:button {"backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"radius":"999px"}}} -->
    <div class="wp-block-button"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background wp-element-button" href="#start" style="border-radius:999px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)">Start your project</a></div>
    <!-- /wp:button -->
  </div>
  <!-- /wp:buttons -->

  <!-- wp:separator {"className":"is-style-dotted","style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|30"}}}} -->
  <hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--30)"/>
  <!-- /wp:separator -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-2"} -->
  <p class="has-text-align-center has-contrast-2-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)"><a href="#about">About</a> · <a href="#patterns">Patterns</a> · <a href="#docs">Docs</a> · <a href="#contact">Contact</a> · © 2026</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
',
);
