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
	public function get_slug(): string {
		return 'post_thumbnails';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_post_thumbnail_support' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_image_sizes' ) );
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

		// Add 'buddyx-featured' size dynamically.
		/**
		 * Filters the single-post featured image width.
		 *
		 * @param int $width Width.
		 */
		$featured_width = apply_filters( 'buddyx_featured_width', 900 );
		/**
		 * Filters the single-post featured image height.
		 *
		 * @param int $height Height.
		 */
		$featured_height = apply_filters( 'buddyx_featured_height', 515 );
		/**
		 * Filters the single-post featured image crop setting.
		 *
		 * @param bool $crop Crop.
		 */
		$featured_crop = apply_filters( 'buddyx_featured_crop', true );
		add_image_size( 'buddyx-featured', $featured_width, $featured_height, $featured_crop );

		add_image_size(
			'buddyx-large',
			/**
			 * Filters the large card image width.
			 *
			 * @param int $width Width.
			 */
			apply_filters( 'buddyx_large_width', 1024 ),
			/**
			 * Filters the large card image height.
			 *
			 * @param int $height Height.
			 */
			apply_filters( 'buddyx_large_height', 508 ),
			/**
			 * Filters the large card image crop setting.
			 *
			 * @param bool $crop Crop.
			 */
			apply_filters( 'buddyx_large_crop', false )
		);

		add_image_size(
			'buddyx-list',
			/**
			 * Filters the list layout image width.
			 *
			 * @param int $width Width.
			 */
			apply_filters( 'buddyx_list_width', 300 ),
			/**
			 * Filters the list layout image height.
			 *
			 * @param int $height Height.
			 */
			apply_filters( 'buddyx_list_height', 250 ),
			/**
			 * Filters the list layout image crop setting.
			 *
			 * @param bool $crop Crop.
			 */
			apply_filters( 'buddyx_list_crop', true )
		);

		add_image_size(
			'buddyx-col-two',
			/**
			 * Filters the 2-column grid image width.
			 *
			 * @param int $width Width.
			 */
			apply_filters( 'buddyx_col_two_width', 450 ),
			/**
			 * Filters the 2-column grid image height.
			 *
			 * @param int $height Height.
			 */
			apply_filters( 'buddyx_col_two_height', 300 ),
			/**
			 * Filters the 2-column grid image crop setting.
			 *
			 * @param bool $crop Crop.
			 */
			apply_filters( 'buddyx_col_two_crop', true )
		);

		add_image_size(
			'buddyx-col-three',
			/**
			 * Filters the 3-column grid image width.
			 *
			 * @param int $width Width.
			 */
			apply_filters( 'buddyx_col_three_width', 350 ),
			/**
			 * Filters the 3-column grid image height.
			 *
			 * @param int $height Height.
			 */
			apply_filters( 'buddyx_col_three_height', 250 ),
			/**
			 * Filters the 3-column grid image crop setting.
			 *
			 * @param bool $crop Crop.
			 */
			apply_filters( 'buddyx_col_three_crop', true )
		);
	}
}
