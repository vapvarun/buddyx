<?php
/**
 * Template part for displaying a post's header
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<header class="entry-header">
	<?php
	if ( ! is_search() ) {
		get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() );
	}
	if ( ! is_singular() ) {
		get_template_part( 'template-parts/content/entry_title', get_post_type() );
	}
	if ( is_singular( 'post' ) ) {
		get_template_part( 'template-parts/content/entry_meta', get_post_type() );
	}
	?>
</header><!-- .entry-header -->
