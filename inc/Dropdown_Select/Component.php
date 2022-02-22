<?php
/**
 * BuddyX\Buddyx\Dropdown_Select\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Dropdown_Select;

use BuddyX\Buddyx\Component_Interface;
use Kirki\Field\Select;

/**
 * Class to override kirki drop down pages field
 */
class Component extends Select implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'dropdown_select';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( 'buddyx_login_page' === $args['settings'] || 'buddyx_registration_page' === $args['settings'] || 'buddyx_404_page' === $args['settings'] ) {

			$args = parent::filter_control_args( $args, $wp_customize );

			$all_pages          = get_pages();
			$args['choices'][0] = __( '-- Select a Page --', 'buddyx' );

			foreach ( $all_pages as $page ) {
				$args['choices'][ $page->ID ] = $page->post_title;
			}
		}

		return $args;
	}

}
