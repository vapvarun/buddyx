<?php
/**
 * BuddyX\Buddyx\Post_Thumbnails\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Post_Thumbnails;

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function add_theme_support;
use function add_image_size;
use function apply_filters;

/**
 * Class for managing post thumbnail support.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'post_thumbnails';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_post_thumbnail_support' ] );
		add_action( 'after_setup_theme', [ $this, 'action_add_image_sizes' ] );
	}

	/**
	 * Adds support for post thumbnails.
	 */
	public function action_add_post_thumbnail_support() {
		/**
		 * Filter whether to add support for post thumbnails.
		 *
		 * @since 1.0.0
		 * @param bool $enable_post_thumbnails Whether to enable post thumbnails. Default true.
		 */
		$enable_post_thumbnails = apply_filters( 'buddyx_enable_post_thumbnails', true );

		if ( $enable_post_thumbnails ) {
			add_theme_support( 'post-thumbnails' );
		}
	}

	/**
	 * Adds custom image sizes.
	 */
	public function action_add_image_sizes() {
		/**
		 * Filter the custom image sizes to add.
		 *
		 * @since 1.0.0
		 * @param array $image_sizes Array of custom image sizes. Each size is an associative array with 'name', 'width', 'height', and 'crop' keys.
		 */
		$image_sizes = apply_filters( 'buddyx_custom_image_sizes', [
			[ 'name' => 'buddyx-featured', 'width' => 900, 'height' => 515, 'crop' => true ],
			[ 'name' => 'buddyx-thumbnail', 'width' => 150, 'height' => 150, 'crop' => true ],
			[ 'name' => 'buddyx-medium', 'width' => 300, 'height' => 300, 'crop' => true ],
			[ 'name' => 'buddyx-large', 'width' => 1024, 'height' => 508, 'crop' => true ],
			[ 'name' => 'buddyx-list', 'width' => 300, 'height' => 250, 'crop' => true ],
			[ 'name' => 'buddyx-col-two', 'width' => 450, 'height' => 300, 'crop' => true ],
			[ 'name' => 'buddyx-col-three', 'width' => 350, 'height' => 250, 'crop' => true ],
		] );

		foreach ( $image_sizes as $size ) {
			if ( isset( $size['name'], $size['width'], $size['height'], $size['crop'] ) ) {
				add_image_size( $size['name'], $size['width'], $size['height'], $size['crop'] );
			}
		}
	}
}
