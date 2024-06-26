<?php
/**
 * BuddyX\Buddyx\Image_Sizes\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Image_Sizes;

use BuddyX\Buddyx\Component_Interface;
use function BuddyX\Buddyx\buddyx;
use WP_Post;
use function add_filter;
use function is_active_sidebar;

/**
 * Class for managing responsive image sizes.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'image_sizes';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'wp_calculate_image_sizes', array( $this, 'filter_content_image_sizes_attr' ), 10, 2 );
		add_filter( 'get_header_image_tag', array( $this, 'filter_header_image_tag' ), 10, 3 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'filter_post_thumbnail_sizes_attr' ), 10, 3 );
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for content images.
	 *
	 * @param string $sizes A source size value for use in a 'sizes' attribute.
	 * @param array  $size  Image size. Accepts an array of width and height
	 *                      values in pixels (in that order).
	 * @return string A source size value for use in a content image 'sizes' attribute.
	 */
	public function filter_content_image_sizes_attr( string $sizes, array $size ): string {
		$width = $size[0];

		if ( 740 <= $width ) {
			$sizes = '100vw';
		}

		if ( buddyx()->is_right_sidebar_active() ) {
			$sizes = '(min-width: 960px) 75vw, 100vw';
		}

		return $sizes;
	}

	/**
	 * Filters the `sizes` value in the header image markup.
	 *
	 * @param string $html   The HTML image tag markup being filtered.
	 * @param object $header The custom header object returned by 'get_custom_header()'.
	 * @param array  $attr   Array of the attributes for the image tag.
	 * @return string The filtered header image HTML.
	 */
	public function filter_header_image_tag( string $html, $header, array $attr ): string {
		if ( isset( $attr['sizes'] ) ) {
			$html = str_replace( $attr['sizes'], '100vw', $html );
		}

		return $html;
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for post thumbnails.
	 *
	 * @param array        $attr       Attributes for the image markup.
	 * @param mixed        $attachment Attachment post object or other types.
	 * @param string|array $size       Registered image size or flat array of height and width dimensions.
	 * @return array The filtered attributes for the image markup.
	 */
	public function filter_post_thumbnail_sizes_attr( array $attr, $attachment, $size ): array {
		// Ensure $attachment is an instance of WP_Post.
		if ( ! $attachment instanceof WP_Post ) {
			return $attr; // Return the original attributes if $attachment is not a WP_Post instance.
		}

		$attr['sizes'] = '100vw';

		if ( buddyx()->is_right_sidebar_active() ) {
			$attr['sizes'] = '(min-width: 960px) 75vw, 100vw';
		}

		return $attr;
	}
}
