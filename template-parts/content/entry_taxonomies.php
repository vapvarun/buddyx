<?php
/**
 * Template part for displaying a post's taxonomy terms
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$taxonomies = wp_list_filter(
	get_object_taxonomies( $post, 'objects' ),
	array(
		'public' => true,
	)
);

?>
<div class="entry-taxonomies">
	<?php
	// Show terms for all taxonomies associated with the post.
	foreach ( $taxonomies as $taxo ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		/* translators: separator between taxonomy terms */
		$separator = _x( ', ', 'list item separator', 'buddyx' );

		switch ( $taxo->name ) {
			case 'category':
				$class = 'category-links term-links';
				$list  = get_the_category_list( esc_html( $separator ), '', $post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Posted in %s', 'buddyx' );
				break;
			case 'post_tag':
				$class = 'tag-links term-links';
				$list  = get_the_tag_list( '', esc_html( $separator ), '', $post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Tagged %s', 'buddyx' );
				break;
			default:
				$class            = str_replace( '_', '-', $taxo->name ) . '-links term-links';
				$list             = get_the_term_list( $post->ID, $taxo->name, '', esc_html( $separator ), '' );
				$placeholder_text = sprintf(
					/* translators: %s: taxonomy name */
					__( '%s:', 'buddyx' ),
					$taxo->labels->name // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
		}

		if ( empty( $list ) ) {
			continue;
		}
		?>
		<span class="<?php echo esc_attr( $class ); ?>">
			<?php
			printf(
				esc_html( $placeholder_text ),
				$list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}
	?>
</div><!-- .entry-taxonomies -->
