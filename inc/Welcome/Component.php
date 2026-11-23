<?php
/**
 * BuddyX\Buddyx\Welcome\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Welcome;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function remove_all_actions;


/**
 * Class Component
 *
 * Appearance > Getting Started: five tabs (Start, Step by step, Our products,
 * Free vs Pro, Help and links). Start holds the next step, the goal cards, the
 * Customizer tiles and a collapsed help block. Step by step is a checklist
 * that ticks itself from the site's own settings. BuddyX is on WordPress.org,
 * so nothing here installs a plugin: every action is a link the owner chooses.
 *
 * @package BuddyX\Buddyx\Welcome
 */
class Component implements Component_Interface {

	/**
	 * Every external address the screen links to, kept in one place.
	 *
	 * @var array<string, string>
	 */
	private const URLS = array(
		'docs'           => 'https://buddyxtheme.com/docs/',
		'films'          => 'https://www.youtube.com/playlist?list=PLB3Tp-smkUiA',
		'film_community' => 'https://youtu.be/Q1MPFOxc388',
		'film_live'      => 'https://youtu.be/WijuvjG6WiE',
		'support'        => 'https://wbcomdesigns.com/support/',
		'contact'        => 'https://wbcomdesigns.com/contact/',
		'wbcom'          => 'https://wbcomdesigns.com/',
		'downloads'      => 'https://wbcomdesigns.com/downloads/',
		'release_notes'  => 'https://wbcomdesigns.com/release-notes/',
		'pro'            => 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/',
		'compare'        => 'https://buddyxtheme.com/free-vs-pro/',
		'group'          => 'https://www.facebook.com/groups/191523257634994',
		'review'         => 'https://wordpress.org/support/theme/buddyx/reviews/?rate=5#new-post',
		'child_theme'    => 'https://github.com/wbcomdesigns/buddyx-child/releases/download/v1.0.0/buddyx-child.zip',
		'essential'      => 'https://github.com/vapvarun/wbcom-essential/releases/latest',
		'demo_installer' => 'https://github.com/buddynext/wbcom-demo-installer/releases/latest',
	);

