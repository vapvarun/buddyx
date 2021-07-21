<?php
/**
 * BuddyX\Buddyx\Custom_Js\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Custom_Js;

use BuddyX\Buddyx\Component_Interface;
use function BuddyX\Buddyx\buddyx;
use function add_action;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;

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

		// Enqueue the isotope script.
		wp_enqueue_script(
			'buddyx-isotope-pkgd',
			get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ),
			['jquery'],			
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/isotope.pkgd.min.js' ) ),
			true
		);

		// Enqueue the fitVids script.
		wp_enqueue_script(
			'buddyx-fitvids',
			get_theme_file_uri( '/assets/js/fitvids.min.js' ),
			['jquery'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/fitvids.min.js' ) ),
			true
		);

		// Enqueue the sticky kit script.
		wp_enqueue_script(
			'buddyx-sticky-kit',
			get_theme_file_uri( '/assets/js/sticky-kit.min.js' ),
			['jquery'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/sticky-kit.min.js' ) ),
			true
		);
                
                // Enqueue the jquery cookie script.
                wp_enqueue_script(
                        'buddyx-jquery-cookie',
                        get_theme_file_uri('/assets/js/jquery-cookie.min.js'),
                        ['jquery'],
                        buddyx()->get_asset_version(get_theme_file_path('/assets/js/jquery-cookie.min.js')),
                        true
                );
                
                // Enqueue the slick script.
                wp_enqueue_script(
                    'buddyx-slick',
                    get_theme_file_uri('/assets/js/slick.min.js'),
                    ['jquery'],
                    buddyx()->get_asset_version(get_theme_file_path('/assets/js/slick.min.js')),
                    true
                );

		// Enqueue the custom script.
		wp_enqueue_script(
			'buddyx-custom',
			get_theme_file_uri( '/assets/js/custom.min.js' ),
			['jquery'],
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/custom.min.js' ) ),
			true
		);
		
	}
}
