<?php
/**
 * Blog Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Blog Layout
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'blog_layout_option',
				'label'    => esc_html__( 'Blog Layout', 'buddyx' ),
				'section'  => 'site_blog_section',
				'priority' => 10,
				'default'  => 'default-layout',
				'choices'  => array(
					'default-layout' => get_template_directory_uri() . '/assets/images/default-layout.png',
					'list-layout'    => get_template_directory_uri() . '/assets/images/list-layout.png',
					'grid-layout'    => get_template_directory_uri() . '/assets/images/grid-layout.png',
					'masonry-layout' => get_template_directory_uri() . '/assets/images/masonry-layout.png',
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio',
			array(
				'settings'        => 'blog_image_position',
				'label'           => esc_html__( 'Image position', 'buddyx' ),
				'section'         => 'site_blog_section',
				'priority'        => 10,
				'default'         => 'thumb-left',
				'choices'         => array(
					'thumb-left'  => esc_html__( 'Left', 'buddyx' ),
					'thumb-right' => esc_html__( 'Right', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_layout_option',
						'operator' => '==',
						'value'    => 'list-layout',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio',
			array(
				'settings'        => 'blog_grid_columns',
				'label'           => esc_html__( 'Grid Columns', 'buddyx' ),
				'section'         => 'site_blog_section',
				'priority'        => 10,
				'default'         => 'one-column',
				'choices'         => array(
					'one-column' => esc_html__( 'One', 'buddyx' ),
					'two-column' => esc_html__( 'Two', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_layout_option',
						'operator' => '==',
						'value'    => 'grid-layout',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio',
			array(
				'settings'        => 'blog_masonry_view',
				'label'           => esc_html__( 'View', 'buddyx' ),
				'section'         => 'site_blog_section',
				'priority'        => 10,
				'default'         => 'without-masonry',
				'choices'         => array(
					'without-masonry' => esc_html__( 'Without Masonry', 'buddyx' ),
					'with-masonry'    => esc_html__( 'With Masonry', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_layout_option',
						'operator' => '==',
						'value'    => 'masonry-layout',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'select',
			array(
				'settings'        => 'post_per_row',
				'label'           => esc_html__( 'Post Per Row', 'buddyx' ),
				'section'         => 'site_blog_section',
				'default'         => 'buddyx-masonry-2',
				'priority'        => 10,
				'choices'         => array(
					'buddyx-masonry-2' => esc_html__( 'Two', 'buddyx' ),
					'buddyx-masonry-3' => esc_html__( 'Three', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_layout_option',
						'operator' => '==',
						'value'    => 'masonry-layout',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings' => 'blog_show_tags',
				'label'    => esc_html__( 'Show Tags', 'buddyx' ),
				'section'  => 'site_blog_section',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
				'tooltip'  => esc_html__( 'Display tags on blog layouts.', 'buddyx' ),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio',
			array(
				'settings'        => 'blog_show_tags_style',
				'label'           => esc_html__( 'Tags Style', 'buddyx' ),
				'section'         => 'site_blog_section',
				'priority'        => 10,
				'default'         => 'default',
				'choices'         => array(
					'default'   => esc_html__( 'Default', 'buddyx' ),
					'badge'     => esc_html__( 'Badge', 'buddyx' ),
					'underline' => esc_html__( 'Underline', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'blog_show_tags',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings' => 'blog_edit_link',
				'label'    => esc_html__( 'Show Edit Link', 'buddyx' ),
				'section'  => 'site_blog_section',
				'default'  => '',
				'priority' => 10,
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
				'tooltip'  => esc_html__( 'Please remember that the results will be shown in the frontend.', 'buddyx' ),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
			array(
				'settings' => 'custom-skin-divider1',
				'section'  => 'site_blog_section',
				'default'  => '<hr>',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'single_post_content_width',
				'label'    => esc_html__( 'Single Post Content Width', 'buddyx' ),
				'section'  => 'site_blog_section',
				'priority' => 10,
				'default'  => 'small',
				'choices'  => array(
					'small' => get_template_directory_uri() . '/assets/images/small.png',
					'large' => get_template_directory_uri() . '/assets/images/large.png',
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'single_post_title_layout',
				'label'    => esc_html__( 'Single Post Title Layout', 'buddyx' ),
				'section'  => 'site_blog_section',
				'priority' => 10,
				'default'  => 'buddyx-section-title-above',
				'choices'  => array(
					'buddyx-section-title-over'  => get_template_directory_uri() . '/assets/images/single-blog-layout-1.png',
					'buddyx-section-half'        => get_template_directory_uri() . '/assets/images/single-blog-layout-2.png',
					'buddyx-section-title-above' => get_template_directory_uri() . '/assets/images/single-blog-layout-3.png',
					'buddyx-section-title-below' => get_template_directory_uri() . '/assets/images/single-blog-layout-4.png',
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
			array(
				'settings'        => 'buddyx_section_title_over_overlay',
				'label'           => esc_attr__( 'Image Overlay Color', 'buddyx' ),
				'description'     => esc_attr__( 'Allow to add image overlay color on single post title layout one.', 'buddyx' ),
				'section'         => 'site_blog_section',
				'default'         => 'rgba(0, 0, 0, 0.1)',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'output'          => array(
					array(
						'function' => 'css',
						'element'  => '.buddyx-section-title-over.has-featured-image.has-featured-image .post-thumbnail:after',
						'property' => 'background',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'single_post_title_layout',
						'operator' => '==',
						'value'    => 'buddyx-section-title-over',
					),
				),
			)
		);