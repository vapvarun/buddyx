<?php
/**
 * Template part for displaying a post's tags
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

// Get tags associated with the post.
$tags = get_the_tags();

$tags_style = get_theme_mod( 'blog_show_tags_style', 'default' );

if ( ! empty( $tags ) ) : ?>
	<div class="post-meta-tags <?php echo esc_attr( $tags_style ); ?>">
		<?php foreach ( $tags as $key => $bx_tag ) : ?>
			<div class="post-meta-tag__item">
				<a href="<?php echo esc_url( get_tag_link( $bx_tag->term_id ) ); ?>" class="post-meta-tag__link">
					<?php echo esc_html( $bx_tag->name ); ?>
				</a>
			<?php
			if ( 'default' === $tags_style || 'underline' === $tags_style ) {
				// Add a comma separator after the tag, except for the last tag.
				if ( $key < count( $tags ) - 1 ) :
					echo ', ';
					endif;
			}
			?>
			</div><!-- .post-meta-tag__item -->
		<?php endforeach; ?>
	</div><!-- .post-meta-tags -->
<?php endif; ?>
