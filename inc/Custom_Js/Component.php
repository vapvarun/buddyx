<?php
/**
 * BuddyX\Buddyx\Custom_Js\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Custom_Js;

use BuddyX\Buddyx\Component_Interface;
use function BuddyX\Buddyx\buddyx;
use WP_Post;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for improving custom_js among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_js';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_custom_js_script' ] );
	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_custom_js_script() {

		// If the AMP plugin is active, return early.
		if ( buddyx()->is_amp() ) {
			return;
		}

		// Enqueue the superfish script.
		wp_enqueue_script(
			'buddyx-superfish',
			get_theme_file_uri( '/assets/js/superfish.min.js' ),
			['jquery'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/superfish.min.js' ) ),
			true
		);
		wp_script_add_data( 'buddyx-superfish', 'async', true );
		wp_script_add_data( 'buddyx-superfish', 'precache', true );

		// Enqueue the isotope script.
		wp_enqueue_script(
			'buddyx-isotope-pkgd',
			get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ),
			['jquery'],			
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/isotope.pkgd.min.js' ) ),
			true
		);
		wp_script_add_data( 'buddyx-isotope-pkgd', 'async', true );
		wp_script_add_data( 'buddyx-isotope-pkgd', 'precache', true );

		// Enqueue the fitVids script.
		wp_enqueue_script(
			'buddyx-fitvids',
			get_theme_file_uri( '/assets/js/fitvids.min.js' ),
			['jquery'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/fitvids.min.js' ) ),
			true
		);
		wp_script_add_data( 'buddyx-fitvids', 'async', true );
		wp_script_add_data( 'buddyx-fitvids', 'precache', true );

		// Enqueue the sticky kit script.
		wp_enqueue_script(
			'buddyx-sticky-kit',
			get_theme_file_uri( '/assets/js/sticky-kit.min.js' ),
			['jquery'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/sticky-kit.min.js' ) ),
			true
		);
		wp_script_add_data( 'buddyx-sticky-kit', 'async', true );
		wp_script_add_data( 'buddyx-sticky-kit', 'precache', true );
                
                // Enqueue the jquery cookie script.
                wp_enqueue_script(
                        'buddyx-jquery-cookie',
                        get_theme_file_uri('/assets/js/jquery-cookie.min.js'),
                        [],
                        buddyx()->get_asset_version(get_theme_file_path('/assets/js/jquery-cookie.min.js')),
                        true
                );
                wp_script_add_data('buddyx-jquery-cookie', 'async', true);
                wp_script_add_data('buddyx-jquery-cookie', 'precache', true);

		// Enqueue the custom script.
		wp_enqueue_script(
			'buddyx-custom',
			get_theme_file_uri( '/assets/js/custom.min.js' ),
			['jquery', 'buddyx-superfish', 'buddyx-isotope-pkgd', 'buddyx-fitvids', 'buddyx-sticky-kit', 'buddyx-jquery-cookie'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/custom.min.js' ) ),
			true
		);
		wp_script_add_data( 'buddyx-custom', 'async', true );
		wp_script_add_data( 'buddyx-custom', 'precache', true );
		
	}
}
