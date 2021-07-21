<?php
/**
 * The template for displaying archive page content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_layout = $args['post_layout'];

$classes = array(
	'entry',
	'entry-layout',
	'buddyx-article',
);

$blog_grid_columns = get_theme_mod( 'blog_grid_columns', buddyx_defaults( 'blog-grid-columns' ) );

?>

<div class="post-layout <?php echo esc_attr( $post_layout ); ?>">
	<div class="buddyx-article--grid <?php echo esc_attr( $blog_grid_columns ); ?>">
		<?php
		while ( have_posts() ) {
			the_post();
			?>
			<div class="buddyx-article-col">
				<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
					<?php

						echo '<div class="buddyx-article-grid-thumbnail">';

							get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() );

						echo '</div>';

						echo '<div class="buddyx-article-grid-content">';

							get_template_part( 'template-parts/content/entry_categories', get_post_type() );

							get_template_part( 'template-parts/content/entry_title', get_post_type() );

							get_template_part( 'template-parts/content/entry_content', get_post_type() );

							get_template_part( 'template-parts/content/entry_meta', get_post_type() );

						echo '</div>';

					?>
				</article><!-- #post-<?php the_ID(); ?> -->
			</div>
			<?php
		}
		?>
	</div>
</div>
