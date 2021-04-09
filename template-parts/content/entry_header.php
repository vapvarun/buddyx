<?php
/**
 * Template part for displaying a post's header
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$single_post_meta = get_theme_mod( 'single_post_meta', buddyx_defaults( 'single-post-meta' ) );
$single_post_categories = get_theme_mod( 'single_post_categories', buddyx_defaults( 'single-post-categories' ) );

?>

<header class="entry-header">
	<?php
	if ( ! is_search() ) {
		get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() );
	}
	?>

	<?php
	if ( is_single() && ! is_home() ) {
		if ( ! empty( $single_post_categories ) ) { ?>
			<div class="post-categories"><?php the_category( ' ' ); ?></div>
		<?php
		}
	} else { ?>
		<div class="post-categories"><?php the_category( ' ' ); ?></div>
	<?php
	}
	?>
	
	<?php
	if ( ! is_singular() ) {
		get_template_part( 'template-parts/content/entry_title', get_post_type() );
	}

	if ( is_single() && ! is_home() ) {
		if ( ! empty( $single_post_meta ) ) {
			get_template_part( 'template-parts/content/entry_meta', get_post_type() );
		}
	} else {
		get_template_part( 'template-parts/content/entry_meta', get_post_type() );
	}
	?>
</header><!-- .entry-header -->
