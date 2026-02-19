<?php
/**
 * BuddyX\Buddyx\Typography_Options\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Kirki_Option;

use function add_action;
use function add_filter;
use BuddyX\Buddyx\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		if ( class_exists( 'Kirki' ) ) {
			add_action( 'init', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'init', array( $this, 'add_fields' ) );
		}
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_single_blog_post_body_classes' ) );
		if ( class_exists( 'SFWD_LMS' ) ) {
			add_filter( 'body_class', array( $this, 'site_learndash_body_classes' ) );
		}
		if ( class_exists( 'BuddyPress' ) ) {
			add_filter( 'body_class', array( $this, 'site_buddypress_body_classes' ) );
		}
	}

	/**
	 * Site layout body class.
	 */
	public function site_width_body_classes( array $classes ): array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', buddyx_defaults( 'site-layout' ) );

		return $classes;
	}

	/**
	 * Site sticky header body class.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function site_sticky_header_classes( array $classes ): array {
		$sticky_header = get_theme_mod( 'site_sticky_header', buddyx_defaults( 'site-sticky-header' ) );
		if ( $sticky_header ) {
			$classes[] = 'sticky-header';
		}

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ): array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site single blog post body class.
	 */
	public function site_single_blog_post_body_classes( array $classes ): array {

		$single_post_layout = get_theme_mod( 'single_post_layout', buddyx_defaults( 'single-post-layout' ) );

		if ( $single_post_layout === '2' ) {
			$classes[] = 'single-post-layout-1';
		} elseif ( $single_post_layout === '3' ) {
			$classes[] = 'single-post-layout-2';
		} elseif ( $single_post_layout === '4' ) {
			$classes[] = 'single-post-layout-3';
		} else {
			$classes[] = '';
		}

		return $classes;
	}

	/**
	 * LearnDash dark mode body class.
	 */
	public function site_learndash_body_classes( array $classes ): array {
		if ( isset( $_COOKIE['bxtheme'] ) && 'dark' === $_COOKIE['bxtheme'] && is_user_logged_in() ) {
			$classes[] = 'buddyx-dark-theme';
		}

		return $classes;
	}

	/**
	 * BuddyPress body class.
	 */
	public function site_buddypress_body_classes( array $classes ): array {
		$buddypress_avatar_style = get_theme_mod( 'buddypress_avatar_style', buddyx_defaults( 'buddypress-avatar-style' ) );
		if ( $buddypress_avatar_style ) {
			$classes[] = 'round-avatars';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections() {
		// Site Layout.
		new \Kirki\Panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		new \Kirki\Section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Page Mapping.
		new \Kirki\Section(
			'page_mapping',
			array(
				'title'       => esc_html__( 'Page Mapping', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		new \Kirki\Panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		new \Kirki\Section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sub Header.
		new \Kirki\Section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Skin.
		new \Kirki\Section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Blog Layout.
		new \Kirki\Section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		new \Kirki\Section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// WP Login.
		new \Kirki\Panel(
			'site_wp_login',
			array(
				'title'       => esc_html__( 'WP Login', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_wp_login_logo',
			array(
				'title'       => esc_html__( 'Logo', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_wp_login',
			)
		);

		// BuddyPress Option.
		if ( class_exists( 'BuddyPress' ) ) {
			if ( ! class_exists( 'Youzify' ) ) {
				new \Kirki\Panel(
					'site_buddypress_panel',
					array(
						'title'       => esc_html__( 'Community Settings', 'buddyx' ),
						'priority'    => 31,
						'description' => '',
					)
				);
			}

			new \Kirki\Section(
				'site_buddypress_general_section',
				array(
					'title'       => esc_html__( 'General Setting', 'buddyx' ),
					'priority'    => 30,
					'description' => '',
					'panel'       => 'site_buddypress_panel',
				)
			);
		}

		// Site Footer.
		new \Kirki\Panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		new \Kirki\Section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Performance.
		new \Kirki\Section(
			'site_performance_section',
			array(
				'title'       => esc_html__( 'Site Performance', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);
	}

	/**
	 * Add Fields
	 */
	public function add_fields() {
		$fields_dir = __DIR__ . '/Fields/';

		// Core theme fields (always loaded).
		require_once $fields_dir . 'General_Fields.php';
		require_once $fields_dir . 'Typography_Fields.php';
		require_once $fields_dir . 'Header_Fields.php';
		require_once $fields_dir . 'Sub_Header_Fields.php';
		require_once $fields_dir . 'Skin_Fields.php';
		require_once $fields_dir . 'Blog_Fields.php';
		require_once $fields_dir . 'Sidebar_Fields.php';
		require_once $fields_dir . 'Footer_Fields.php';
		require_once $fields_dir . 'WP_Login_Fields.php';
		require_once $fields_dir . 'Site_Performance.php';

		// BuddyPress fields - only load when BuddyPress is active.
		if ( class_exists( 'BuddyPress' ) ) {
			require_once $fields_dir . 'BuddyPress_Fields.php';
		}
	}
}