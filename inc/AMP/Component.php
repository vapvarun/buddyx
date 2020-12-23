<?php
/**
 * BuddyX\Buddyx\AMP\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\AMP;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Templating_Component_Interface;
use function add_action;
use function add_filter;
use function add_theme_support;
use function get_theme_support;

/**
 * Class for managing AMP support.
 *
 * Exposes template tags:
 * * `buddyx()->is_amp()`
 * * `buddyx()->using_amp_live_list_comments()`
 *
 * @link https://wordpress.org/plugins/amp/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'amp';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_amp_support' ] );
		add_filter( 'body_class', [ $this, 'buddyx_amp' ] );
		add_filter( 'buddyx_search_slide_toggle_data_attrs', array( $this, 'add_search_slide_toggle_attrs' ) );
		add_filter( 'buddyx_search_field_toggle_data_attrs', array( $this, 'add_search_field_toggle_attrs' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `buddyx()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return [
			'is_amp'                       => [ $this, 'is_amp' ],
			'using_amp_live_list_comments' => [ $this, 'using_amp_live_list_comments' ],
		];
	}

	/**
	 * Adds theme support for AMP.
	 *
	 * From here you can control how the plugin, when activated, impacts the the theme.
	 */
	public function action_add_amp_support() {
		add_theme_support(
			'amp',
			[
				'comments_live_list' => true,
			]
		);
	}

	/**
	 * Adds custom classes to indicate to activate AMP.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function buddyx_amp( array $classes ) : array {
		if ( $this->is_amp() ) {
			$classes[] = 'buddyx-amp';
		}

		return $classes;
	}

	/**
	 * Add search slide data attributes.
	 *
	 * @param string $input the data attrs already existing in the nav.
	 *
	 * @return string
	 */
	public function add_search_slide_toggle_attrs( $input ) {
		$input .= ' on="tap:AMP.setState( { buddyxAmpSlideSearchMenuExpanded: ! buddyxAmpSlideSearchMenuExpanded } )" ';
		$input .= ' [class]="( buddyxAmpSlideSearchMenuExpanded ? \'buddyx-search-menu-icon search buddyx-dropdown-active\' : \'buddyx-search-menu-icon search\' )" ';
		$input .= ' aria-expanded="false" [aria-expanded]="buddyxAmpSlideSearchMenuExpanded ? \'true\' : \'false\'" ';

		return $input;
	}

	/**
	 * Add search slide data attributes.
	 *
	 * @param string $input the data attrs already existing in the nav.
	 *
	 * @return string
	 */
	public function add_search_field_toggle_attrs( $input ) {
		$input .= ' on="tap:AMP.setState( { buddyxAmpSlideSearchMenuExpanded: buddyxAmpSlideSearchMenuExpanded } )" ';

		return $input;
	}

	/**
	 * Determines whether this is an AMP response.
	 *
	 * Note that this must only be called after the parse_query action.
	 *
	 * @return bool Whether the AMP plugin is active and the current request is for an AMP endpoint.
	 */
	public function is_amp() : bool {
		return function_exists( 'is_amp_endpoint' ) && \is_amp_endpoint();
	}

	/**
	 * Determines whether amp-live-list should be used for the comment list.
	 *
	 * @return bool Whether to use amp-live-list.
	 */
	public function using_amp_live_list_comments() : bool {
		if ( ! $this->is_amp() ) {
			return false;
		}

		$amp_theme_support = get_theme_support( 'amp' );

		return ! empty( $amp_theme_support[0]['comments_live_list'] );
	}
	
}
