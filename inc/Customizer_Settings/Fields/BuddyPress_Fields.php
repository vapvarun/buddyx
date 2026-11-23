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
		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'switch',
			array(
				'settings'    => 'buddypress_avatar_style',
				'label'       => esc_html__( 'Round Avatars', 'buddyx' ),
				'description' => esc_html__( 'Shows member and group profile photos as circles. Turn off for square photos.', 'buddyx' ),
				'section'     => 'site_buddypress_general_section',
				'default'     => 'on',
				'choices'     => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);
