<?php
/**
 * Template part for displaying a post
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_per_row = 'col-md-' . get_theme_mod( 'post_per_row', buddyx_defaults( 'post-per-row' ) );

$classes = array(
	'entry',
	'entry-layout',
	$post_per_row,
);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="buddyx-post-content-wrapper">
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
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
