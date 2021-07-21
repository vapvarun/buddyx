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

$blog_masonry_view = get_theme_mod( 'blog_masonry_view', buddyx_defaults( 'blog-masonry-view' ) );
$post_per_row      = get_theme_mod( 'post_per_row', buddyx_defaults( 'post-per-row' ) );

$classes = array(
	'entry',
	'entry-layout',
	'buddyx-article',
	$post_per_row,
);

?>

<div class="post-layout <?php echo esc_attr( $post_layout ); ?>">
	<div class="buddyx-article--masonry <?php echo esc_attr( $blog_masonry_view ); ?> <?php echo esc_attr( $post_per_row ); ?>">
	<div class="buddyx-grid-sizer"></div>
		<?php
		while ( have_posts() ) {
			the_post();
			?>
		<div class="buddyx-article-col">
			<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
				<?php
					get_template_part( 'template-parts/content/entry_media', get_post_type() );

					get_template_part( 'template-parts/content/entry_categories', get_post_type() );

					get_template_part( 'template-parts/content/entry_title', get_post_type() );

					get_template_part( 'template-parts/content/entry_content', get_post_type() );

					get_template_part( 'template-parts/content/entry_meta', get_post_type() );

				?>
			</article><!-- #post-<?php the_ID(); ?> -->
		</div>
			<?php
		}
		?>
	</div>
</div>
