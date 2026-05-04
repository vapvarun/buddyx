<?php
/**
 * Pattern: Footer — 5-column mega with newsletter pull.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'Footer — Mega 5-column', 'buddyx' ),
	'categories' => array( 'buddyx-footer', 'footer' ),
	'content'    => '
<!-- wp:group {"align":"full","backgroundColor":"surface-3","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-base-color has-surface-3-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column {"width":"30%"} -->
    <div class="wp-block-column" style="flex-basis:30%">
      <!-- wp:heading {"level":3,"className":"has-newsreader-accent","style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","letterSpacing":"-0.02em"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|20"}}}} -->
      <h3 class="wp-block-heading has-newsreader-accent" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--20);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.02em">BuddyX</h3>
      <!-- /wp:heading -->
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"1.6"}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);line-height:1.6">An editorial-grade WordPress theme with a designer pattern library. Free on wp.org.</p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Theme</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#patterns">Patterns</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#starters">Starter sites</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#fonts">Typography</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Resources</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#docs">Docs</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#changelog">Changelog</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#github">GitHub</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Studio</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#about">About</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#contact">Contact</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#blog">Journal</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.12em","textTransform":"uppercase","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}},"textColor":"contrast-3"} -->
      <p class="has-contrast-3-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-size:var(--wp--preset--font-size--small);font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Legal</p>
      <!-- /wp:paragraph -->
      <!-- wp:list {"className":"is-style-stacked-links","style":{"typography":{"fontSize":"var:preset|font-size|medium","lineHeight":"2"}}} -->
      <ul class="wp-block-list is-style-stacked-links" style="font-size:var(--wp--preset--font-size--medium);line-height:2">
        <!-- wp:list-item --><li><a href="#license">License</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#privacy">Privacy</a></li><!-- /wp:list-item -->
        <!-- wp:list-item --><li><a href="#terms">Terms</a></li><!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

  <!-- wp:separator {"className":"is-style-gradient","style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|30"}}}} -->
  <hr class="wp-block-separator has-alpha-channel-opacity is-style-gradient" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--30)"/>
  <!-- /wp:separator -->

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"contrast-3"} -->
  <p class="has-text-align-center has-contrast-3-color has-text-color" style="margin-bottom:0;font-size:var(--wp--preset--font-size--small)">© 2026 BuddyX · v5.0.3 · Free on wordpress.org</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
',
);
