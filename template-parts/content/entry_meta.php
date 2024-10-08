<?php
/**
 * Template part for displaying a post's metadata
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

$post_type_obj = get_post_type_object( get_post_type() );

$time_string = '';

// Show date only when the post type is 'post' or has an archive.
if ( 'post' === $post_type_obj->name || $post_type_obj->has_archive ) {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$time_string = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
}

$author_string = '';

// Show author only if the post type supports it.
if ( post_type_supports( $post_type_obj->name, 'author' ) ) {
	$author_string = sprintf(
		'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}

$parent_string = '';

// Show parent post only if available and if the post type is 'attachment'.
if ( ! empty( $post->post_parent ) && 'attachment' === get_post_type() ) {
	$parent_string = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( get_permalink( $post->post_parent ) ),
		esc_html( get_the_title( $post->post_parent ) )
	);
}

?>

<div class="entry-meta">
	<div class="entry-meta__content">
	<?php
	if ( ! empty( $author_string ) ) {
		?>
		<span class="posted-by">
			<?php
			/* translators: %s: post author */
			if ( class_exists( 'BuddyPress' ) ) {
				echo get_avatar( get_the_author_meta( 'user_email' ), '38' );
				printf( _x( 'Written by %s', 'post author', 'buddyx' ), bp_core_get_userlink( $post->post_author ) );
			}else {
				echo get_avatar( get_the_author_meta('user_email'), $size = '38');
				$author_byline = _x( 'Written by  %s', 'post author', 'buddyx' );
			}
			if ( ! empty( $time_string ) ) {
				/* translators: %s: post author */
				if ( ! class_exists( 'BuddyPress' ) ) {
					$author_byline = _x( 'Written by %s', 'post author', 'buddyx' );
				}
			}
			if ( ! class_exists( 'BuddyPress' ) ) {
				printf(
					esc_html( $author_byline ),
					$author_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
			?>
		</span>
		<?php
	}

	if ( ! empty( $parent_string ) ) {
		?>
		<span class="posted-in">
			<?php
			/* translators: %s: post parent title */
			$parent_note = _x( 'In %s', 'post parent', 'buddyx' );
			if ( ! empty( $time_string ) || ! empty( $author_string ) ) {
				/* translators: %s: post parent title */
				$parent_note = _x( 'in %s', 'post parent', 'buddyx' );
			}
			printf(
				esc_html( $parent_note ),
				$parent_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}

	if ( ! empty( $time_string ) ) {
		?>
		<span class="posted-on">
			<?php
			printf(
				/* translators: %s: post date */
				esc_html_x( '%s', 'post date', 'buddyx' ),
				$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}?>
	</div><!-- .entry-meta__content -->
	<?php
	$blog_edit_link = get_theme_mod( 'blog_edit_link', '' );
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) && ! empty( $blog_edit_link ) ) {
		edit_post_link( esc_html__( 'Edit', 'buddyx' ), '<span class="entry-edit-link">', '</span>' );
	}
	?>
</div><!-- .entry-meta -->
<?php
