<?php
/**
 * Pattern: Footer - default mega 4-column with newsletter strip.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Footer - Default Mega', 'buddyx' ),
	'categories' => array( 'buddyx-footer', 'footer' ),
	'content'    => '
<!-- wp:group {"align":"full","backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column {"width":"34%"} -->
    <div class="wp-block-column" style="flex-basis:34%">
      <!-- wp:heading {"level":3,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|large","fontWeight":"700","lineHeight":"1.2","letterSpacing":"-0.015em"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|20"}}}} -->
      <h3 class="wp-block-heading has-newsreader-accent" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--large);font-weight:700;line-height:1.2;letter-spacing:-0.015em">BuddyX</h3>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--medium);line-height:1.6">Editorial-grade WordPress theme for sites that have something to say.</p>
      <!-- /wp:paragraph -->
      <!-- wp:social-links {"iconColor":"base","iconColorValue":"#ffffff","openInNewTab":true,"size":"has-small-icon-size"} -->
      <ul class="wp-block-social-links has-small-icon-size has-icon-color">
        <!-- wp:social-link {"url":"#","service":"twitter"} /-->
        <!-- wp:social-link {"url":"#","service":"github"} /-->
        <!-- wp:social-link {"url":"#","service":"mastodon"} /-->
        <!-- wp:social-link {"url":"#","service":"rss"} /-->
      </ul>
      <!-- /wp:social-links -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"22%"} -->
    <div class="wp-block-column" style="flex-basis:22%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Theme</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#patterns">Patterns</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#styles">Style variations</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#fonts">Typography</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#docs">Documentation</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"22%"} -->
    <div class="wp-block-column" style="flex-basis:22%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Studio</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#about">About us</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#manifesto">Manifesto</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#blog">Journal</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#contact">Contact</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"22%"} -->
    <div class="wp-block-column" style="flex-basis:22%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Legal</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#license">License</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#privacy">Privacy</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#terms">Terms</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#changelog">Changelog</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

  <!-- wp:separator {"className":"is-style-gradient","style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|30"}}}} -->
  <hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--30)"/>
  <!-- /wp:separator -->

  <!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}}} -->
  <div class="wp-block-columns alignwide are-vertically-aligned-center">
    <!-- wp:column {"verticalAlignment":"center","width":"66%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66%">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)">© 2026 BuddyX. Made with care in Lucknow. Free on wordpress.org.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->
    <!-- wp:column {"verticalAlignment":"center","width":"34%"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:34%">
      <!-- wp:paragraph {"align":"right","style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
      <p class="has-text-align-right has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)">v5.0.3 · <a href="#status">Status</a> · <a href="#rss">RSS</a></p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
',
);
