<?php
/**
 * BuddyX\Buddyx\Accessibility\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Accessibility;

use BuddyX\Buddyx\Component_Interface;
use WP_Post;

use function BuddyX\Buddyx\buddyx;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for improving accessibility among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string component slug
	 */
	public function get_slug(): string {
		return 'accessibility';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_navigation_script' ) );
		add_action( 'wp_head', array( $this, 'action_wp_head_amp_script' ) );
		add_filter( 'nav_menu_link_attributes', array( $this, 'filter_nav_menu_link_attributes_aria_current' ), 10, 2 );
		add_filter( 'page_menu_link_attributes', array( $this, 'filter_nav_menu_link_attributes_aria_current' ), 10, 2 );
	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_navigation_script() {
		// If the AMP plugin is active, return early.
		if ( buddyx()->is_amp() ) {
			return;
		}

		// Enqueue the navigation script.
		wp_enqueue_script(
			'buddyx-navigation',
			get_theme_file_uri( '/assets/js/navigation.min.js' ),
			array(),
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/navigation.min.js' ) ),
			true
		);
		wp_script_add_data( 'buddyx-navigation', 'async', true );
		wp_script_add_data( 'buddyx-navigation', 'precache', true );
		wp_localize_script(
			'buddyx-navigation',
			'buddyxScreenReaderText',
			array(
				'expand'    => esc_attr__( 'Expand child menu', 'buddyx' ),
				'collapse'  => esc_attr__( 'Collapse child menu', 'buddyx' ),
				'more'      => esc_html__( 'More', 'buddyx' ),
				'moreLabel' => esc_attr__( 'More menu items', 'buddyx' ),
			)
		);
	}

	/**
	 * AMP accessibility.
	 */
	public function action_wp_head_amp_script() {
		if ( ! buddyx()->is_amp() ) {
			?>
			<script>document.documentElement.classList.remove( 'no-js' );</script>
			<?php
		}
	}

	/**
	 * Filters the HTML attributes applied to a menu item's anchor element.
	 *
	 * Checks if the menu item is the current menu item and adds the aria "current" attribute.
	 *
	 * @param array    $atts the HTML attributes applied to the menu item's `<a>` element.
	 * @param \WP_Post $item the current menu item.
	 *
	 * @return array Modified HTML attributes
	 */
	public function filter_nav_menu_link_attributes_aria_current( array $atts, $item ): array {
		if ( isset( $item->current ) ) {
			if ( $item->current ) {
				$atts['aria-current'] = 'page';
			}
		} elseif ( ! empty( $item->ID ) ) {
			global $post;

			if ( ! empty( $post->ID ) && (int) $post->ID === (int) $item->ID ) {
				$atts['aria-current'] = 'page';
			}
		}

		return $atts;
	}
}
