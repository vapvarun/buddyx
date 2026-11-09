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
use function esc_html__;
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

	const LEFT_SIDEBAR_SLUG             = 'sidebar-left';
	const RIGHT_SIDEBAR_SLUG            = 'sidebar-right';
	const BUDDYPRESS_LEFT_SIDEBAR_SLUG  = 'buddypress-sidebar-left';
	const BUDDYPRESS_RIGHT_SIDEBAR_SLUG = 'buddypress-sidebar-right';

	const BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG = 'buddypress-members-sidebar-right';
	const BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG  = 'buddypress-groups-sidebar-right';

	const BBPRESS_LEFT_SIDEBAR_SLUG      = 'bbpress-sidebar-left';
	const BBPRESS_RIGHT_SIDEBAR_SLUG     = 'bbpress-sidebar-right';
	const WOOCOMMERCE_LEFT_SIDEBAR_SLUG  = 'woocommerce-sidebar-left';
	const WOOCOMMERCE_RIGHT_SIDEBAR_SLUG = 'woocommerce-sidebar-right';
	const FLUENTCART_LEFT_SIDEBAR_SLUG   = 'fluentcart-sidebar-left';
	const FLUENTCART_RIGHT_SIDEBAR_SLUG  = 'fluentcart-sidebar-right';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'sidebars';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', array( $this, 'action_register_sidebars' ) );
		add_filter( 'body_class', array( $this, 'filter_body_classes' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `buddyx()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return array(
			'is_left_sidebar_active'                     => array( $this, 'is_left_sidebar_active' ),
			'display_left_sidebar'                       => array( $this, 'display_left_sidebar' ),
			'is_right_sidebar_active'                    => array( $this, 'is_right_sidebar_active' ),
			'display_right_sidebar'                      => array( $this, 'display_right_sidebar' ),

			'display_buddypress_left_sidebar'            => array( $this, 'display_buddypress_left_sidebar' ),
			'is_buddypress_left_sidebar_active'          => array( $this, 'is_buddypress_left_sidebar_active' ),
			'display_buddypress_right_sidebar'           => array( $this, 'display_buddypress_right_sidebar' ),
			'is_buddypress_right_sidebar_active'         => array( $this, 'is_buddypress_right_sidebar_active' ),

			'display_buddypress_members_right_sidebar'   => array( $this, 'display_buddypress_members_right_sidebar' ),
			'is_buddypress_members_right_sidebar_active' => array( $this, 'is_buddypress_members_right_sidebar_active' ),

			'display_buddypress_groups_right_sidebar'    => array( $this, 'display_buddypress_groups_right_sidebar' ),
			'is_buddypress_groups_right_sidebar_active'  => array( $this, 'is_buddypress_groups_right_sidebar_active' ),

			'display_bbpress_left_sidebar'               => array( $this, 'display_bbpress_left_sidebar' ),
			'is_bbpress_left_sidebar_active'             => array( $this, 'is_bbpress_left_sidebar_active' ),
			'display_bbpress_right_sidebar'              => array( $this, 'display_bbpress_right_sidebar' ),
			'is_bbpress_right_sidebar_active'            => array( $this, 'is_bbpress_right_sidebar_active' ),

			'display_woocommerce_left_sidebar'           => array( $this, 'display_woocommerce_left_sidebar' ),
			'is_woocommerce_left_sidebar_active'         => array( $this, 'is_woocommerce_left_sidebar_active' ),
			'display_woocommerce_right_sidebar'          => array( $this, 'display_woocommerce_right_sidebar' ),
			'is_woocommerce_right_sidebar_active'        => array( $this, 'is_woocommerce_right_sidebar_active' ),

			'display_fluentcart_left_sidebar'            => array( $this, 'display_fluentcart_left_sidebar' ),
			'is_fluentcart_left_sidebar_active'          => array( $this, 'is_fluentcart_left_sidebar_active' ),
			'display_fluentcart_right_sidebar'           => array( $this, 'display_fluentcart_right_sidebar' ),
			'is_fluentcart_right_sidebar_active'         => array( $this, 'is_fluentcart_right_sidebar_active' ),
		);
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		$areas = array(
			static::RIGHT_SIDEBAR_SLUG => esc_html__( 'Right Sidebar', 'buddyx' ),
			static::LEFT_SIDEBAR_SLUG  => esc_html__( 'Left Sidebar', 'buddyx' ),
		);

		if ( function_exists( 'bp_is_active' ) ) {
			$areas += array(
				static::BUDDYPRESS_LEFT_SIDEBAR_SLUG  => esc_html__( 'Community Left Sidebar', 'buddyx' ),
				static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG => esc_html__( 'Activity Directory Right Sidebar', 'buddyx' ),
				static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG => esc_html__( 'Members Directory Right Sidebar', 'buddyx' ),
				static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG => esc_html__( 'Groups Directory Right Sidebar', 'buddyx' ),
				'single_member'                       => esc_html__( 'Members Single Profile Sidebar', 'buddyx' ),
				'single_member_activity'              => esc_html__( 'Members Single User Activity', 'buddyx' ),
				'single_group'                        => esc_html__( 'Groups Single Group Sidebar', 'buddyx' ),
				'single_group_activity'               => esc_html__( 'Groups Single Group Activity', 'buddyx' ),
			);
		}

		if ( function_exists( 'is_bbpress' ) ) {
			$areas += array(
				static::BBPRESS_LEFT_SIDEBAR_SLUG  => esc_html__( 'bbPress Left Sidebar', 'buddyx' ),
				static::BBPRESS_RIGHT_SIDEBAR_SLUG => esc_html__( 'bbPress Right Sidebar', 'buddyx' ),
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$areas += array(
				static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG  => esc_html__( 'WooCommerce Left Sidebar', 'buddyx' ),
				static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG => esc_html__( 'WooCommerce Right Sidebar', 'buddyx' ),
			);
		}

		if ( defined( 'FLUENTCART_PLUGIN_FILE_PATH' ) ) {
			$areas += array(
				static::FLUENTCART_LEFT_SIDEBAR_SLUG  => esc_html__( 'Single Product Left Sidebar', 'buddyx' ),
				static::FLUENTCART_RIGHT_SIDEBAR_SLUG => esc_html__( 'Single Product Right Sidebar', 'buddyx' ),
			);
		}

		// Footer columns use h3 titles; every other area uses h2.
		$areas += array(
			'footer-1' => esc_html__( 'Footer 1', 'buddyx' ),
			'footer-2' => esc_html__( 'Footer 2', 'buddyx' ),
			'footer-3' => esc_html__( 'Footer 3', 'buddyx' ),
			'footer-4' => esc_html__( 'Footer 4', 'buddyx' ),
		);

		foreach ( $areas as $id => $name ) {
			$heading = 0 === strpos( $id, 'footer-' ) ? 'h3' : 'h2';
			register_sidebar(
				array(
					'name'          => $name,
					'id'            => $id,
					'description'   => esc_html__( 'Add widgets here.', 'buddyx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<' . $heading . ' class="widget-title">',
					'after_title'   => '</' . $heading . '>',
				)
			);
		}
	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ): array {
		// Cache template check once to avoid 30+ repeated global declarations and in_array checks.
		global $template;
		$excluded_templates   = array( 'front-page.php', '404.php', '500.php', 'offline.php' );
		$template_name        = $template ? basename( $template ) : '';
		$is_excluded_template = in_array( $template_name, $excluded_templates, true );

		// Early return if on excluded template - no sidebar classes needed.
		if ( $is_excluded_template ) {
			return $classes;
		}

		// An explicitly assigned sidebar page template takes precedence over the
		// global customizer setting, matching the layout the template itself renders.
		$page_template_sidebar_map = array(
			'page-templates/page-left-sidebar.php'  => 'left',
			'page-templates/page-right-sidebar.php' => 'right',
			'page-templates/page-both-sidebar.php'  => 'both',
		);
		$assigned_page_template    = is_singular() ? get_page_template_slug() : '';
		$default_sidebar           = isset( $page_template_sidebar_map[ $assigned_page_template ] )
			? $page_template_sidebar_map[ $assigned_page_template ]
			: get_theme_mod( 'sidebar_option', buddyx_defaults( 'sidebar-option' ) );

		if ( $this->is_left_sidebar_active() && 'left' === $default_sidebar ) {
			$classes[] = 'has-sidebar-left';
		} elseif ( $this->is_right_sidebar_active() && 'right' === $default_sidebar ) {
			$classes[] = 'has-sidebar-right';
		} elseif ( $this->is_right_sidebar_active() && 'both' === $default_sidebar ) {
			$classes[] = 'has-sidebar-both';
		}

		// BuddyPress.
		if ( class_exists( 'BuddyPress' ) ) {
			if ( bp_current_component() ) {
				$buddypress_sidebar         = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );
				$buddypress_members_sidebar = get_theme_mod( 'buddypress_members_sidebar_option', buddyx_defaults( 'buddypress-members-sidebar-option' ) );
				$buddypress_groups_sidebar  = get_theme_mod( 'buddypress_groups_sidebar_option', buddyx_defaults( 'buddypress-groups-sidebar-option' ) );

				// Cache sidebar active states to avoid repeated method calls.
				$bp_left_active          = $this->is_buddypress_left_sidebar_active();
				$bp_right_active         = $this->is_buddypress_right_sidebar_active();
				$bp_members_right_active = $this->is_buddypress_members_right_sidebar_active();
				$bp_groups_right_active  = $this->is_buddypress_groups_right_sidebar_active();

				// Activity sidebar classes.
				if ( bp_is_current_component( 'activity' ) && ! bp_is_user() ) {
					if ( $bp_left_active && 'left' === $buddypress_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-left';
					} elseif ( $bp_right_active && 'right' === $buddypress_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-right';
					} elseif ( $bp_right_active && 'both' === $buddypress_sidebar && ! $bp_left_active ) {
						$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-left';
					} elseif ( $bp_left_active && 'both' === $buddypress_sidebar && ! $bp_right_active ) {
						$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-right';
					} elseif ( ( $bp_right_active || $bp_left_active ) && 'both' === $buddypress_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-both';
					}
				}

				// Members sidebar classes.
				if ( bp_is_current_component( 'members' ) && ! bp_is_user() ) {
					if ( $bp_left_active && 'left' === $buddypress_members_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-left';
					} elseif ( $bp_members_right_active && 'right' === $buddypress_members_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-right';
					} elseif ( $bp_members_right_active && 'both' === $buddypress_members_sidebar && ! $bp_left_active ) {
						$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-left';
					} elseif ( $bp_left_active && 'both' === $buddypress_members_sidebar && ! $bp_members_right_active ) {
						$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-right';
					} elseif ( ( $bp_members_right_active || $bp_left_active ) && 'both' === $buddypress_members_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-both';
					}
				}

				// Groups sidebar classes.
				if ( bp_is_current_component( 'groups' ) && ! bp_is_group() && ! bp_is_user() ) {
					if ( $bp_left_active && 'left' === $buddypress_groups_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-left';
					} elseif ( $bp_groups_right_active && 'right' === $buddypress_groups_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-right';
					} elseif ( $bp_groups_right_active && 'both' === $buddypress_groups_sidebar && ! $bp_left_active ) {
						$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-left';
					} elseif ( $bp_left_active && 'both' === $buddypress_groups_sidebar && ! $bp_groups_right_active ) {
						$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-right';
					} elseif ( ( $bp_groups_right_active || $bp_left_active ) && 'both' === $buddypress_groups_sidebar ) {
						$classes[] = 'has-buddypress-sidebar-both';
					}
				}
			}
		}

		// Sidebar classes docs component.
		if ( class_exists( 'BuddyPress' ) ) {
			if ( function_exists( 'bp_docs_is_docs_component' ) && bp_docs_is_docs_component() && ! bp_is_user() ) {
				$buddypress_sidebar = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );
				if ( $this->is_buddypress_right_sidebar_active() && ( 'right' === $buddypress_sidebar || 'both' === $buddypress_sidebar ) ) {
					$classes[] = 'has-docs-sidebar-right';
				}
			}
		}

		// bbPress.
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			$bbpress_sidebar = get_theme_mod( 'bbpress_sidebar_option', buddyx_defaults( 'bbpress-sidebar-option' ) );

			if ( $this->is_bbpress_left_sidebar_active() && 'left' === $bbpress_sidebar ) {
				$classes[] = 'has-bbpress-sidebar-left';
			} elseif ( $this->is_bbpress_right_sidebar_active() && 'right' === $bbpress_sidebar ) {
				$classes[] = 'has-bbpress-sidebar-right';
			} elseif ( $this->is_bbpress_right_sidebar_active() && 'both' === $bbpress_sidebar ) {
				$classes[] = 'has-bbpress-sidebar-both';
			}
		}

		// WooCommerce.
		if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
			$woocommerce_sidebar = get_theme_mod( 'woocommerce_sidebar_option', buddyx_defaults( 'woocommerce-sidebar-option' ) );

			if ( $this->is_woocommerce_left_sidebar_active() && 'left' === $woocommerce_sidebar ) {
				$classes[] = 'has-woocommerce-sidebar-left';
			} elseif ( $this->is_woocommerce_right_sidebar_active() && 'right' === $woocommerce_sidebar ) {
				$classes[] = 'has-woocommerce-sidebar-right';
			} elseif ( $this->is_woocommerce_right_sidebar_active() && 'both' === $woocommerce_sidebar ) {
				$classes[] = 'has-woocommerce-sidebar-both';
			}
		}

		// FluentCart.
		if ( defined( 'FLUENTCART_PLUGIN_FILE_PATH' ) ) {
			$fluentcart_sidebar = get_theme_mod( 'fluentcart_product_sidebar', 'none' );

			if ( $this->is_fluentcart_left_sidebar_active() && 'left' === $fluentcart_sidebar ) {
				$classes[] = 'has-fluentcart-sidebar-left';
			} elseif ( $this->is_fluentcart_right_sidebar_active() && 'right' === $fluentcart_sidebar ) {
				$classes[] = 'has-fluentcart-sidebar-right';
			} elseif ( $this->is_fluentcart_right_sidebar_active() && 'both' === $fluentcart_sidebar ) {
				$classes[] = 'has-fluentcart-sidebar-both';
			}
		}

		// Dokan Class.
		if ( class_exists( 'WeDevs_Dokan' ) ) {
			$classes[] = 'buddyx-dokan';
		}

		// Single Member Sidebar.
		if ( function_exists( 'buddypress' ) && buddypress()->buddyboss ) {
			if ( is_active_sidebar( 'single_member' ) && bp_is_user() && ! bp_is_user_settings() && ! bp_is_user_messages() && ! bp_is_user_notifications() && ! bp_is_user_profile_edit() && ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() && ! bp_is_user_front() ) {
				$classes[] = 'has-single-member-sidebar';
			}
		} elseif ( class_exists( 'BuddyPress' ) ) {
			if ( is_active_sidebar( 'single_member' ) && bp_is_user() && ! bp_is_user_settings() && ! bp_is_user_messages() && ! bp_is_user_notifications() && ! bp_is_user_profile_edit() && ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() && ! bp_is_user_front() && function_exists( 'bp_is_members_invitations_screen' ) && ! bp_is_members_invitations_screen() ) {
				$classes[] = 'has-single-member-sidebar';
			}
		}

		if ( class_exists( 'BuddyPress' ) ) {
			$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance' );

			// Single Member Activity Sidebar.
			if ( ( ! isset( $bp_nouveau_appearance['user_nav_display'] ) || ! $bp_nouveau_appearance['user_nav_display'] ) && is_active_sidebar( 'single_member_activity' ) && bp_is_user_activity() ) {
				$classes[] = 'has-single-member-activity-sidebar';
			}

			// Single Group Activity Sidebar.
			if ( ( ! isset( $bp_nouveau_appearance['group_nav_display'] ) || ! $bp_nouveau_appearance['group_nav_display'] ) && is_active_sidebar( 'single_group_activity' ) && bp_is_group_activity() ) {
				$classes[] = 'has-single-group-activity-sidebar';
			}

			// Single Group Sidebar.
			if ( is_active_sidebar( 'single_group' ) && bp_is_group() ) {
				$classes[] = 'has-single-group-sidebar';
			}
		}

		// MediaPress Class.
		if ( class_exists( 'MediaPress' ) ) {
			$classes[] = 'buddyx-mediapress';
		}

		// BPGES Class.
		if ( class_exists( 'BPGES_Subscription' ) ) {
			$classes[] = 'buddyx-bpges';
		}

		return $classes;
	}

	/**
	 * Checks whether the left sidebar is active.
	 *
	 * @return bool True if the left sidebar is active, false otherwise.
	 */
	public function is_left_sidebar_active(): bool {
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
	public function is_right_sidebar_active(): bool {
		return (bool) is_active_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the right sidebar.
	 */
	public function display_right_sidebar() {
		dynamic_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Whether a BuddyPress directory sidebar has anything to show: widgets the owner added,
	 * or the built-in defaults (turn those off with the `buddyx_buddypress_sidebar_default_widgets` filter).
	 *
	 * @param string $slug Sidebar id.
	 * @return bool
	 */
	private function bp_directory_sidebar_has_content( string $slug ): bool {
		return is_active_sidebar( $slug ) || (bool) apply_filters( 'buddyx_buddypress_sidebar_default_widgets', true, $slug );
	}

	/**
	 * Prints a BuddyPress directory sidebar: the owner's widgets, else sensible default widgets.
	 *
	 * @param string $slug Sidebar id.
	 */
	private function display_bp_directory_sidebar( string $slug ) {
		if ( is_active_sidebar( $slug ) ) {
			dynamic_sidebar( $slug );
			return;
		}
		if ( ! apply_filters( 'buddyx_buddypress_sidebar_default_widgets', true, $slug ) ) {
			return;
		}

		// BuddyPress 14 ships these as blocks, not classic widgets.
		$blocks = array(
			static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG         => array(
				array(
					'bp/dynamic-members',
					array(
						'title'         => __( 'Members', 'buddyx' ),
						'maxMembers'    => 5,
						'memberDefault' => 'active',
					),
				),
				array(
					'bp/dynamic-groups',
					array(
						'title'        => __( 'Groups', 'buddyx' ),
						'maxGroups'    => 5,
						'groupDefault' => 'active',
					),
				),
			),
			static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG => array(
				array(
					'bp/online-members',
					array(
						'title'      => __( 'Who\'s Online', 'buddyx' ),
						'maxMembers' => 15,
					),
				),
				array(
					'bp/active-members',
					array(
						'title'      => __( 'Recently Active Members', 'buddyx' ),
						'maxMembers' => 15,
					),
				),
			),
			static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG  => array(
				array(
					'bp/dynamic-groups',
					array(
						'title'        => __( 'Popular Groups', 'buddyx' ),
						'maxGroups'    => 5,
						'groupDefault' => 'popular',
					),
				),
				array(
					'bp/dynamic-groups',
					array(
						'title'        => __( 'Newest Groups', 'buddyx' ),
						'maxGroups'    => 5,
						'groupDefault' => 'newest',
					),
				),
			),
		);

		foreach ( $blocks[ $slug ] ?? array() as list( $name, $attrs ) ) {
			if ( \WP_Block_Type_Registry::get_instance()->is_registered( $name ) ) {
				echo render_block( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block output is escaped by the block.
					array(
						'blockName'    => $name,
						'attrs'        => $attrs,
						'innerBlocks'  => array(),
						'innerHTML'    => '',
						'innerContent' => array(),
					)
				);
			}
		}
	}

	/**
	 * Checks whether the buddypress left sidebar is active.
	 *
	 * @return bool True if the buddypress left sidebar is active, false otherwise.
	 */
	public function is_buddypress_left_sidebar_active(): bool {
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
	public function is_buddypress_right_sidebar_active(): bool {
		return $this->bp_directory_sidebar_has_content( static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress right sidebar.
	 */
	public function display_buddypress_right_sidebar() {
		$this->display_bp_directory_sidebar( static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress members right sidebar is active.
	 *
	 * @return bool True if the buddypress members right sidebar is active, false otherwise.
	 */
	public function is_buddypress_members_right_sidebar_active(): bool {
		return $this->bp_directory_sidebar_has_content( static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress members right sidebar.
	 */
	public function display_buddypress_members_right_sidebar() {
		$this->display_bp_directory_sidebar( static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress groups right sidebar is active.
	 *
	 * @return bool True if the buddypress groups right sidebar is active, false otherwise.
	 */
	public function is_buddypress_groups_right_sidebar_active(): bool {
		return $this->bp_directory_sidebar_has_content( static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress groups right sidebar.
	 */
	public function display_buddypress_groups_right_sidebar() {
		$this->display_bp_directory_sidebar( static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress left sidebar is active.
	 *
	 * @return bool True if the bbpress left sidebar is active, false otherwise.
	 */
	public function is_bbpress_left_sidebar_active(): bool {
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
	public function is_bbpress_right_sidebar_active(): bool {
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
	public function is_woocommerce_left_sidebar_active(): bool {
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
	public function is_woocommerce_right_sidebar_active(): bool {
		return (bool) is_active_sidebar( static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the woocommerce right sidebar.
	 */
	public function display_woocommerce_right_sidebar() {
		dynamic_sidebar( static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the FluentCart left sidebar is active.
	 *
	 * @return bool True if the FluentCart left sidebar is active, false otherwise.
	 */
	public function is_fluentcart_left_sidebar_active(): bool {
		return (bool) is_active_sidebar( static::FLUENTCART_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the FluentCart left sidebar.
	 */
	public function display_fluentcart_left_sidebar() {
		dynamic_sidebar( static::FLUENTCART_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the FluentCart right sidebar is active.
	 *
	 * @return bool True if the FluentCart right sidebar is active, false otherwise.
	 */
	public function is_fluentcart_right_sidebar_active(): bool {
		return (bool) is_active_sidebar( static::FLUENTCART_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the fluentcart right sidebar.
	 */
	public function display_fluentcart_right_sidebar() {
		dynamic_sidebar( static::FLUENTCART_RIGHT_SIDEBAR_SLUG );
	}
}
