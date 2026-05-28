<?php
/**
 * Sidebar Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Sidebar Layout
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'sidebar_option',
				'label'    => esc_html__( 'Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'single_post_sidebar_option',
				'label'    => esc_html__( 'Single Post Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'none',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			)
		);

		if ( function_exists( 'bp_is_active' ) ) {
			if ( ! class_exists( 'Youzify' ) ) {
				\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
					array(
						'settings' => 'buddypress_sidebar_option',
						'label'    => esc_html__( 'Activity Directory Sidebar Layout', 'buddyx' ),
						'section'  => 'site_sidebar_layout',
						'priority' => 10,
						'default'  => 'both',
						'choices'  => array(
							'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
							'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
							'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
							'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
						),
					)
				);

				\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
					array(
						'settings' => 'buddypress_members_sidebar_option',
						'label'    => esc_html__( 'Members Directory Sidebar Layout', 'buddyx' ),
						'section'  => 'site_sidebar_layout',
						'priority' => 10,
						'default'  => 'right',
						'choices'  => array(
							'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
							'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
							'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
							'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
						),
					)
				);

				\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
					array(
						'settings' => 'buddypress_groups_sidebar_option',
						'label'    => esc_html__( 'Groups Directory Sidebar Layout', 'buddyx' ),
						'section'  => 'site_sidebar_layout',
						'priority' => 10,
						'default'  => 'right',
						'choices'  => array(
							'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
							'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
							'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
							'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
						),
					)
				);
			}
		}

		if ( function_exists( 'is_bbpress' ) ) {
			\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
				array(
					'settings' => 'bbpress_sidebar_option',
					'label'    => esc_html__( 'bbPress Sidebar Layout', 'buddyx' ),
					'section'  => 'site_sidebar_layout',
					'priority' => 10,
					'default'  => 'right',
					'choices'  => array(
						'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
						'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
						'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
						'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
					),
				)
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
				array(
					'settings' => 'woocommerce_sidebar_option',
					'label'    => esc_html__( 'WooCommerce Sidebar Layout', 'buddyx' ),
					'section'  => 'site_sidebar_layout',
					'priority' => 10,
					'default'  => 'right',
					'choices'  => array(
						'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
						'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
						'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
						'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
					),
				)
			);
		}

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings' => 'sticky_sidebar_option',
				'label'    => esc_html__( 'Sticky Sidebar ?', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);