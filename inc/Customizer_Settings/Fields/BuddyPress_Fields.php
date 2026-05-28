<?php
/**
 * BuddyPress Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  BuddyPress General Settings
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings'    => 'buddypress_avatar_style',
				'label'       => esc_html__( 'Avatar Style', 'buddyx' ),
				'description' => esc_html__( 'Set the round style for member and group avatars.', 'buddyx' ),
				'section'     => 'site_buddypress_general_section',
				'default'     => 'on',
				'choices'     => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);