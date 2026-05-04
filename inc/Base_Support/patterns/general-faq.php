<?php
/**
 * Pattern: FAQ — typography-led accordion.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'FAQ — Typography-led', 'buddyx' ),
	'categories' => array( 'buddyx-pricing-faq', 'text' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"880px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.08em","textTransform":"uppercase"},"color":{"text":"#6e6e6e"}}} -->
  <p class="has-text-align-center has-text-color" style="color:#6e6e6e;font-size:var(--wp--preset--font-size--small);letter-spacing:0.08em;text-transform:uppercase">FAQ</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","letterSpacing":"-0.015em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|50"}}}} -->
  <h2 class="wp-block-heading has-text-align-center" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.015em">Questions, answered.</h2>
  <!-- /wp:heading -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>What does the theme include out of the box?</strong></summary>
  <!-- wp:paragraph --><p>A library of premium block patterns covering hero, about, services, testimonials, pricing, FAQ, CTAs and more — full Site Editor support, fluid type scale, light and dark style variations, ten spacing presets. No plugin required.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

  <!-- wp:separator {"className":"is-style-dotted"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted"/><!-- /wp:separator -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>Will it work without page builders?</strong></summary>
  <!-- wp:paragraph --><p>Yes. Patterns assemble from core WordPress blocks only — no Elementor, no Divi, no proprietary block plugins. Drop them in, edit the copy, ship.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

  <!-- wp:separator {"className":"is-style-dotted"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted"/><!-- /wp:separator -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>Can I switch the typography?</strong></summary>
  <!-- wp:paragraph --><p>Pick the Editorial style variation in Site Editor and the entire site flips to Newsreader serif headings. Or set your own font in Global Styles.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

  <!-- wp:separator {"className":"is-style-dotted"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted"/><!-- /wp:separator -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>Is it accessible?</strong></summary>
  <!-- wp:paragraph --><p>WCAG 2.1 AA. Visible focus rings via :focus-visible, real label associations on form inputs, no aria-hidden on focusable elements, and keyboard parity across every pattern.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

</div>
<!-- /wp:group -->
',
);
