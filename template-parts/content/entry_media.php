<?php
/**
 * Template part for displaying a post's media
 *
 * @package buddyx
 */

 namespace BuddyX\Buddyx;

$allowed_post_formats = array( 'image', 'video', 'gallery', 'quote', 'link', 'audio' );

$post_format = get_post_format() ?: 'standard';
$item_format = $post_format === 'standard' ? 'image' : $post_format;

if ( in_array( $post_format, $allowed_post_formats, true ) ) {

	get_template_part( 'template-parts/post-format/entry', $item_format );
} else {
	if ( ! is_search() ) {
			get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() );
	}
}
