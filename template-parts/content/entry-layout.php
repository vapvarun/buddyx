<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-layout' ); ?>>
	<?php
	get_template_part( 'template-parts/content/entry_header', get_post_type() );

	if ( is_search() ) {
		get_template_part( 'template-parts/content/entry_summary', get_post_type() );
	} else {
		get_template_part( 'template-parts/content/entry_content', get_post_type() );
	}
	if ( is_singular() ) {
		get_template_part( 'template-parts/content/entry_footer', get_post_type() );
	}
	?>
</article><!-- #post-<?php the_ID(); ?> -->