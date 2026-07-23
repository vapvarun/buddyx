<?php
/**
 * BuddyX\Buddyx\Customizer_Settings\Component class
 *
 * Registers Customizer panels, sections, and fields via the in-house
 * Customizer_Framework (replaces Kirki dependency in 5.1.0).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Settings;

use function add_action;
use function add_filter;
use function apply_filters;
use BuddyX\Buddyx\Component_Interface;

/**
 * Class for registering Customizer settings.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'customizer_settings';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		// Customizer_Framework runs unconditionally (replaces Kirki).
		add_action( 'init', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'init', array( $this, 'add_fields' ) );
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
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
		// `buddyx_is_truthy()` correctly handles pre-5.1.0 'on'/'off' string
		// values; bare `if ( $sticky_header )` evaluates the literal 'off'
		// string as truthy and incorrectly applies the body class.
		if ( buddyx_is_truthy( get_theme_mod( 'site_sticky_header', buddyx_defaults( 'site-sticky-header' ) ) ) ) {
			$classes[] = 'sticky-header';
		}

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ): array {
		// `buddyx_is_truthy()` correctly handles pre-5.1.0 'on'/'off' strings.
		if ( buddyx_is_truthy( get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) ) ) ) {
			$classes[] = 'sticky-sidebar-enable';
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
		// `buddyx_is_truthy()` correctly handles pre-5.1.0 'on'/'off' strings.
		if ( buddyx_is_truthy( get_theme_mod( 'buddypress_avatar_style', buddyx_defaults( 'buddypress-avatar-style' ) ) ) ) {
			$classes[] = 'round-avatars';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections() {
		// Site Layout.
		\BuddyX\Buddyx\Customizer_Framework\Panel::add(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Page Mapping.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'page_mapping',
			array(
				'title'       => esc_html__( 'Page Mapping', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		\BuddyX\Buddyx\Customizer_Framework\Panel::add(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sub Header.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Skin.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Blog Layout.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// WP Login.
		\BuddyX\Buddyx\Customizer_Framework\Panel::add(
			'site_wp_login',
			array(
				'title'       => esc_html__( 'WP Login', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_wp_login_logo',
			array(
				'title'       => esc_html__( 'Logo', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_wp_login',
			)
		);

		// BuddyPress Option. The panel, section and fields share ONE gate:
		// BuddyPress active AND Youzify absent (Youzify replaces the avatar
		// styling this section controls). Registering the section without
		// its parent panel makes WordPress silently drop it, orphaning
		// buddypress_avatar_style with nowhere to render.
		if ( class_exists( 'BuddyPress' ) && ! class_exists( 'Youzify' ) ) {
			\BuddyX\Buddyx\Customizer_Framework\Panel::add(
				'site_buddypress_panel',
				array(
					'title'       => esc_html__( 'Community Settings', 'buddyx' ),
					'priority'    => 31,
					'description' => '',
				)
			);

			\BuddyX\Buddyx\Customizer_Framework\Section::add(
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
		\BuddyX\Buddyx\Customizer_Framework\Panel::add(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Performance.
		\BuddyX\Buddyx\Customizer_Framework\Section::add(
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

		// BuddyPress fields - same gate as the Community panel/section above
		// (BuddyPress active AND Youzify absent) so the avatar toggle never
		// registers against a section WordPress dropped.
		if ( class_exists( 'BuddyPress' ) && ! class_exists( 'Youzify' ) ) {
			require_once $fields_dir . 'BuddyPress_Fields.php';
		}
	}
}