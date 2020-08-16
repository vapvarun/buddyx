<?php
/**
 * BuddyX\Buddyx\Sidebars\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Sidebars;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Templating_Component_Interface;
use function add_action;
use function add_filter;
use function register_sidebar;
//use function esc_html__;
use function is_active_sidebar;
use function dynamic_sidebar;

/**
 * Class for managing sidebars.
 *
 * Exposes template tags:
 * * `buddyx()->is_right_sidebar_active()`
 * * `buddyx()->display_primary_sidebar()`
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const LEFT_SIDEBAR_SLUG  = 'sidebar-left';
	const RIGHT_SIDEBAR_SLUG = 'sidebar-right';
	const BUDDYPRESS_LEFT_SIDEBAR_SLUG = 'buddypress-sidebar-left';
	const BUDDYPRESS_RIGHT_SIDEBAR_SLUG = 'buddypress-sidebar-right';
	const BBPRESS_LEFT_SIDEBAR_SLUG = 'bbpress-sidebar-left';
	const BBPRESS_RIGHT_SIDEBAR_SLUG = 'bbpress-sidebar-right';
	const WOOCOMMERCE_LEFT_SIDEBAR_SLUG  = 'woocommerce-sidebar-left';
	const WOOCOMMERCE_RIGHT_SIDEBAR_SLUG = 'woocommerce-sidebar-right';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'sidebars';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', [ $this, 'action_register_sidebars' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
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
			'is_left_sidebar_active'  => [ $this, 'is_left_sidebar_active' ],
			'display_left_sidebar'    => [ $this, 'display_left_sidebar' ],
			'is_right_sidebar_active' => [ $this, 'is_right_sidebar_active' ],
			'display_right_sidebar'   => [ $this, 'display_right_sidebar' ],
			
			'display_buddypress_left_sidebar'    => [ $this, 'display_buddypress_left_sidebar' ],
			'is_buddypress_left_sidebar_active'  => [ $this, 'is_buddypress_left_sidebar_active' ],
			'display_buddypress_right_sidebar'    => [ $this, 'display_buddypress_right_sidebar' ],
			'is_buddypress_right_sidebar_active'  => [ $this, 'is_buddypress_right_sidebar_active' ],

			'display_bbpress_left_sidebar'    => [ $this, 'display_bbpress_left_sidebar' ],
			'is_bbpress_left_sidebar_active'  => [ $this, 'is_bbpress_left_sidebar_active' ],
			'display_bbpress_right_sidebar'    => [ $this, 'display_bbpress_right_sidebar' ],
			'is_bbpress_right_sidebar_active'  => [ $this, 'is_bbpress_right_sidebar_active' ],

			'display_woocommerce_left_sidebar'    => [ $this, 'display_woocommerce_left_sidebar' ],
			'is_woocommerce_left_sidebar_active'  => [ $this, 'is_woocommerce_left_sidebar_active' ],
			'display_woocommerce_right_sidebar'    => [ $this, 'display_woocommerce_right_sidebar' ],
			'is_woocommerce_right_sidebar_active'  => [ $this, 'is_woocommerce_right_sidebar_active' ],
		];
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Right Sidebar', 'buddyx' ),
				'id'            => static::RIGHT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Left Sidebar', 'buddyx' ),
				'id'            => static::LEFT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);

		if ( function_exists('bp_is_active') ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'BuddyPress Left Sidebar', 'buddyx' ),
					'id'            => static::BUDDYPRESS_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'BuddyPress Right Sidebar', 'buddyx' ),
					'id'            => static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
		}

		if ( function_exists('is_bbpress') ) {
    		register_sidebar(
				[
					'name'          => esc_html__( 'bbPress Left Sidebar', 'buddyx' ),
					'id'            => static::BBPRESS_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'bbPress Right Sidebar', 'buddyx' ),
					'id'            => static::BBPRESS_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
        }

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Left Sidebar', 'buddyx' ),
					'id'            => static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Right Sidebar', 'buddyx' ),
					'id'            => static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
		}

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 1', 'buddyx' ),
				'id'            => 'footer-1',
				'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 2', 'buddyx' ),
				'id'            => 'footer-2',
				'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 3', 'buddyx' ),
				'id'            => 'footer-3',
				'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 4', 'buddyx' ),
				'id'            => 'footer-4',
				'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ) : array {
		$default_sidebar = get_theme_mod( 'sidebar_option', buddyx_defaults( 'sidebar-option' ) );

		if ( $this->is_left_sidebar_active() && $default_sidebar == 'left' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-left';
			}
		} elseif ( $this->is_right_sidebar_active() && $default_sidebar == 'right' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-right';
			}
		} elseif ( $this->is_right_sidebar_active() && $this->is_right_sidebar_active() && $default_sidebar == 'both' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-both';
			}
		}

		//Buddypress
		if ( class_exists( 'BuddyPress' ) ) {
			if ( bp_current_component() ) {
				$buddypress_sidebar = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );

				if ( $this->is_buddypress_left_sidebar_active() && $buddypress_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-buddypress-sidebar-left';
					}
				} elseif ( $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-buddypress-sidebar-right';
					}
				} elseif ( $this->is_buddypress_right_sidebar_active() && $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-buddypress-sidebar-both';
					}
				}
			}
		}

		//bbPress
		if ( function_exists('is_bbpress') ) {
			$bbpress_sidebar = get_theme_mod( 'bbpress_sidebar_option', buddyx_defaults( 'bbpress-sidebar-option' ) );

			if ( $this->is_bbpress_left_sidebar_active() && $bbpress_sidebar == 'left' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-bbpress-sidebar-left';
				}
			} elseif ( $this->is_bbpress_right_sidebar_active() && $bbpress_sidebar == 'right' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-bbpress-sidebar-right';
				}
			} elseif ( $this->is_bbpress_right_sidebar_active() && $this->is_bbpress_right_sidebar_active() && $bbpress_sidebar == 'both' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-bbpress-sidebar-both';
				}
			}
		}

		//WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_woocommerce() ) {
				$woocommerce_sidebar = get_theme_mod( 'woocommerce_sidebar_option', buddyx_defaults( 'woocommerce-sidebar-option' ) );
				
				if ( $this->is_woocommerce_left_sidebar_active() && $woocommerce_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-woocommerce-sidebar-left';
					}
				} elseif ( $this->is_woocommerce_right_sidebar_active() && $woocommerce_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-woocommerce-sidebar-right';
					}
				} elseif ( $this->is_woocommerce_right_sidebar_active() && $this->is_woocommerce_right_sidebar_active() && $woocommerce_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-woocommerce-sidebar-both';
					}
				}
			}
		}

		return $classes;
	}

	/**
	 * Checks whether the left sidebar is active.
	 *
	 * @return bool True if the left sidebar is active, false otherwise.
	 */
	public function is_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the left sidebar.
	 */
	public function display_left_sidebar() {
		dynamic_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the right sidebar is active.
	 *
	 * @return bool True if the right sidebar is active, false otherwise.
	 */
	public function is_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the right sidebar.
	 */
	public function display_right_sidebar() {
		dynamic_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress left sidebar is active.
	 *
	 * @return bool True if the buddypress left sidebar is active, false otherwise.
	 */
	public function is_buddypress_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BUDDYPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress left sidebar.
	 */
	public function display_buddypress_left_sidebar() {
		dynamic_sidebar( static::BUDDYPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress right sidebar is active.
	 *
	 * @return bool True if the buddypress right sidebar is active, false otherwise.
	 */
	public function is_buddypress_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress right sidebar.
	 */
	public function display_buddypress_right_sidebar() {
		dynamic_sidebar( static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress left sidebar is active.
	 *
	 * @return bool True if the bbpress left sidebar is active, false otherwise.
	 */
	public function is_bbpress_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BBPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the bbpress left sidebar.
	 */
	public function display_bbpress_left_sidebar() {
		dynamic_sidebar( static::BBPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress right sidebar is active.
	 *
	 * @return bool True if the buddypress right sidebar is active, false otherwise.
	 */
	public function is_bbpress_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BBPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the bbpress right sidebar.
	 */
	public function display_bbpress_right_sidebar() {
		dynamic_sidebar( static::BBPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the woocommerce left sidebar is active.
	 *
	 * @return bool True if the woocommerce left sidebar is active, false otherwise.
	 */
	public function is_woocommerce_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the woocommerce left sidebar.
	 */
	public function display_woocommerce_left_sidebar() {
		dynamic_sidebar( static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the woocommerce right sidebar is active.
	 *
	 * @return bool True if the woocommerce right sidebar is active, false otherwise.
	 */
	public function is_woocommerce_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the woocommerce right sidebar.
	 */
	public function display_woocommerce_right_sidebar() {
		dynamic_sidebar( static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG );
	}
}