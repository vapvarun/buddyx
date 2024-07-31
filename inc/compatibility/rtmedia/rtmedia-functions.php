<?php
/**
 * Custom functions for bbPress
 *
 * @link    https://wbcomdesigns.com/
 * @package buddyx
 */

defined( 'ABSPATH' ) || exit;

/**
 * Custom filter to modify image URLs in RTMedia activity components.
 *
 * This function replaces the default image URL in RTMedia activity components
 * with a custom image URL based on a specific size. It only applies to media items
 * of type 'photo' and when the activity component is being displayed.
 *
 * @param string $html The HTML content of the activity item.
 * @param object $media The media object containing details about the media.
 *
 * @return string The modified HTML content with the custom image URL.
 */
function buddyx_custom_rtmedia_filter( $html, $media ) {
	// Check if the filter is enabled.
	if ( apply_filters( 'buddyx_rtmedia_filter_enabled', true ) ) {
		// Check if the media type is 'photo'.
		if ( $media->media_type === 'photo' ) {
			// Check if we are on the activity component page or group activity page.
			if ( bp_is_activity_component() || ( function_exists( 'bp_is_group' ) && bp_is_group() ) ) {
				// Retrieve the URL for the custom image size.
				$image_src = wp_get_attachment_image_src( $media->media_id, 'custom_rtmedia_size' );

				// Check if $image_src is a valid array and contains a non-empty URL.
				if ( is_array( $image_src ) && ! empty( $image_src[0] ) ) {
					// Sanitize the URL to prevent XSS.
					$custom_image_url = esc_url( $image_src[0] );

					// Replace the old image URL in the HTML with the new custom URL.
					$html = preg_replace(
						'/src="[^"]*"/',
						'src="' . $custom_image_url . '"',
						$html
					);
				}
			}
		}
	}
	return $html;
}

add_filter( 'rtmedia_single_activity_filter', 'buddyx_custom_rtmedia_filter', 10, 2 );