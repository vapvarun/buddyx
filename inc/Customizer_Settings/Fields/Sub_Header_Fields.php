<?php
/**
 * Sub Header Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Sub Header
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings' => 'site_sub_header_bg',
				'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
				'section'  => 'site_sub_header_section',
				'default'  => 'off',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'background',
			array(
				'settings'        => 'sub_header_background_setting',
				'label'           => esc_html__( 'Background Control', 'buddyx' ),
				'section'         => 'site_sub_header_section',
				// No default background-color — when the customer hasn't enabled
				// `site_sub_header_bg`, the sub-header should inherit its color
				// from the token-driven _header.css rule so it adapts to dark mode.
				// A hardcoded rgba default would emit as inline CSS and clash on
				// dark backgrounds (visible flat-gray strip). Customer can still
				// enable + pick their own background color in the customizer.
				'default'         => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'cover',
					'background-attachment' => 'scroll',
				),
				'transport'       => 'auto',
				'sanitize_callback' => function( $value ) {
					if ( isset( $value['background-color'] ) && is_string( $value['background-color'] ) ) {
						$value['background-color'] = preg_replace( '/rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*0\s*\)/', 'rgba($1,$2,$3,0.01)', $value['background-color'] );
					}
					return $value;
				},
				'output'          => array(
					array(
						'element' => '.site-sub-header',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_sub_header_bg',
						'operator' => '==',
						'value'    => '1',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'site_sub_header_typography',
				'label'    => esc_html__( 'Content Typography', 'buddyx' ),
				'section'  => 'site_sub_header_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '',
					'font-size'       => '',
					'line-height'     => '',
					'letter-spacing'  => '',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-decoration' => '',
				),
				'priority' => 10,
				'output'   => array(
					array(
						'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings' => 'site_breadcrumbs',
				'label'    => esc_html__( 'Site Breadcrumbs?', 'buddyx' ),
				'section'  => 'site_sub_header_section',
				'default'  => 'on',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);