	/**
	 * The setup films in the BuddyX Starter playlist: id => title. Order is the
	 * order shown; the first one is the film for this theme.
	 *
	 * @var array<string, string>
	 */
	private const FILMS = array(
		'Q1MPFOxc388' => 'Set up a complete community site in 5 minutes',
		'WijuvjG6WiE' => 'Take your demo site live and keep your setup',
		'vxM1m22RxJ8' => 'Build an online community with forums and media',
		'csWX1FwJ3XY' => 'Build an online course site with quizzes',
		'IQkm4nDu4IM' => 'Build an events site with RSVPs',
		'I_lc8uBm8Q8' => 'Build a job board site',
		'6vXdtmoZ-uc' => 'Build a business directory with reviews',
		'WmfIJOV8Tgc' => 'Build a freelance services marketplace',
		'jLANmm3uAGI' => 'Build an all-in-one community site on Reign',
		'W2rs9rpKEYk' => 'Build an all-in-one community site on BuddyX Pro',
	);

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'welcome';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
	}

	/**
	 *  Add welcome page at admin
	 */
	public function add_admin_menu_page() {
		$hook = add_submenu_page(
			'themes.php',
			__( 'Getting Started', 'buddyx' ),
			__( 'Getting Started', 'buddyx' ),
			'edit_theme_options',
			'buddyx-welcome',
			array( &$this, 'submenu_page_callback' )
		);

		if ( $hook ) {
			add_action( 'load-' . $hook, array( $this, 'hide_admin_notices' ) );
		}
	}

	/**
	 * Other plugins' admin notices stay off this screen so the first run reads clean.
	 */
	public function hide_admin_notices() {
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
		remove_all_actions( 'user_admin_notices' );
	}

	/**
	 * One external address from URLS.
	 *
	 * @param string $key  Key of URLS.
	 * @param string $path Optional path appended to the base (docs pages).
	 * @return string
	 */
	private function url( $key, $path = '' ) {
		return self::URLS[ $key ] . $path;
	}

	/**
	 * A Customizer address that comes back to this screen.
	 *
	 * @param string $focus Query part, for example 'autofocus[section]=site_layout', or ''.
	 * @return string
	 */
	private function customize_url( $focus ) {
		return admin_url( 'customize.php?' . ( $focus ? $focus . '&' : '' ) . 'return=' . rawurlencode( admin_url( 'themes.php?page=buddyx-welcome' ) ) );
	}

	/**
	 * Resolve a family product's status for the UI.
	 *
	 * DETECTION ONLY. BuddyX ships on WordPress.org, so it holds no plugin
	 * catalog, no EDD item ids and no distribution licence keys, and it cannot
	 * install anything from a third-party server. All this does is notice
	 * whether the plugin is already running.
	 *
	 * @param array $product Product row from family_plugins().
	 * @return string 'active' | 'not_installed'.
	 */
	private function family_status( $product ) {
		$slug = isset( $product['slug'] ) ? $product['slug'] : '';

		$detect = array(
			'buddynext'        => static function () {
				return defined( 'BUDDYNEXT_VERSION' );
			},
			'eventonomy'       => static function () {
				return defined( 'EVENTONOMY_VERSION' );
			},
			'jetonomy'         => static function () {
				return class_exists( '\\Jetonomy\\Plugin' ) || function_exists( 'jetonomy' );
			},
			'learnomy'         => static function () {
				return defined( 'LEARNOMY_VERSION' ) || function_exists( 'learnomy' );
			},
			'wb-gamification'  => static function () {
				return defined( 'WB_GAM_VERSION' ) || function_exists( 'wb_gam_submit_event' );
			},
			'wpmediaverse'     => static function () {
				return defined( 'MVS_VERSION' ) || class_exists( '\\WPMediaVerse\\Core\\Plugin' );
			},
			'wp-career-board'  => static function () {
				return defined( 'WCB_VERSION' ) || class_exists( '\\WCB\\Core\\Plugin' );
			},
			'wb-listora'       => static function () {
				return defined( 'WB_LISTORA_VERSION' );
			},
			'wp-sell-services' => static function () {
				return defined( 'WPSS_VERSION' );
			},
		);

		if ( isset( $detect[ $slug ] ) && call_user_func( $detect[ $slug ] ) ) {
			return 'active';
		}

		return 'not_installed';
	}

	/**
	 * Where a plugin stands on this site, read live from the plugins folder.
	 *
	 * Read-only: BuddyX is on WordPress.org, so it never downloads or installs
	 * a plugin. The owner installs each one; this only reports the result.
	 *
	 * @param string $slug Plugin folder name, for example 'wbcom-essential'.
	 * @return array{state:string,file:string} state is 'active', 'installed' or 'missing'.
	 */
	private function plugin_state( $slug ) {
		static $plugins = null;

		if ( null === $plugins ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugins = get_plugins();
		}

		foreach ( array_keys( $plugins ) as $file ) {
			if ( str_starts_with( $file, $slug . '/' ) ) {
				return array(
					'state' => is_plugin_active( $file ) ? 'active' : 'installed',
					'file'  => $file,
				);
			}
		}

		return array(
			'state' => 'missing',
			'file'  => '',
		);
	}

	/**
	 * The Wbcom plugins in the family, ours first on every tab.
	 *
	 * 'open' is the plugin's own admin screen, used once it is active.
	 *
	 * @return array<string, array<string, string>> Keyed by plugin folder.
	 */
	private function family_plugins() {
		return array(
			'buddynext'        => array(
				'slug' => 'buddynext',
				'name' => __( 'BuddyNext', 'buddyx' ),
				'desc' => __( 'Community engine: profiles, activity feeds, Spaces and messaging.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'buddynext/' ),
				'open' => admin_url( 'admin.php?page=buddynext' ),
			),
			'jetonomy'         => array(
				'slug' => 'jetonomy',
				'name' => __( 'Jetonomy', 'buddyx' ),
				'desc' => __( 'Forums, threaded Q&A, an ideas roadmap and polls.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'jetonomy/' ),
				'open' => '',
			),
			'wb-gamification'  => array(
				'slug' => 'wb-gamification',
				'name' => __( 'WB Gamification', 'buddyx' ),
				'desc' => __( 'Points, badges, levels and leaderboards for any activity.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'wordpress-gamification-plugin/' ),
				'open' => '',
			),
			'wpmediaverse'     => array(
				'slug' => 'wpmediaverse',
				'name' => __( 'MediaVerse', 'buddyx' ),
				'desc' => __( 'Media albums and galleries for your members.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'mediaverse/' ),
				'open' => '',
			),
			'learnomy'         => array(
				'slug' => 'learnomy',
				'name' => __( 'Learnomy', 'buddyx' ),
				'desc' => __( 'A full LMS: courses, lessons, quizzes, certificates and paid enrollments.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'learnomy/' ),
				'open' => admin_url( 'admin.php?page=learnomy' ),
			),
			'eventonomy'       => array(
				'slug' => 'eventonomy',
				'name' => __( 'Eventonomy', 'buddyx' ),
				'desc' => __( 'Events with RSVPs, paid ticketing, venues and QR check-in.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'eventonomy/' ),
				'open' => '',
			),
			'wp-career-board'  => array(
				'slug' => 'wp-career-board',
				'name' => __( 'WP Career Board', 'buddyx' ),
				'desc' => __( 'A job board: listings, company profiles, resumes and applications.', 'buddyx' ),
				'href' => 'https://wpcareerboard.com/',
				'open' => '',
			),
			'wp-sell-services' => array(
				'slug' => 'wp-sell-services',
				'name' => __( 'WP Sell Services', 'buddyx' ),
				'desc' => __( 'A freelance services marketplace: storefronts, orders and payouts.', 'buddyx' ),
				'href' => 'https://wpsellservices.com/',
				'open' => admin_url( 'admin.php?page=wp-sell-services' ),
			),
			'wb-listora'       => array(
				'slug' => 'wb-listora',
				'name' => __( 'Listora', 'buddyx' ),
				'desc' => __( 'A directory: member listings, claims, reviews and monetization.', 'buddyx' ),
				'href' => $this->url( 'downloads', 'listora/' ),
				'open' => '',
			),
		);
	}

	/**
	 * The free Wbcom helpers that are not on WordPress.org.
	 *
	 * @return array<string, array<string, string>> Keyed by plugin folder.
	 */
	private function helper_plugins() {
		return array(
			'wbcom-essential'      => array(
				'slug' => 'wbcom-essential',
				'name' => __( 'Wbcom Essential', 'buddyx' ),
				'desc' => __( 'Ready-made blocks and patterns that use your BuddyX colors.', 'buddyx' ),
				'href' => $this->url( 'essential' ),
				'open' => admin_url( 'post-new.php?post_type=page' ),
			),
			'wbcom-demo-installer' => array(
				'slug' => 'wbcom-demo-installer',
				'name' => __( 'Wbcom Demo Installer', 'buddyx' ),
				'desc' => __( 'Builds a full demo site with sample members in one click.', 'buddyx' ),
				'href' => $this->url( 'demo_installer' ),
				'open' => admin_url( 'themes.php?page=wbcom-demo' ),
			),
		);
	}

	/**
	 * The next action for one plugin, from its live state.
	 *
	 * Missing: the plugin's page in a new tab, where the owner downloads it,
	 * plus the core Upload Plugin screen. Installed: the core activate link
	 * with its nonce. Active: the plugin's own screen. A user who may not
	 * install or activate gets a note instead of a link.
	 *
	 * @param array  $plugin     Row from family_plugins() or helper_plugins().
	 * @param string $open_label Link text once the plugin is active.
	 * @return array{state:string,link:?array{url:string,label:string,new_tab:bool},upload:string,note:string}
	 */
	private function plugin_action( $plugin, $open_label = '' ) {
		$status = $this->plugin_state( $plugin['slug'] );
		$state  = 'active' === $this->family_status( $plugin ) ? 'active' : $status['state'];
		$admin  = is_multisite() ? __( 'your network administrator', 'buddyx' ) : __( 'a site administrator', 'buddyx' );
		$action = array(
			'state'  => $state,
			'link'   => null,
			'upload' => '',
			'note'   => '',
		);

		if ( 'active' === $state && $plugin['open'] ) {
			$action['link'] = array(
				'url'     => $plugin['open'],
				/* translators: %s: plugin name. */
				'label'   => $open_label ? $open_label : sprintf( __( 'Open %s', 'buddyx' ), $plugin['name'] ),
				'new_tab' => false,
			);
		} elseif ( 'active' === $state ) {
			$action['link'] = array(
				'url'     => $plugin['href'],
				'label'   => __( 'View plugin', 'buddyx' ),
				'new_tab' => true,
			);
		} elseif ( 'installed' === $state ) {
			if ( current_user_can( 'activate_plugin', $status['file'] ) ) {
				$action['link'] = array(
					'url'     => wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $status['file'] ) ), 'activate-plugin_' . $status['file'] ),
					/* translators: %s: plugin name. */
					'label'   => sprintf( __( 'Activate %s', 'buddyx' ), $plugin['name'] ),
					'new_tab' => false,
				);
			} else {
				/* translators: %s: who can do it, for example "a site administrator". */
				$action['note'] = sprintf( __( 'Ask %s to activate it.', 'buddyx' ), $admin );
			}
		} elseif ( current_user_can( 'install_plugins' ) ) {
			$action['link']   = array(
				'url'     => $plugin['href'],
				/* translators: %s: plugin name. */
				'label'   => sprintf( __( 'Install %s', 'buddyx' ), $plugin['name'] ),
				'new_tab' => true,
			);
			$action['upload'] = network_admin_url( 'plugin-install.php?tab=upload' );
		} else {
			/* translators: %s: who can do it, for example "a site administrator". */
			$action['note'] = sprintf( __( 'Ask %s to install it.', 'buddyx' ), $admin );
		}

		return $action;
	}

	/**
	 * Where the demo site stands: the Demo Installer's state, then its build.
	 *
	 * @return string 'missing' | 'installed' | 'idle' | 'building' | 'built' | 'finished'.
	 */
	private function demo_stage() {
		$state = $this->plugin_state( 'wbcom-demo-installer' )['state'];
		if ( 'active' !== $state ) {
			return $state;
		}
		$status = function_exists( 'wbcom_demo_status' ) ? (array) wbcom_demo_status() : array();
		$stage  = isset( $status['status'] ) ? (string) $status['status'] : 'idle';
		$map    = array(
			'building'  => 'building',
			'built'     => 'built',
			'finishing' => 'built',
			'finished'  => 'finished',
		);

		return isset( $map[ $stage ] ) ? $map[ $stage ] : 'idle';
	}

	/**
	 * The BuddyX vs BuddyX Pro feature table: area => list of
	 * [feature, what Free has]. 'yes' = in both, 'no' = Pro only,
	 * 'limited' = Free has a basic version, Pro the full control.
	 *
	 * @return array<string, array<int, array{0:string,1:string}>>
	 */
	private function features() {
		return array(
			__( 'Layout', 'buddyx' )       => array(
				array( __( 'Site Layout', 'buddyx' ), 'yes' ),
				array( __( 'Content Layout Width', 'buddyx' ), 'yes' ),
				array( __( 'Sidebar Width', 'buddyx' ), 'no' ),
				array( __( 'Global Border Radius', 'buddyx' ), 'yes' ),
				array( __( 'Buttons Border Radius', 'buddyx' ), 'yes' ),
				array( __( 'Form Border Radius', 'buddyx' ), 'yes' ),
				array( __( 'Page Mapping', 'buddyx' ), 'yes' ),
				array( __( 'Sign-in Popup and Register Form Fields', 'buddyx' ), 'no' ),
				array( __( 'Scroll Top', 'buddyx' ), 'no' ),
				array( __( 'Site Loader', 'buddyx' ), 'yes' ),
				array( __( 'Loading Text', 'buddyx' ), 'yes' ),
			),
			__( 'Typography', 'buddyx' )   => array(
				array( __( 'Site Title Settings', 'buddyx' ), 'yes' ),
				array( __( 'Headings', 'buddyx' ), 'yes' ),
				array( __( 'Menu', 'buddyx' ), 'yes' ),
				array( __( 'Body', 'buddyx' ), 'yes' ),
				array( __( 'Google Fonts', 'buddyx' ), 'yes' ),
				array( __( 'Font Size (PX/EM)', 'buddyx' ), 'yes' ),
				array( __( 'Text Transform', 'buddyx' ), 'yes' ),
				array( __( 'Line Height', 'buddyx' ), 'yes' ),
				array( __( 'Letter Spacing', 'buddyx' ), 'yes' ),
			),
			__( 'Header', 'buddyx' )       => array(
				array( __( 'Top Bar', 'buddyx' ), 'no' ),
				array( __( 'Sticky Header', 'buddyx' ), 'yes' ),
				array( __( 'Header Layout', 'buddyx' ), 'no' ),
				array( __( 'Header Menu Position', 'buddyx' ), 'no' ),
				array( __( 'Menu Effects', 'buddyx' ), 'no' ),
				array( __( 'More Menu', 'buddyx' ), 'no' ),
				array( __( 'Site Search Style', 'buddyx' ), 'no' ),
				array( __( 'Login/Register Button Style', 'buddyx' ), 'no' ),
				array( __( 'Background Color', 'buddyx' ), 'yes' ),
				array( __( 'Site Search', 'buddyx' ), 'yes' ),
				array( __( 'Site Cart', 'buddyx' ), 'yes' ),
				array( __( 'BuddyPress Components', 'buddyx' ), 'no' ),
			),
			__( 'Side Panel', 'buddyx' )   => array(
				array( __( 'Side Panel', 'buddyx' ), 'no' ),
				array( __( 'Side Panel Colors', 'buddyx' ), 'no' ),
			),
			__( 'Sub Header', 'buddyx' )   => array(
				array( __( 'Customize Background', 'buddyx' ), 'yes' ),
				array( __( 'Content Typography', 'buddyx' ), 'yes' ),
				array( __( 'Breadcrumbs', 'buddyx' ), 'yes' ),
			),
			__( 'Skin Colors', 'buddyx' )  => array(
				array( __( 'Dark Mode', 'buddyx' ), 'yes' ),
				array( __( 'Custom Colors', 'buddyx' ), 'yes' ),
				array( __( 'Body Background Color', 'buddyx' ), 'yes' ),
				array( __( 'Theme Color', 'buddyx' ), 'yes' ),
				array( __( 'Link Color', 'buddyx' ), 'yes' ),
				array( __( 'Primary Header', 'buddyx' ), 'yes' ),
				array( __( 'Button Background Color / Hover', 'buddyx' ), 'yes' ),
				array( __( 'Button Text Color / Hover', 'buddyx' ), 'yes' ),
				array( __( 'Button Border Color / Hover', 'buddyx' ), 'yes' ),
			),
			__( 'Site Blog', 'buddyx' )    => array(
				array( __( 'List Layout', 'buddyx' ), 'yes' ),
				array( __( 'Grid Layout', 'buddyx' ), 'yes' ),
				array( __( 'Masonry Layout', 'buddyx' ), 'yes' ),
				array( __( 'Blog Layout Style', 'buddyx' ), 'no' ),
				array( __( 'Post Per Row', 'buddyx' ), 'yes' ),
			),
			__( 'Site Sidebar', 'buddyx' ) => array(
				array( __( 'No Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'Left Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'Right Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'Both Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'BuddyPress Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'WooCommerce Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'bbPress Sidebar', 'buddyx' ), 'yes' ),
				array( __( 'LearnDash Sidebar', 'buddyx' ), 'no' ),
				array( __( 'Sticky Sidebar', 'buddyx' ), 'yes' ),
			),
			__( 'WP Login', 'buddyx' )     => array(
				array( __( 'Customize Your Logo Section', 'buddyx' ), 'yes' ),
				array( __( 'Choose WP Login Theme', 'buddyx' ), 'no' ),
				array( __( 'Customize Login Form', 'buddyx' ), 'no' ),
				array( __( 'Customize Forgot Form', 'buddyx' ), 'no' ),
				array( __( 'Customize Button', 'buddyx' ), 'no' ),
			),
			__( 'Site Footer', 'buddyx' )  => array(
				array( __( 'Footer Section', 'buddyx' ), 'yes' ),
				array( __( 'Copyright Section', 'buddyx' ), 'yes' ),
			),
			__( 'BuddyPress', 'buddyx' )   => array(
				array( __( 'Activity Control', 'buddyx' ), 'limited' ),
				array( __( 'Activity Share', 'buddyx' ), 'no' ),
				array( __( 'Activity Reaction', 'buddyx' ), 'no' ),
				array( __( 'Activity Load More', 'buddyx' ), 'limited' ),
				array( __( 'Members Multiple Directory Layout', 'buddyx' ), 'limited' ),
				array( __( 'Members Default Cover Background', 'buddyx' ), 'limited' ),
				array( __( 'Groups Multiple Directory View', 'buddyx' ), 'limited' ),
				array( __( 'Group Default Cover Background', 'buddyx' ), 'limited' ),
				array( __( 'Member Multiple Header Layout', 'buddyx' ), 'limited' ),
				array( __( 'Member Primary Navigation Layout', 'buddyx' ), 'limited' ),
				array( __( 'Group Multiple Header Layout', 'buddyx' ), 'limited' ),
				array( __( 'Group Primary Navigation Style', 'buddyx' ), 'limited' ),
			),
			__( 'WooCommerce', 'buddyx' )  => array(
				array( __( 'Product Listing Style', 'buddyx' ), 'no' ),
				array( __( 'Shop Products Per Page', 'buddyx' ), 'no' ),
				array( __( 'Display Filter Button', 'buddyx' ), 'no' ),
				array( __( 'Sale Badge Style', 'buddyx' ), 'no' ),
			),
			__( 'Integrations', 'buddyx' ) => array(
				array( __( 'BuddyPress', 'buddyx' ), 'yes' ),
				array( __( 'BuddyBoss Platform', 'buddyx' ), 'yes' ),
				array( __( 'WooCommerce', 'buddyx' ), 'limited' ),
				array( __( 'LearnDash', 'buddyx' ), 'limited' ),
				array( __( 'LearnPress', 'buddyx' ), 'limited' ),
				array( __( 'LifterLMS', 'buddyx' ), 'limited' ),
				array( __( 'Tutor LMS', 'buddyx' ), 'limited' ),
				array( __( 'bbPress', 'buddyx' ), 'limited' ),
				array( __( 'Dokan', 'buddyx' ), 'limited' ),
				array( __( 'WC Vendors', 'buddyx' ), 'limited' ),
				array( __( 'GamiPress', 'buddyx' ), 'yes' ),
				array( __( 'Paid Memberships', 'buddyx' ), 'yes' ),
				array( __( 'The Event Calendar', 'buddyx' ), 'yes' ),
			),
		);
	}

	/**
	 * Print one link. New-tab links say so to screen readers.
	 *
	 * @param string $url     Address.
	 * @param string $label   Visible text.
	 * @param bool   $new_tab Open in a new tab.
	 * @param string $context Hidden text that names the row, for screen readers.
	 * @param string $css     Optional class.
	 * @return void
	 */
	private function link( $url, $label, $new_tab = true, $context = '', $css = '' ) {
		printf(
			'<a%1$s href="%2$s"%3$s>%4$s%5$s%6$s</a>',
			$css ? ' class="' . esc_attr( $css ) . '"' : '',
			esc_url( $url ),
			$new_tab ? ' target="_blank" rel="noopener"' : '',
			esc_html( $label ),
			$context ? '<span class="screen-reader-text">: ' . esc_html( $context ) . '</span>' : '',
			$new_tab ? '<span class="screen-reader-text"> ' . esc_html__( '(opens in a new tab)', 'buddyx' ) . '</span>' : ''
		);
	}

	/**
	 * A link that opens another tab of this screen, then moves focus there.
	 *
	 * @param string $tab    Tab key, for example 'guide'.
	 * @param string $target Element id to focus inside that tab.
	 * @param string $label  Visible text.
	 * @param string $css    Optional class.
	 * @return void
	 */
	private function tab_link( $tab, $target, $label, $css = '' ) {
		printf(
			'<a%1$s href="#%2$s" data-buddyx-tab="%3$s">%4$s</a>',
			$css ? ' class="' . esc_attr( $css ) . '"' : '',
			esc_attr( $target ),
			esc_attr( $tab ),
			esc_html( $label )
		);
	}

	/**
	 * The primary button, the upload link or the note for a plugin_action().
	 *
	 * @param array  $action  Result of plugin_action().
	 * @param string $context Hidden text that names the row, for screen readers.
	 * @param string $css     Class for the main link.
	 * @param bool   $hint    Print the upload hint under a missing plugin.
	 * @return void
	 */
	private function render_action( $action, $context, $css, $hint = true ) {
		if ( $action['link'] ) {
			$this->link( $action['link']['url'], $action['link']['label'], $action['link']['new_tab'], '', $css );
		}
		if ( $action['upload'] && $hint ) {
			echo '<span class="buddyx-gs-hint">';
			esc_html_e( 'Download it, then add it at', 'buddyx' );
			echo ' ';
			$this->link( $action['upload'], __( 'Upload Plugin', 'buddyx' ), false, $context );
			echo '. ';
			esc_html_e( 'Nothing installs until you click.', 'buddyx' );
			echo '</span>';
		}
		if ( $action['note'] ) {
			echo '<span class="buddyx-gs-hint">' . esc_html( $action['note'] ) . '</span>';
		}
	}

	/**
	 * The setup films: one player and the list of every film in the Starter
	 * playlist. Each title is a plain YouTube link, so it works without script;
	 * the script plays it in place after a click and loads nothing before.
	 *
	 * @param string $featured Video id shown in the player.
	 * @return void
	 */
	private function render_films( $featured ) {
		if ( ! isset( self::FILMS[ $featured ] ) ) {
			$featured = (string) key( self::FILMS );
		}
		?>
		<div class="buddyx-gs-films" data-gs-films>
			<div class="buddyx-gs-video" data-gs-player>
				<?php $this->film_face( $featured, 'buddyx-gs-video-play', true ); ?>
			</div>
			<ul class="buddyx-gs-film-list" aria-label="<?php esc_attr_e( 'Setup films', 'buddyx' ); ?>">
				<?php foreach ( self::FILMS as $id => $title ) : ?>
					<li<?php echo $id === $featured ? ' class="is-current"' : ''; ?>>
						<?php $this->film_face( $id, 'buddyx-gs-film-link', false ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	/**
	 * One film as a link with its preview image.
	 *
	 * @param string $id        YouTube video id.
	 * @param string $css_class Class attribute.
	 * @param bool   $big       Whether this is the large player face.
	 * @return void
	 */
	private function film_face( $id, $css_class, $big ) {
		?>
		<a class="<?php echo esc_attr( $css_class ); ?>" href="<?php echo esc_url( 'https://youtu.be/' . $id ); ?>" data-video-id="<?php echo esc_attr( $id ); ?>" data-video-title="<?php echo esc_attr( self::FILMS[ $id ] ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="buddyx-gs-film-thumb">
				<img src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $id . '/' . ( $big ? 'hqdefault' : 'mqdefault' ) . '.jpg' ); ?>" alt="" loading="lazy" decoding="async">
				<span class="buddyx-gs-video-icon" aria-hidden="true"></span>
			</span>
			<span class="buddyx-gs-film-title"><?php echo esc_html( self::FILMS[ $id ] ); ?></span>
		</a>
		<?php
	}

	/**
	 * Start tab: the next step, the goal cards, the Customizer tiles and a
	 * collapsed list of help and plugin links.
	 *
	 * @return void
	 */
	private function render_start() {
		$stage   = $this->demo_stage();
		$helpers = $this->helper_plugins();
		$family  = $this->family_plugins();
		$demo    = $this->plugin_action( $helpers['wbcom-demo-installer'] );
		$demo_ui = admin_url( 'themes.php?page=wbcom-demo' );

		// Three stages; each is 'done', 'current' or ''.
		$progress = array(
			'build' => in_array( $stage, array( 'built', 'finished' ), true ) ? 'done' : 'current',
			'make'  => in_array( $stage, array( 'built', 'finished' ), true ) ? 'current' : '',
			'live'  => 'finished' === $stage ? 'done' : '',
		);

		$next = array(
			'title'  => __( 'Start from a demo, or pick your goal below.', 'buddyx' ),
			'text'   => __( 'A demo gives you ready pages and sample content in minutes. You can remove it later.', 'buddyx' ),
			'link'   => null,
			'action' => null,
			'film'   => 'Q1MPFOxc388',
		);
		if ( 'finished' === $stage ) {
			$next['title'] = __( 'Your site is live. The pages are yours.', 'buddyx' );
			$next['text']  = __( 'Edit anything. The samples are gone and your setup stayed. Make it yours with the tiles below.', 'buddyx' );
			$next['link']  = array( $this->customize_url( 'autofocus[control]=custom_logo' ), __( 'Open the Customizer', 'buddyx' ) );
		} elseif ( 'built' === $stage ) {
			$next['title'] = __( 'Your demo site is built. Take it live.', 'buddyx' );
			$next['text']  = __( 'Keep the pages, menus and settings you like and remove the sample members and content. Nothing is deleted until you confirm.', 'buddyx' );
			$next['link']  = array( $demo_ui, __( 'Take your site live', 'buddyx' ) );
			$next['film']  = 'WijuvjG6WiE';
		} elseif ( 'building' === $stage ) {
			$next['title'] = __( 'Your demo site is being built.', 'buddyx' );
			$next['text']  = __( 'You can leave this page. The build keeps going, and this step moves on when it is done.', 'buddyx' );
			$next['link']  = array( $demo_ui, __( 'Watch the build', 'buddyx' ) );
		} elseif ( 'idle' === $stage ) {
			$next['link'] = array( $demo_ui, __( 'Set up a demo site', 'buddyx' ) );
			$next['film'] = 'Q1MPFOxc388';
		} else {
			// The Demo Installer is missing or not active: download or activate it first.
			$next['text']   = __( 'The free Wbcom Demo Installer gives you ready pages and sample content in minutes. You can remove it later.', 'buddyx' );
			$next['action'] = $demo;
			$next['film']   = 'Q1MPFOxc388';
			if ( $demo['link'] && 'missing' === $stage ) {
				$next['action']['link']['label'] = __( 'Get the Demo Installer', 'buddyx' );
			}
		}

		$stages = array(
			'build' => __( 'Build', 'buddyx' ),
			'make'  => __( 'Make it yours', 'buddyx' ),
			'live'  => __( 'Go live', 'buddyx' ),
		);
		$marks  = array(
			'done'    => __( '(done)', 'buddyx' ),
			'current' => __( '(you are here)', 'buddyx' ),
			''        => '',
		);

		$goals       = array(
			array(
				'name'   => __( 'Blog or business', 'buddyx' ),
				'text'   => __( 'A portfolio or a company site.', 'buddyx' ),
				'plugin' => $helpers['wbcom-essential'],
				'open'   => __( 'Build a page', 'buddyx' ),
			),
			array(
				'name'   => __( 'Community', 'buddyx' ),
				'text'   => __( 'Members, profiles, activity and groups.', 'buddyx' ),
				'plugin' => $family['buddynext'],
				'open'   => __( 'Set up community', 'buddyx' ),
			),
			array(
				'name'   => __( 'Courses', 'buddyx' ),
				'text'   => __( 'Teach online with lessons and quizzes.', 'buddyx' ),
				'plugin' => $family['learnomy'],
				'open'   => __( 'Set up courses', 'buddyx' ),
			),
			array(
				'name'   => __( 'Services marketplace', 'buddyx' ),
				'text'   => __( 'Vendors sell services and take orders.', 'buddyx' ),
				'plugin' => $family['wp-sell-services'],
				'open'   => __( 'Set up services', 'buddyx' ),
			),
		);
		$state_names = array(
			'active'    => __( 'Active', 'buddyx' ),
			'installed' => __( 'Installed, not active', 'buddyx' ),
			'missing'   => __( 'Not installed', 'buddyx' ),
		);

		$tiles = array(
			array( 'autofocus[control]=custom_logo', __( 'Upload Logo', 'buddyx' ) ),
			array( 'autofocus[section]=site_skin_section', __( 'Set Colors', 'buddyx' ) ),
			array( 'autofocus[section]=site_layout', __( 'Site Layout', 'buddyx' ) ),
			array( 'autofocus[panel]=typography_panel', __( 'Set Typography', 'buddyx' ) ),
			array( 'autofocus[section]=site_header_section', __( 'Header Settings', 'buddyx' ) ),
			array( 'autofocus[section]=site_blog_section', __( 'Site Blog', 'buddyx' ) ),
		);

		$more = array(
			array( $this->url( 'docs' ), __( 'Documentation', 'buddyx' ) ),
			array( $this->url( 'films' ), __( 'Watch the setup films', 'buddyx' ) ),
			array( $this->url( 'support' ), __( 'Support', 'buddyx' ) ),
			array( $this->url( 'contact' ), __( 'Give us feedback', 'buddyx' ) ),
			array( $family['buddynext']['href'], $family['buddynext']['name'] ),
			array( $family['jetonomy']['href'], $family['jetonomy']['name'] ),
			array( $family['learnomy']['href'], $family['learnomy']['name'] ),
			array( $this->url( 'downloads' ), __( 'See all Wbcom plugins', 'buddyx' ) ),
			array( $this->url( 'child_theme' ), __( 'Download the child theme', 'buddyx' ) ),
			array( $this->url( 'pro' ), __( 'Get BuddyX Pro', 'buddyx' ) ),
		);
		?>
		<section class="buddyx-gs-next" aria-labelledby="buddyx-gs-next-title">
			<div class="buddyx-gs-next-head">
				<ol class="buddyx-gs-stages">
					<?php foreach ( $stages as $key => $label ) : ?>
						<li class="buddyx-gs-stage<?php echo $progress[ $key ] ? ' is-' . esc_attr( $progress[ $key ] ) : ''; ?>"<?php echo 'current' === $progress[ $key ] ? ' aria-current="step"' : ''; ?>>
							<?php echo esc_html( $label ); ?>
							<?php if ( $marks[ $progress[ $key ] ] ) : ?>
								<span class="screen-reader-text"><?php echo esc_html( $marks[ $progress[ $key ] ] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
				<span class="buddyx-gs-next-label"><?php esc_html_e( 'Your next step', 'buddyx' ); ?></span>
			</div>
			<div class="buddyx-gs-next-body">
				<div class="buddyx-gs-next-text">
					<h2 id="buddyx-gs-next-title"><?php echo esc_html( $next['title'] ); ?></h2>
					<p><?php echo esc_html( $next['text'] ); ?></p>
				</div>
				<div class="buddyx-gs-next-actions">
					<?php
					if ( $next['link'] ) {
						$this->link( $next['link'][0], $next['link'][1], false, '', 'buddyx-gs-button' );
					}
					if ( $next['action'] ) {
						$this->render_action( $next['action'], $helpers['wbcom-demo-installer']['name'], 'buddyx-gs-button' );
					}
					?>
				</div>
			</div>
			<?php
			$this->render_films( $next['film'] );
			?>
		</section>

		<section class="buddyx-gs-section" aria-labelledby="buddyx-gs-goals">
			<h2 id="buddyx-gs-goals" class="buddyx-gs-section-title" tabindex="-1"><?php esc_html_e( 'What are you building?', 'buddyx' ); ?></h2>
			<p class="buddyx-gs-section-text"><?php esc_html_e( 'Our plugins are shown first. Other supported plugins, such as BuddyPress or WooCommerce, work too and stay your choice.', 'buddyx' ); ?></p>
			<ul class="buddyx-gs-goals">
				<?php
				foreach ( $goals as $goal ) :
					$action = $this->plugin_action( $goal['plugin'], $goal['open'] );
					?>
					<li class="buddyx-gs-card">
						<h3 class="buddyx-gs-card-title"><?php echo esc_html( $goal['name'] ); ?></h3>
						<p class="buddyx-gs-card-text"><?php echo esc_html( $goal['text'] ); ?></p>
						<p class="buddyx-gs-card-state is-<?php echo esc_attr( $action['state'] ); ?>">
							<?php
							/* translators: 1: plugin name, 2: its state, for example "Active" or "Not installed". */
							echo esc_html( sprintf( __( '%1$s: %2$s', 'buddyx' ), $goal['plugin']['name'], $state_names[ $action['state'] ] ) );
							?>
						</p>
						<?php $this->render_action( $action, $goal['plugin']['name'], 'buddyx-gs-button buddyx-gs-button--outline' ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>

		<section class="buddyx-gs-section" aria-labelledby="buddyx-gs-tiles">
			<h2 id="buddyx-gs-tiles" class="buddyx-gs-section-title"><?php esc_html_e( 'Then make it yours', 'buddyx' ); ?></h2>
			<ul class="buddyx-gs-tiles">
				<?php foreach ( $tiles as $tile ) : ?>
					<li><?php $this->link( $this->customize_url( $tile[0] ), $tile[1], false, '', 'buddyx-gs-tile' ); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<details class="buddyx-gs-more">
			<summary><?php esc_html_e( 'More help and plugins', 'buddyx' ); ?></summary>
			<ul class="buddyx-gs-more-list">
				<?php foreach ( $more as $item ) : ?>
					<li><?php $this->link( $item[0], $item[1] ); ?></li>
				<?php endforeach; ?>
			</ul>
		</details>
		<?php
	}

	/**
	 * Step by step tab: seven steps that tick themselves from the site's own
	 * settings, a progress bar and a help panel for the chosen step.
	 *
	 * @return void
	 */
	private function render_guide() {
		$stage    = $this->demo_stage();
		$family   = $this->family_plugins();
		$demo_ui  = admin_url( 'themes.php?page=wbcom-demo' );
		$goal_set = function_exists( 'buddypress' ) || class_exists( 'WooCommerce' );
		foreach ( $family as $product ) {
			$goal_set = $goal_set || 'active' === $this->family_status( $product );
		}

		$steps = array(
			array(
				'title' => __( 'Add your logo', 'buddyx' ),
				'sub'   => __( 'Upload a logo and site icon', 'buddyx' ),
				'time'  => __( '2 min', 'buddyx' ),
				'done'  => has_custom_logo(),
				'what'  => __( 'Shows your brand in the header, the login screen and browser tabs.', 'buddyx' ),
				'how'   => array(
					__( 'Open the Customizer, then Site Identity.', 'buddyx' ),
					__( 'Upload a logo (SVG or PNG, at least 200px wide).', 'buddyx' ),
					__( 'Upload a square site icon, then Publish.', 'buddyx' ),
				),
				'cta'   => array( $this->customize_url( 'autofocus[control]=custom_logo' ), __( 'Open Site Identity', 'buddyx' ) ),
				'guide' => $this->url( 'docs', 'free/recipes/customize-brand/' ),
			),
			array(
				'title' => __( 'Choose your home page', 'buddyx' ),
				'sub'   => __( 'Latest posts or a page you built', 'buddyx' ),
				'time'  => __( '1 min', 'buddyx' ),
				'done'  => 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ),
				'what'  => __( 'Decides what visitors see first when they open your address.', 'buddyx' ),
				'how'   => array(
					__( 'Go to Settings, then Reading.', 'buddyx' ),
					__( 'Pick Your latest posts or A static page.', 'buddyx' ),
					__( 'Choose the page and save.', 'buddyx' ),
				),
				'cta'   => array( admin_url( 'options-reading.php' ), __( 'Open Reading settings', 'buddyx' ) ),
				'guide' => $this->url( 'docs', 'free/recipes/build-your-homepage/' ),
			),
			array(
				'title' => __( 'Pick your colors', 'buddyx' ),
				'sub'   => __( 'Brand color and dark mode', 'buddyx' ),
				'time'  => __( '3 min', 'buddyx' ),
				'done'  => false !== get_theme_mod( 'site_primary_color', false ) || false !== get_theme_mod( 'site_style_variation', false ),
				'what'  => __( 'Sets buttons, links and accents everywhere on the site, and the dark mode colors your visitors can switch to.', 'buddyx' ),
				'how'   => array(
					__( 'Open the Customizer, then Colors and Dark Mode.', 'buddyx' ),
					__( 'Choose a style or your own brand color.', 'buddyx' ),
					__( 'Check the preview in light and dark, then Publish.', 'buddyx' ),
				),
				'cta'   => array( $this->customize_url( 'autofocus[section]=site_skin_section' ), __( 'Open Colors and Dark Mode', 'buddyx' ) ),
				'guide' => $this->url( 'docs', 'free/skin-colors/' ),
			),
			array(
				'title' => __( 'Set up your menu', 'buddyx' ),
				'sub'   => __( 'Header and mobile menu', 'buddyx' ),
				'time'  => __( '3 min', 'buddyx' ),
				'done'  => has_nav_menu( 'primary' ),
				'what'  => __( 'Gives visitors the main ways around your site, on desktop and phones.', 'buddyx' ),
				'how'   => array(
					__( 'Go to Appearance, then Menus.', 'buddyx' ),
					__( 'Create a menu and add your key pages.', 'buddyx' ),
					__( 'Assign it to Primary and save.', 'buddyx' ),
				),
				'cta'   => array( admin_url( 'nav-menus.php' ), __( 'Open Menus', 'buddyx' ) ),
				'guide' => $this->url( 'docs', 'free/customizing/header/' ),
			),
			array(
				'title' => __( 'Choose your layout', 'buddyx' ),
				'sub'   => __( 'Width, sidebar and header', 'buddyx' ),
				'time'  => __( '3 min', 'buddyx' ),
				'done'  => false !== get_theme_mod( 'site_layout', false ) || false !== get_theme_mod( 'site_container_width', false ),
				'what'  => __( 'Controls page width, sidebar position and header options.', 'buddyx' ),
				'how'   => array(
					__( 'Open the Customizer, then Layout.', 'buddyx' ),
					__( 'Pick the site layout and container width.', 'buddyx' ),
					__( 'Set the sidebar side under Sidebar, then Publish.', 'buddyx' ),
				),
				'cta'   => array( $this->customize_url( 'autofocus[section]=site_layout' ), __( 'Open Layout', 'buddyx' ) ),
				'guide' => $this->url( 'docs', 'free/customizing/layout/' ),
			),
			array(
				'title' => __( 'Add a plugin for your goal', 'buddyx' ),
				'sub'   => __( 'Community, courses or services', 'buddyx' ),
				'time'  => __( '5 min', 'buddyx' ),
				'done'  => $goal_set,
				'what'  => __( 'BuddyX styles the plugin you add, so it looks like part of your theme. Our own plugin is always listed first.', 'buddyx' ),
				'how'   => array(
					__( 'Pick what you are building: Community (BuddyNext), Courses (Learnomy), Services (WP Sell Services) or Jobs (WP Career Board).', 'buddyx' ),
					__( 'Get the Wbcom plugin shown first. Other supported plugins, such as BuddyPress or WooCommerce, work too. Nothing installs until you click.', 'buddyx' ),
					__( 'Follow its short setup checklist.', 'buddyx' ),
				),
				'tab'   => array( 'start', 'buddyx-gs-goals', __( 'Choose your goal', 'buddyx' ) ),
				'guide' => $this->url( 'docs', 'free/getting-started/choose-what-to-build/' ),
				'film'  => array( $this->url( 'film_community' ), __( 'Watch: Set up a community site in 5 minutes (3:46)', 'buddyx' ) ),
			),
			array(
				'title' => __( 'Take your site live', 'buddyx' ),
				'sub'   => __( 'Remove sample content, if you built a demo', 'buddyx' ),
				'time'  => __( '2 min', 'buddyx' ),
				'done'  => 'finished' === $stage,
				'what'  => __( 'Keeps the pages and settings you like and removes the demo members and sample posts.', 'buddyx' ),
				'how'   => array(
					__( 'Review what the demo created.', 'buddyx' ),
					__( 'Choose Take your site live.', 'buddyx' ),
					__( 'Confirm. Nothing is deleted before you confirm.', 'buddyx' ),
				),
				'cta'   => in_array( $stage, array( 'idle', 'building', 'built', 'finished' ), true ) ? array( $demo_ui, __( 'Take your site live', 'buddyx' ) ) : null,
				'guide' => $this->url( 'docs', 'free/getting-started/demo-installer/' ),
				'film'  => array( $this->url( 'film_live' ), __( 'Watch: Take your demo site live (2:38)', 'buddyx' ) ),
			),
		);

		$done     = count( array_filter( wp_list_pluck( $steps, 'done' ) ) );
		$total    = count( $steps );
		$selected = 0;
		foreach ( $steps as $i => $step ) {
			if ( ! $step['done'] ) {
				$selected = $i;
				break;
			}
		}

		$demo_names = array(
			'missing'   => __( 'Not started', 'buddyx' ),
			'installed' => __( 'Not started', 'buddyx' ),
			'idle'      => __( 'Not started', 'buddyx' ),
			'building'  => __( 'Being built', 'buddyx' ),
			'built'     => __( 'Built, not live yet', 'buddyx' ),
			'finished'  => __( 'Live', 'buddyx' ),
		);
		$community  = __( 'None yet', 'buddyx' );
		if ( 'active' === $this->family_status( $family['buddynext'] ) ) {
			$community = __( 'BuddyNext active', 'buddyx' );
		} elseif ( function_exists( 'buddypress' ) ) {
			$community = __( 'BuddyPress active', 'buddyx' );
		}
		$basics = array(
			/* translators: %s: theme version number. */
			__( 'Theme', 'buddyx' )            => sprintf( __( 'BuddyX %s', 'buddyx' ), wp_get_theme( get_template() )->get( 'Version' ) ),
			__( 'Demo site', 'buddyx' )        => $demo_names[ $stage ],
			__( 'Community plugin', 'buddyx' ) => $community,
		);
		?>
		<div class="buddyx-gs-guide-head">
			<div class="buddyx-gs-guide-intro">
				<h2 class="buddyx-gs-section-title"><?php esc_html_e( 'Your site, step by step', 'buddyx' ); ?></h2>
				<p class="buddyx-gs-section-text"><?php esc_html_e( 'Each step ticks itself when it is done. Pick any step to see what it does, how to do it and the guide.', 'buddyx' ); ?></p>
			</div>
			<div class="buddyx-gs-progress">
				<label for="buddyx-gs-progress-bar">
					<?php
					/* translators: 1: steps done, 2: total steps. */
					echo esc_html( sprintf( __( '%1$s of %2$s steps done', 'buddyx' ), number_format_i18n( $done ), number_format_i18n( $total ) ) );
					?>
				</label>
				<progress id="buddyx-gs-progress-bar" max="<?php echo absint( $total ); ?>" value="<?php echo absint( $done ); ?>"></progress>
			</div>
		</div>

		<dl class="buddyx-gs-basics">
			<?php foreach ( $basics as $key => $value ) : ?>
				<div>
					<dt><?php echo esc_html( $key ); ?></dt>
					<dd><?php echo esc_html( $value ); ?></dd>
				</div>
			<?php endforeach; ?>
			<div>
				<dt><?php esc_html_e( 'Need help?', 'buddyx' ); ?></dt>
				<dd><?php $this->tab_link( 'links', 'buddyx-panel-links', __( 'Docs, films, support', 'buddyx' ) ); ?></dd>
			</div>
		</dl>

		<div class="buddyx-gs-guide">
			<ol class="buddyx-gs-checklist">
				<?php foreach ( $steps as $i => $step ) : ?>
					<li>
						<button type="button" class="buddyx-gs-check<?php echo $step['done'] ? ' is-done' : ''; ?>" aria-controls="buddyx-gs-help-<?php echo absint( $i ); ?>" aria-pressed="<?php echo $selected === $i ? 'true' : 'false'; ?>">
							<span class="buddyx-gs-check-mark" aria-hidden="true"><?php echo $step['done'] ? '&#10003;' : esc_html( number_format_i18n( $i + 1 ) ); ?></span>
							<span class="buddyx-gs-check-body">
								<span class="buddyx-gs-check-title"><?php echo esc_html( $step['title'] ); ?></span>
								<span class="buddyx-gs-check-sub"><?php echo esc_html( $step['sub'] ); ?></span>
							</span>
							<span class="buddyx-gs-check-time"><?php echo esc_html( $step['time'] ); ?></span>
							<?php if ( $step['done'] ) : ?>
								<span class="screen-reader-text"><?php esc_html_e( '(done)', 'buddyx' ); ?></span>
							<?php endif; ?>
						</button>
					</li>
				<?php endforeach; ?>
			</ol>

			<?php foreach ( $steps as $i => $step ) : ?>
				<section class="buddyx-gs-help" id="buddyx-gs-help-<?php echo absint( $i ); ?>" aria-labelledby="buddyx-gs-help-title-<?php echo absint( $i ); ?>"<?php echo $selected === $i ? '' : ' hidden'; ?>>
					<p class="buddyx-gs-help-label"><?php esc_html_e( 'Help for this step', 'buddyx' ); ?></p>
					<h3 id="buddyx-gs-help-title-<?php echo absint( $i ); ?>" class="buddyx-gs-help-title"><?php echo esc_html( $step['title'] ); ?></h3>
					<h4 class="buddyx-gs-label"><?php esc_html_e( 'What it does', 'buddyx' ); ?></h4>
					<p class="buddyx-gs-help-text"><?php echo esc_html( $step['what'] ); ?></p>
					<h4 class="buddyx-gs-label"><?php esc_html_e( 'How to do it', 'buddyx' ); ?></h4>
					<ol class="buddyx-gs-help-steps">
						<?php foreach ( $step['how'] as $how ) : ?>
							<li><?php echo esc_html( $how ); ?></li>
						<?php endforeach; ?>
					</ol>
					<p class="buddyx-gs-help-actions">
						<?php
						if ( ! empty( $step['cta'] ) ) {
							$this->link( $step['cta'][0], $step['cta'][1], false, '', 'buddyx-gs-button' );
						}
						if ( ! empty( $step['tab'] ) ) {
							$this->tab_link( $step['tab'][0], $step['tab'][1], $step['tab'][2], 'buddyx-gs-button' );
						}
						$this->link( $step['guide'], __( 'Read the guide', 'buddyx' ), true, $step['title'], 'buddyx-gs-button buddyx-gs-button--outline' );
						if ( ! empty( $step['film'] ) ) {
							$this->link( $step['film'][0], $step['film'][1], true, '', 'buddyx-gs-button buddyx-gs-button--outline' );
						}
						?>
					</p>
					<p class="buddyx-gs-help-foot">
						<?php
						esc_html_e( 'Stuck?', 'buddyx' );
						echo ' ';
						$this->link( $this->url( 'support' ), __( 'Ask support', 'buddyx' ) );
						echo ' ';
						esc_html_e( 'or', 'buddyx' );
						echo ' ';
						$this->link( $this->url( 'docs' ), __( 'search the docs', 'buddyx' ) );
						echo '.';
						?>
					</p>
				</section>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Our products tab: the family with live state and a named next action,
	 * then the free helpers.
	 *
	 * @return void
	 */
	private function render_products() {
		$state_names = array(
			'active'    => __( 'Active', 'buddyx' ),
			'installed' => __( 'Installed, not active', 'buddyx' ),
			'missing'   => __( 'Not installed', 'buddyx' ),
		);
		$groups      = array(
			__( 'Plugins for your site', 'buddyx' )   => $this->family_plugins(),
			__( 'Free helpers from Wbcom', 'buddyx' ) => $this->helper_plugins(),
		);
		?>
		<p class="buddyx-gs-intro"><?php esc_html_e( 'One family of plugins built to work together and styled by BuddyX. Install only what your site needs. Plugin pages open in a new tab.', 'buddyx' ); ?></p>
		<?php foreach ( $groups as $group => $plugins ) : ?>
			<h2 class="buddyx-gs-label"><?php echo esc_html( $group ); ?></h2>
			<ul class="buddyx-gs-list">
				<?php
				foreach ( $plugins as $plugin ) :
					$action = $this->plugin_action( $plugin );
					?>
					<li class="buddyx-gs-item">
						<strong class="buddyx-gs-item-name"><?php echo esc_html( $plugin['name'] ); ?></strong>
						<span class="buddyx-gs-item-text"><?php echo esc_html( $plugin['desc'] ); ?></span>
						<span class="buddyx-gs-card-state is-<?php echo esc_attr( $action['state'] ); ?>"><?php echo esc_html( $state_names[ $action['state'] ] ); ?></span>
						<span class="buddyx-gs-item-action">
							<?php $this->render_action( $action, $plugin['name'], 'buddyx-gs-button buddyx-gs-button--outline', false ); ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
		<p class="buddyx-gs-foot">
			<?php
			esc_html_e( 'BuddyX never downloads or installs a plugin for you. Install opens the plugin page, where you download it; then add the file at', 'buddyx' );
			echo ' ';
			$this->link( network_admin_url( 'plugin-install.php?tab=upload' ), __( 'Upload Plugin', 'buddyx' ), false );
			echo '.';
			?>
		</p>
		<?php
	}

	/**
	 * Free vs Pro tab: the Pro-only rows of features(), grouped by area.
	 *
	 * @return void
	 */
	private function render_compare() {
		$total = 0;
		$areas = array();
		foreach ( $this->features() as $area => $rows ) {
			$total += count( $rows );
			$pro    = array();
			foreach ( $rows as $row ) {
				if ( 'yes' !== $row[1] ) {
					$pro[] = $row[0];
				}
			}
			if ( $pro ) {
				$areas[ $area ] = $pro;
			}
		}
		uasort(
			$areas,
			static function ( $a, $b ) {
				return count( $b ) <=> count( $a );
			}
		);
		$pro_total = array_sum( array_map( 'count', $areas ) );
		?>
		<p class="buddyx-gs-intro">
			<?php
			echo esc_html(
				sprintf(
					/* translators: %s: number of settings only BuddyX Pro has. */
					__( 'BuddyX covers the essentials: layout, typography, colors, dark mode, blog and sidebar settings are in both. Pro adds %s more settings, listed by area.', 'buddyx' ),
					number_format_i18n( $pro_total )
				)
			);
			?>
		</p>
		<ul class="buddyx-gs-list">
			<?php foreach ( $areas as $area => $names ) : ?>
				<li class="buddyx-gs-item buddyx-gs-item--area">
					<strong class="buddyx-gs-item-name">
						<?php echo esc_html( $area ); ?>
						<span class="buddyx-gs-item-count"><?php echo esc_html( number_format_i18n( count( $names ) ) ); ?></span>
					</strong>
					<span class="buddyx-gs-item-text"><?php echo esc_html( implode( wp_get_list_item_separator(), $names ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="buddyx-gs-callout">
			<p class="buddyx-gs-callout-text"><?php esc_html_e( 'The same theme with every control above, plus priority support.', 'buddyx' ); ?></p>
			<?php $this->link( $this->url( 'pro' ), __( 'Get BuddyX Pro', 'buddyx' ), true, '', 'buddyx-gs-button' ); ?>
		</div>
		<p class="buddyx-gs-foot">
			<?php esc_html_e( 'Already own Pro? Install it and use Migrate from BuddyX Free to carry your settings over.', 'buddyx' ); ?>
			<?php
			/* translators: %s: total number of features compared. */
			$this->link( $this->url( 'compare' ), sprintf( __( 'See the full %s-feature comparison', 'buddyx' ), number_format_i18n( $total ) ) );
			?>
		</p>
		<?php
	}

	/**
	 * Help and links tab: static link groups.
	 *
	 * @return void
	 */
	private function render_links() {
		$groups = array(
			__( 'Documentation', 'buddyx' )          => array(
				array( $this->url( 'docs', 'free/getting-started/' ), __( 'Getting Started', 'buddyx' ) ),
				array( $this->url( 'docs', 'free/customizing/' ), __( 'Customizing', 'buddyx' ) ),
				array( $this->url( 'docs', 'free/skin-colors/' ), __( 'Skin and Colors', 'buddyx' ) ),
				array( $this->url( 'docs', 'free/blocks-and-patterns/' ), __( 'Blocks and Patterns', 'buddyx' ) ),
				array( $this->url( 'docs', 'buddypress/' ), __( 'BuddyPress', 'buddyx' ) ),
				array( $this->url( 'docs', 'woocommerce/' ), __( 'WooCommerce', 'buddyx' ) ),
				array( $this->url( 'docs', 'free/developer/' ), __( 'Developer', 'buddyx' ) ),
				array( $this->url( 'docs', 'faq-support/' ), __( 'FAQ and Support', 'buddyx' ) ),
			),
			__( 'Learn', 'buddyx' )                  => array(
				array( $this->url( 'film_community' ), __( 'Set up a community site in 5 minutes (3:46)', 'buddyx' ) ),
				array( $this->url( 'film_live' ), __( 'Take your demo site live (2:38)', 'buddyx' ) ),
				array( $this->url( 'films' ), __( 'All setup films (playlist)', 'buddyx' ) ),
			),
			__( 'Get help and give back', 'buddyx' ) => array(
				array( $this->url( 'support' ), __( 'Contact support', 'buddyx' ) ),
				array( $this->url( 'contact' ), __( 'Contact us', 'buddyx' ) ),
				array( $this->url( 'group' ), __( 'Join the community group', 'buddyx' ) ),
				array( $this->url( 'review' ), __( 'Leave a 5-star review', 'buddyx' ) ),
			),
			__( 'Downloads', 'buddyx' )              => array(
				array( $this->url( 'child_theme' ), __( 'BuddyX child theme', 'buddyx' ) ),
				array( $this->url( 'essential' ), __( 'Wbcom Essential', 'buddyx' ) ),
				array( $this->url( 'demo_installer' ), __( 'Wbcom Demo Installer', 'buddyx' ) ),
			),
			__( 'Our site', 'buddyx' )               => array(
				array( $this->url( 'wbcom' ), __( 'wbcomdesigns.com', 'buddyx' ) ),
				array( $this->url( 'downloads' ), __( 'All Wbcom plugins and themes', 'buddyx' ) ),
				array( $this->url( 'release_notes' ), __( 'Release notes', 'buddyx' ) ),
			),
		);
		?>
		<div class="buddyx-gs-groups">
			<?php foreach ( $groups as $group => $links ) : ?>
				<section class="buddyx-gs-group">
					<h2 class="buddyx-gs-label"><?php echo esc_html( $group ); ?></h2>
					<ul class="buddyx-gs-group-list">
						<?php foreach ( $links as $group_link ) : ?>
							<li><?php $this->link( $group_link[0], $group_link[1] ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Renders the Getting Started page in the admin.
	 *
	 * @return void
	 */
	public function submenu_page_callback() {
		$tabs = array(
			'start'    => array( __( 'Start', 'buddyx' ), 'render_start' ),
			'guide'    => array( __( 'Step by step', 'buddyx' ), 'render_guide' ),
			'products' => array( __( 'Our products', 'buddyx' ), 'render_products' ),
			'compare'  => array( __( 'Free vs Pro', 'buddyx' ), 'render_compare' ),
			'links'    => array( __( 'Help and links', 'buddyx' ), 'render_links' ),
		);
		?>
		<div class="wrap buddyx-gs">
			<div class="buddyx-gs-header">
				<div class="buddyx-gs-header-text">
					<h1 class="buddyx-gs-title"><?php esc_html_e( 'Welcome to BuddyX', 'buddyx' ); ?></h1>
					<p><?php esc_html_e( 'Three steps to a finished site. Start with the one highlighted below.', 'buddyx' ); ?></p>
				</div>
				<span class="buddyx-gs-version">
					<?php
					/* translators: %s: theme version number. */
					echo esc_html( sprintf( __( 'Version %s', 'buddyx' ), wp_get_theme( get_template() )->get( 'Version' ) ) );
					?>
				</span>
				<?php $this->link( $this->customize_url( '' ), __( 'Open Customizer', 'buddyx' ), false, '', 'buddyx-gs-button' ); ?>
			</div>
			<hr class="wp-header-end">
			<div class="buddyx-dashboard-tabs">
				<div class="tabs">
					<div class="buddyx-gs-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Getting Started sections', 'buddyx' ); ?>">
						<?php foreach ( array_keys( $tabs ) as $i => $key ) : ?>
							<button type="button" role="tab" id="buddyx-tab-<?php echo esc_attr( $key ); ?>" aria-controls="buddyx-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $tabs[ $key ][0] ); ?></button>
						<?php endforeach; ?>
					</div>
					<?php foreach ( array_keys( $tabs ) as $i => $key ) : ?>
						<div class="buddyx-gs-panel" role="tabpanel" tabindex="-1" id="buddyx-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="buddyx-tab-<?php echo esc_attr( $key ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
							<?php $this->{ $tabs[ $key ][1] }(); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
