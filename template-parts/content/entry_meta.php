<?php
/**
 * Template part for displaying a post's metadata
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

// Get post object and type once.
$post          = get_post();
$post_type_obj = get_post_type_object( get_post_type() );
$post_id       = get_the_ID();

// Initialize output variables.
$time_string   = '';
$author_string = '';
$parent_string = '';

// Cache commonly accessed values.
$blog_edit_link       = get_theme_mod( 'blog_edit_link', '' );
$is_buddypress_active = class_exists( 'BuddyPress' );

// Show date only when the post type is 'post' or has an archive.
if ( 'post' === $post_type_obj->name || $post_type_obj->has_archive ) {
	// Build time string only once.
	$is_modified = get_the_time( 'U' ) !== get_the_modified_time( 'U' );

	if ( $is_modified ) {
		$time_string = sprintf(
			'<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
	} else {
		$time_string = sprintf(
			'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
	}

	// Add permalink only once.
	$time_string = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
}

// Show author only if the post type supports it.
if ( post_type_supports( $post_type_obj->name, 'author' ) ) {
	// Get author ID once.
	$author_id = $post->post_author;

	// Get author link once.
	$author_url  = get_author_posts_url( $author_id );
	$author_name = get_the_author();

	$author_string = sprintf(
		'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
		esc_url( $author_url ),
		esc_html( $author_name )
	);
}

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
			// Only get avatar once if needed.
			$avatar        = '';
			$author_email  = get_the_author_meta( 'user_email', $post->post_author );
			$avatar_markup = get_avatar( $author_email, '38' );

			/* translators: %s: post author */
			if ( $is_buddypress_active ) {
				echo wp_kses_post( $avatar_markup );

				printf(
					_x( 'Written by %s', 'post author', 'buddyx' ),
					wp_kses_post( bp_core_get_userlink( $post->post_author ) )
				);
			} else {
				echo wp_kses_post( $avatar_markup );
				$author_byline = _x( 'Written by %s', 'post author', 'buddyx' );

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
	}
	?>
	</div><!-- .entry-meta__content -->

	<?php
	// Edit link - only shown when needed.
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) && ! empty( $blog_edit_link ) ) {
		edit_post_link( esc_html__( 'Edit', 'buddyx' ), '<span class="entry-edit-link">', '</span>' );
	}
	?>
</div><!-- .entry-meta -->
<?php
