<?php
/**
 * Template part for displaying a post's title
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<div class="entry-header-title">
	<?php
	// The view's main title (singular, or the sub-header outside the loop, e.g. BuddyPress directories) is the H1; loop cards are H2.
	if ( is_singular( get_post_type() ) || ! in_the_loop() ) {
		the_title( '<h1 class="entry-title">', '</h1>' );
	} else {
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	}
	?>
</div><!-- .entry-header-title -->
