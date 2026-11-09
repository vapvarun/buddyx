<?php
/**
 * Template part for displaying a post's metadata
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

// Get post object and type once.
$meta_post     = get_post();
$post_type_obj = get_post_type_object( get_post_type() );
$meta_post_id  = get_the_ID();

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

// Show author(s) only if the post type supports it. Co-Authors Plus adds
// co-authors (users or guest authors); every one is named and linked.
$author_links = array();
$authors      = array();
if ( post_type_supports( $post_type_obj->name, 'author' ) ) {
	$authors = function_exists( 'get_coauthors' ) ? get_coauthors( $meta_post_id ) : array();
	if ( empty( $authors ) ) {
		$authors = array( get_userdata( (int) $meta_post->post_author ) );
	}
	foreach ( array_filter( $authors ) as $author ) {
		$is_user = ! isset( $author->type ) || 'wpuser' === $author->type;
		if ( $is_buddypress_active && $is_user ) {
			$author_links[] = bp_core_get_userlink( $author->ID );
		} else {
			$author_links[] = sprintf(
				'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ),
				esc_html( $author->display_name )
			);
		}
	}
	$author_string = wp_sprintf( '%l', $author_links );
}

// Show parent post only if available and if the post type is 'attachment'.
if ( ! empty( $meta_post->post_parent ) && 'attachment' === get_post_type() ) {
	$parent_string = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( get_permalink( $meta_post->post_parent ) ),
		esc_html( get_the_title( $meta_post->post_parent ) )
	);
}

?>

<div class="entry-meta">
	<div class="entry-meta__content">
	<?php
	if ( ! empty( $author_string ) ) {
		// One avatar per author: up to three overlap in the avatar slot, then "+N".
		// Guest authors' IDs are not user IDs, so Co-Authors Plus resolves their
		// avatar. The names beside them are the links, so the avatars are decorative.
		$authors = array_values( array_filter( $authors ) );
		$avatars = array();
		foreach ( array_slice( $authors, 0, 3 ) as $author ) {
			$avatars[] = function_exists( 'coauthors_get_avatar' ) ? coauthors_get_avatar( $author, 38 ) : get_avatar( $author->ID, 38 );
		}
		if ( empty( $avatars ) ) {
			$avatars[] = get_avatar( $meta_post->post_author, 38 );
		}
		$extra_authors = count( $authors ) - count( $avatars );
		if ( $extra_authors > 0 ) {
			$avatars[] = '<span class="bx-byline-avatars__more" dir="ltr">+' . (int) $extra_authors . '</span>';
		}
		?>
		<span class="posted-by<?php echo count( $avatars ) > 1 ? ' posted-by--avatars-' . (int) count( $avatars ) : ''; ?>">
			<?php
			if ( count( $avatars ) > 1 ) {
				echo '<span class="bx-byline-avatars" aria-hidden="true">' . wp_kses_post( implode( '', $avatars ) ) . '</span>';
			} else {
				echo wp_kses_post( $avatars[0] );
			}
			printf(
				/* translators: %s: post author name(s), e.g. "Ana, Ben and Cy" */
				esc_html_x( 'Written by %s', 'post author', 'buddyx' ),
				wp_kses_post( $author_string )
			);
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
				$parent_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from esc_url() and esc_html() above.
			);
			?>
		</span>
		<?php
	}

	if ( ! empty( $time_string ) ) {
		?>
		<span class="posted-on">
			<?php
			// $time_string is theme-generated <time> markup; output directly.
			echo wp_kses_post( $time_string );
			?>
		</span>
		<?php
	}
	?>
	</div><!-- .entry-meta__content -->

	<?php
	// Edit link - only shown when needed.
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) && buddyx_is_truthy( $blog_edit_link ) ) {
		edit_post_link( esc_html__( 'Edit', 'buddyx' ), '<span class="entry-edit-link">', '</span>' );
	}
	?>
</div><!-- .entry-meta -->
<?php
