<?php
/**
 * Template part for displaying a post's header
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$categories = get_the_category();

if ( ! empty( $categories ) ) : ?>
	<div class="post-meta-category">
		<?php foreach ( $categories as $key => $category ) : ?>
			<div class="post-meta-category__item">
				<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-meta-category__link">
					<?php echo esc_html( $category->name ); ?>
				</a>				
			</div><!-- .post-meta-category__item -->
			<?php
			if ( $key >= 0 ) :
					break;
				endif;
			?>
		<?php endforeach; ?>
	</div><!-- .post-meta-category -->
	<?php
endif;
