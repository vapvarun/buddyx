<?php
/**
 * Site Performance Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Performance
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'switch',
			array(
				'settings'    => 'site_load_google_font_locally',
				'label'       => esc_html__( 'Load Google Fonts Locally', 'buddyx' ),
				'description' => esc_html__( 'Saves the Google Fonts you picked on your own site and serves them from there instead of from Google.', 'buddyx' ),
				'section'     => 'site_performance_section',
				'default'     => '',
				'choices'     => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'switch',
			array(
				'settings'        => 'site_preload_local_font',
				'label'           => esc_html__( 'Preload Local Fonts', 'buddyx' ),
				'description'     => esc_html__( 'Asks the browser to fetch the font files early, so text shows in the right font sooner.', 'buddyx' ),
				'section'         => 'site_performance_section',
				'default'         => '',
				'choices'         => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_load_google_font_locally',
						'operator' => '==',
						'value'    => 1,
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'custom',
			array(
				'settings'        => 'site_flush_local_font',
				'label'           => esc_html__( 'Local Font Files', 'buddyx' ),
				'description'     => esc_html__( 'Click Flush Local Font Files to delete the font copies saved on your site so they download again. Use it after changing fonts if text still shows the old font.', 'buddyx' ),
				'section'         => 'site_performance_section',
				'default'         => '<input type="submit" value="Flush Local Font Files" class="button button-secondary buddyx-flush-font-files">',
				'active_callback' => array(
					array(
						'setting'  => 'site_load_google_font_locally',
						'operator' => '==',
						'value'    => 1,
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'switch',
			array(
				'settings'    => 'site_load_fontawesome',
				'label'       => esc_html__( 'Load Font Awesome', 'buddyx' ),
				'description' => esc_html__( 'The theme draws its own icons without Font Awesome. Keep this on if a plugin on your site prints Font Awesome icons (many BuddyPress add-ons do); turn it off to save the download when none does.', 'buddyx' ),
				'section'     => 'site_performance_section',
				'default'     => 'on',
				'choices'     => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);
