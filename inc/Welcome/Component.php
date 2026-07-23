<?php
/**
 * BuddyX\Buddyx\Welcome\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Welcome;

use BuddyX\Buddyx\Component_Interface;
use function add_action;


/**
 * Class Component
 *
 * @package BuddyX\Buddyx\Welcome
 */
class Component implements Component_Interface {

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

		// Redirect admin after theme switch.
		add_action( 'after_switch_theme', array( $this, 'redirect_admin' ) );

		add_action( 'admin_init', array( $this, 'hide_welcome_page_notices' ) );
	}

	/**
	 *  Add welcome page at admin
	 */
	public function add_admin_menu_page() {
		add_submenu_page(
			'themes.php',
			__( 'Getting Started', 'buddyx' ),
			__( 'Getting Started', 'buddyx' ),
			'edit_theme_options',
			'buddyx-welcome',
			array( &$this, 'submenu_page_callback' )
		);
	}

	/**
	 * Redirect Admin
	 *
	 * @since 4.5.9
	 * @access public
	 * @return void
	 */
	public function redirect_admin() {
		if ( current_user_can( 'edit_theme_options' ) ) {
			header( 'Location:' . admin_url() . 'admin.php?page=buddyx-welcome' );
		}
	}

	/**
	 * Hide admin notices.
	 */
	public function hide_welcome_page_notices() {
		$wbcom_pages_array  = array( 'buddyx-welcome' );
		$wbcom_setting_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';

		if ( in_array( $wbcom_setting_page, $wbcom_pages_array, true ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	/**
	 * Resolve a family product's status for the UI.
	 *
	 * DETECTION ONLY. BuddyX ships on WordPress.org, so it holds no plugin
	 * catalog, no EDD item ids and no distribution licence keys, and it cannot
	 * install anything from a third-party server. All this does is notice
	 * whether the plugin is already running, so an active product shows a pill
	 * instead of a link.
	 *
	 * @param array $product Product row from the family plugins list.
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
	 * Renders the Getting Started welcome page in the admin.
	 *
	 * @return void
	 */
	public function submenu_page_callback() {
		?>
		<div class="buddyx-top-banner-wrapper">
			<div class="buddyx-top-banner">
				<span class="buddyx-banner-icon">
					<svg width="26" height="26" xmlns="http://www.w3.org/2000/svg" class="buddyx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
				</span>
				<div class="buddyx-banner-body">
					<h1 class="buddyx-banner-title">
						<?php esc_html_e( 'Welcome to BuddyX Theme', 'buddyx' ); ?>
						<span class="buddyx-theme-version">
							<?php esc_html_e( 'Version', 'buddyx' ); ?> <?php echo esc_html( wp_get_theme( get_template() )->get( 'Version' ) ); ?>
						</span>
					</h1>
					<div class="buddyx-banner-description">
						<p><?php esc_html_e( 'Thank you for choosing the BuddyX Theme! Below you will find information on how to set up the theme and start using it.', 'buddyx' ); ?></p>
					</div>
					<a class="buddyx-btn buddyx-btn-primary buddyx-banner-cta" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Open Customizer', 'buddyx' ); ?></a>
				</div>
			</div>
		</div>
		<div class="wrap">
			<div class="welcome-inner-wrap">
				<div class="buddyx-dashboard-tabs">

					<section class="content">
						<div class="tabs">
						<div role="tablist" aria-label="<?php esc_attr_e( 'Getting Started sections', 'buddyx' ); ?>">
							<button role="tab" aria-selected="true" id="tab1"><?php esc_html_e( 'Dashboard', 'buddyx' ); ?></button>
							<button role="tab" aria-selected="false" id="tab2"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></button>
							<button role="tab" aria-selected="false" id="tab3"><?php esc_html_e( 'Community Addons', 'buddyx' ); ?></button>
						</div>
						<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab1', 'buddyx' ); ?>">
							<div class="buddyx-tabs-content buddyx-dashboard-welcome-body">
								<div class="buddyx-welcome-container">

									<!-- Customizer Options -->
									<div class="buddyx-welcome-column">
									<h2 class="buddyx-tabs-title">
										<?php esc_html_e( 'Customizer Settings', 'buddyx' ); ?>
										<a class="section-header-link" target="_blank" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Go to Customizer', 'buddyx' ); ?></a>
									</h2>
									<div class="buddyx-grid-box-wrap">
										<?php
										$buddyx_customizer_tiles = array(
											array(
												'icon' => '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
												'name' => __( 'Upload Logo', 'buddyx' ),
												'href' => admin_url( 'customize.php?autofocus[control]=custom_logo&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ),
											),
											array(
												'icon' => '<path d="M12 22a1 1 0 0 1 0-20 10 9 0 0 1 10 9 5 5 0 0 1-5 5h-2.25a1.75 1.75 0 0 0-1.4 2.8l.3.4a1.75 1.75 0 0 1-1.4 2.8z"/><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/>',
												'name' => __( 'Set Colors', 'buddyx' ),
												'href' => admin_url( 'customize.php?autofocus[control]=body_background_color&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ),
											),
											array(
												'icon' => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
												'name' => __( 'Site Layout', 'buddyx' ),
												'href' => admin_url( 'customize.php?autofocus[control]=site_layout&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ),
											),
											array(
												'icon' => '<polyline points="4 7 4 4 20 4 20 7"/><line x1="9" x2="15" y1="20" y2="20"/><line x1="12" x2="12" y1="4" y2="20"/>',
												'name' => __( 'Set Typography', 'buddyx' ),
												'href' => admin_url( 'customize.php?autofocus[panel]=typography_panel&return=' . admin_url( 'admin.php?page=buddyx-options' ) ),
											),
											array(
												'icon' => '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/>',
												'name' => __( 'Header Layout', 'buddyx' ),
												'href' => admin_url( 'customize.php?autofocus[section]=site_header_section&return=' . admin_url( 'admin.php?page=buddyx-options' ) ),
											),
											array(
												'icon' => '<rect width="18" height="7" x="3" y="3" rx="1"/><rect width="9" height="7" x="3" y="14" rx="1"/><rect width="5" height="7" x="16" y="14" rx="1"/>',
												'name' => __( 'Site Blog', 'buddyx' ),
												'href' => admin_url( 'customize.php?autofocus[section]=site_blog_section&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ),
											),
										);

										foreach ( $buddyx_customizer_tiles as $buddyx_customizer_tile ) :
											?>
											<a class="buddyx-box-item" href="<?php echo esc_url( $buddyx_customizer_tile['href'] ); ?>" target="_blank" rel="noopener">
												<span class="buddyx-box-item-icon">
													<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?php echo $buddyx_customizer_tile['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon markup curated inline above, not user input. ?></svg>
												</span>
												<h4 class="buddyx-box-item-name"><?php echo esc_html( $buddyx_customizer_tile['name'] ); ?></h4>
												<span class="buddyx-box-item-chevron">
													<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 18 6-6-6-6"/></svg>
												</span>
											</a>
											<?php
										endforeach;
										?>
									</div>
									</div>

									<!-- End Customizer Options -->

									<!-- Documentation -->
									<div class="buddyx-welcome-column">
									<h2 class="buddyx-tabs-title">
										<?php esc_html_e( 'Documentation', 'buddyx' ); ?>
										<a class="section-header-link" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs/' ); ?>"><?php esc_html_e( 'Browse all documentation', 'buddyx' ); ?></a>
									</h2>
									<p class="buddyx-section-intro"><?php esc_html_e( 'Step-by-step guides for setting up, customizing, and extending your BuddyX site. Jump straight to a topic below.', 'buddyx' ); ?></p>
									<div class="buddyx-grid-box-wrap">
										<?php
										$buddyx_docs_tiles = array(
											array(
												'icon' => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>',
												'name' => __( 'Getting Started', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/free/getting-started/',
											),
											array(
												'icon' => '<line x1="4" x2="4" y1="21" y2="14"/><line x1="4" x2="4" y1="10" y2="3"/><line x1="12" x2="12" y1="21" y2="12"/><line x1="12" x2="12" y1="8" y2="3"/><line x1="20" x2="20" y1="21" y2="16"/><line x1="20" x2="20" y1="12" y2="3"/><line x1="2" x2="6" y1="14" y2="14"/><line x1="10" x2="14" y1="8" y2="8"/><line x1="18" x2="22" y1="16" y2="16"/>',
												'name' => __( 'Customizing', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/free/customizing/',
											),
											array(
												'icon' => '<path d="M12 22a1 1 0 0 1 0-20 10 9 0 0 1 10 9 5 5 0 0 1-5 5h-2.25a1.75 1.75 0 0 0-1.4 2.8l.3.4a1.75 1.75 0 0 1-1.4 2.8z"/><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/>',
												'name' => __( 'Skin & Colors', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/free/skin-colors/',
											),
											array(
												'icon' => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
												'name' => __( 'Blocks & Patterns', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/free/blocks-and-patterns/',
											),
											array(
												'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
												'name' => __( 'BuddyPress', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/buddypress/',
											),
											array(
												'icon' => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
												'name' => __( 'WooCommerce', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/woocommerce/',
											),
											array(
												'icon' => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
												'name' => __( 'Developer', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/free/developer/',
											),
											array(
												'icon' => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" x2="12.01" y1="17" y2="17"/>',
												'name' => __( 'FAQ & Support', 'buddyx' ),
												'href' => 'https://buddyxtheme.com/docs/faq-support/',
											),
										);

										foreach ( $buddyx_docs_tiles as $buddyx_docs_tile ) :
											?>
											<a class="buddyx-box-item" href="<?php echo esc_url( $buddyx_docs_tile['href'] ); ?>" target="_blank" rel="noopener">
												<span class="buddyx-box-item-icon">
													<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?php echo $buddyx_docs_tile['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon markup curated inline above, not user input. ?></svg>
												</span>
												<h4 class="buddyx-box-item-name"><?php echo esc_html( $buddyx_docs_tile['name'] ); ?></h4>
												<span class="buddyx-box-item-chevron">
													<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 18 6-6-6-6"/></svg>
												</span>
											</a>
											<?php
										endforeach;
										?>
									</div>
									</div>
									<!-- End Documentation -->

									<!-- Wbcom Essential (free blocks + patterns companion) -->
									<div class="buddyx-welcome-column buddyx-essential-hero">
										<span class="buddyx-essential-icon">
											<svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="7" height="7" x="3" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="14" rx="1"></rect><rect width="7" height="7" x="3" y="14" rx="1"></rect></svg>
										</span>
										<div class="buddyx-essential-body">
											<span class="buddyx-bundle-badge"><?php esc_html_e( 'Free companion plugin', 'buddyx' ); ?></span>
											<h2 class="buddyx-tabs-title"><?php esc_html_e( 'Wbcom Essential', 'buddyx' ); ?></h2>
											<p class="buddyx-bundle-tagline"><?php esc_html_e( 'Ready-to-use blocks and patterns that inherit your BuddyX colors automatically. Build landing pages and community layouts fast, no page builder required.', 'buddyx' ); ?></p>
											<ul class="buddyx-essential-stats">
												<li><strong>32</strong> <?php esc_html_e( 'Gutenberg blocks', 'buddyx' ); ?></li>
												<li><strong>43</strong> <?php esc_html_e( 'Elementor widgets', 'buddyx' ); ?></li>
												<li><strong>5</strong> <?php esc_html_e( 'BuddyPress blocks', 'buddyx' ); ?></li>
												<li><?php esc_html_e( 'Blocks inherit your theme colors', 'buddyx' ); ?></li>
											</ul>
											<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/wbcom-essential/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme&utm_content=wbcom-essential' ); ?>"><?php esc_html_e( 'Get Wbcom Essential', 'buddyx' ); ?></a>
										</div>
									</div>

									<!-- Wbcom Community Family (bundled plugins) -->
									<div class="buddyx-welcome-column buddyx-family-section">
										<h2 class="buddyx-tabs-title">
											<?php esc_html_e( 'Part of the Wbcom Community Family', 'buddyx' ); ?>
											<a class="section-header-link" target="_blank" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme&utm_content=explore-all' ); ?>"><?php esc_html_e( 'Explore all', 'buddyx' ); ?></a>
										</h2>
										<p class="buddyx-family-intro"><?php esc_html_e( 'Social, gamification, discussions, courses, messaging, listings, and jobs. Every plugin works on its own, and the family keeps growing.', 'buddyx' ); ?></p>
										<div class="buddyx-family-grid">
											<?php
											$buddyx_family_plugins = array(
												array(
													'slug' => 'buddynext', // Flagship - dedicated site, not one-click installable.
													'logo' => 'buddynext.svg',
													'name' => __( 'BuddyNext', 'buddyx' ),
													'desc' => __( 'A modern, faster alternative to BuddyPress and BuddyBoss - profiles, activity feeds, Spaces, and messaging.', 'buddyx' ),
													'href' => 'https://buddynext.com/',
													'demo' => 'https://buddyxtheme.com/demos/',
												),
												array(
													'slug' => 'wb-gamification',
													'logo' => 'wb-gamification.svg',
													'name' => __( 'WB Gamification', 'buddyx' ),
													'desc' => __( 'Points, badges, levels, and leaderboards for any activity on your site.', 'buddyx' ),
													'href' => 'https://wbcomdesigns.com/downloads/wordpress-gamification-plugin/',
												),
												array(
													'slug' => 'jetonomy',
													'logo' => 'jetonomy.svg',
													'name' => __( 'Jetonomy', 'buddyx' ),
													'desc' => __( 'Structured discussions: forums, threaded Q&A, an ideas roadmap, and polls.', 'buddyx' ),
													'href' => 'https://jetonomy.org/',
												),
												array(
													'slug' => 'learnomy',
													'logo' => 'learnomy.svg',
													'name' => __( 'Learnomy', 'buddyx' ),
													'desc' => __( 'A full LMS: courses, lessons, quizzes, certificates, and paid enrollments.', 'buddyx' ),
													'href' => 'https://learnomy.app/',
												),
												array(
													'slug' => 'wpmediaverse',
													'logo' => 'wpmediaverse.svg',
													'name' => __( 'MediaVerse', 'buddyx' ),
													'desc' => __( 'Media albums and galleries for your members.', 'buddyx' ),
													'href' => 'https://store.wbcomdesigns.com/wpmediaverse/',
												),
												array(
													'slug' => 'eventonomy',
													'logo' => 'eventonomy.svg',
													'name' => __( 'Eventonomy', 'buddyx' ),
													'desc' => __( 'An events platform: RSVPs, paid ticketing, venues, and QR check-in.', 'buddyx' ),
													'href' => 'https://eventonomy.org/',
												),
												array(
													'slug' => 'wp-career-board',
													'logo' => 'wp-career-board.svg',
													'name' => __( 'WP Career Board', 'buddyx' ),
													'desc' => __( 'A job board: listings, company profiles, resumes, and applications.', 'buddyx' ),
													'href' => 'https://wpcareerboard.com/',
												),
												array(
													'slug' => 'wp-sell-services',
													'logo' => 'wp-sell-services.svg',
													'name' => __( 'WP Sell Services', 'buddyx' ),
													'desc' => __( 'A freelance services marketplace: vendor storefronts, orders, and payouts.', 'buddyx' ),
													'href' => 'https://wpsellservices.com/',
												),
												array(
													'slug' => 'wb-listora',
													'logo' => 'wb-listora.svg',
													'name' => __( 'Listora', 'buddyx' ),
													'desc' => __( 'A directory: member listings, claims, reviews, and monetization.', 'buddyx' ),
													'href' => 'https://listora.org/',
												),
											);

											/*
											 * BuddyX is distributed on WordPress.org, so this screen may not
											 * install anything. Themes hosted there cannot download plugin
											 * packages from a third-party server, and they certainly cannot
											 * ship a distribution licence key to authorize it. Every card here
											 * is a LINK to the plugin's page - the owner installs it however
											 * they choose. The one-click installer lives in BuddyX Pro, which
											 * is self-hosted and may do this.
											 */
											foreach ( $buddyx_family_plugins as $buddyx_family_plugin ) :
												$buddyx_family_plugin_status    = $this->family_status( $buddyx_family_plugin );
												$buddyx_family_plugin_is_active = ( 'active' === $buddyx_family_plugin_status );
												$buddyx_family_plugin_base_url  = $buddyx_family_plugin['href'];
												// Minimal UTM so we can see which welcome-screen card drove the visit.
												$buddyx_family_utm         = array(
													'utm_source'   => 'buddyx',
													'utm_medium'   => 'welcome',
													'utm_campaign' => 'buddyx-theme',
													'utm_content'  => sanitize_title( $buddyx_family_plugin['name'] ),
												);
												$buddyx_family_plugin_url  = add_query_arg( $buddyx_family_utm, $buddyx_family_plugin_base_url );
												$buddyx_family_plugin_demo = ! empty( $buddyx_family_plugin['demo'] )
													? add_query_arg(
														array_merge(
															$buddyx_family_utm,
															array( 'utm_content' => sanitize_title( $buddyx_family_plugin['name'] ) . '-demo' )
														),
														$buddyx_family_plugin['demo']
													)
													: '';
												?>
												<div class="buddyx-family-card<?php echo $buddyx_family_plugin_is_active ? ' is-active' : ''; ?>">
													<span class="buddyx-family-card-logo">
														<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/companions/' . $buddyx_family_plugin['logo'] ); ?>" width="40" height="40" alt="" />
													</span>
													<div class="buddyx-family-card-body">
														<div class="buddyx-family-card-head">
															<h4 class="buddyx-family-card-name"><?php echo esc_html( $buddyx_family_plugin['name'] ); ?></h4>
															<?php if ( $buddyx_family_plugin_is_active ) : ?>
																<span class="buddyx-family-card-pill buddyx-family-card-pill--active"><?php esc_html_e( 'Active', 'buddyx' ); ?></span>
															<?php endif; ?>
														</div>
														<p class="buddyx-family-card-desc"><?php echo esc_html( $buddyx_family_plugin['desc'] ); ?></p>
														<?php if ( ! $buddyx_family_plugin_is_active ) : ?>
															<div class="buddyx-family-card-links">
																<a class="buddyx-family-card-link" target="_blank" rel="noopener" href="<?php echo esc_url( $buddyx_family_plugin_url ); ?>">
																	<?php esc_html_e( 'View plugin', 'buddyx' ); ?>
																	<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 18l6-6-6-6"></path></svg>
																</a>
																<?php if ( $buddyx_family_plugin_demo ) : ?>
																	<a class="buddyx-family-card-link buddyx-family-card-link--demo" target="_blank" rel="noopener" href="<?php echo esc_url( $buddyx_family_plugin_demo ); ?>">
																		<?php esc_html_e( 'Live demo', 'buddyx' ); ?>
																		<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg>
																	</a>
																<?php endif; ?>
															</div>
														<?php endif; ?>
													</div>
												</div>
												<?php
											endforeach;
											?>
										</div>
									</div>
									<!-- End Wbcom Community Family -->

									<div class="buddyx-pro-row">
										<div class="buddyx-welcome-column buddyx-pro-section child-theme-section">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-icon.png' ); ?>" alt="<?php esc_attr_e( 'BuddyX Child Theme', 'buddyx' ); ?>">
											</span>
											<div class="buddyx-pro-theme-info">
												<h2 class="buddyx-tabs-title">
													<?php esc_html_e( 'BuddyX Child Theme', 'buddyx' ); ?>
												</h2>
												<p><?php esc_html_e( 'A child theme helps preserve customizations when the parent theme is updated, ensuring design consistency and security.', 'buddyx' ); ?></p>
												<a class="buddyx-box-item-link" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://github.com/wbcomdesigns/buddyx-child/releases/download/v1.0.0/buddyx-child.zip' ); ?>"><?php esc_html_e( 'Download Now', 'buddyx' ); ?></a>
											</div>
										</div>

										<div class="buddyx-welcome-column buddyx-pro-section">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-icon.png' ); ?>" alt="<?php esc_attr_e( 'BuddyX Pro', 'buddyx' ); ?>">
											</span>
											<div class="buddyx-pro-theme-info">
												<h2 class="buddyx-tabs-title">
													<?php esc_html_e( 'Get BuddyX Pro Theme', 'buddyx' ); ?>
												</h2>
												<p><?php esc_html_e( '#1 WordPress Social Network and Community Theme Powered by BuddyPress or BuddyBoss Platform.', 'buddyx' ); ?></p>
												<a class="buddyx-box-item-link" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/' ); ?>" target="_blank"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>

							</div>

							<!-- Welcome Page Sidebar -->
							<div class="buddyx-theme-sidebar">
								<div class="list-section-wrap-widgets">
									<h3><?php esc_html_e( 'Resources & Support', 'buddyx' ); ?></h3>
									<div class="buddyx-sidebar-list">
										<?php
										$buddyx_sidebar_links = array(
											array(
												'icon'   => '<path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"></path>',
												'name'   => __( 'Document', 'buddyx' ),
												'teaser' => __( 'Full docs to help you set up the theme.', 'buddyx' ),
												'href'   => 'https://buddyxtheme.com/docs/',
											),
											array(
												'icon'   => '<path d="M14 13.5H16.5L17.5 9.5H14V7.5C14 6.47062 14 5.5 16 5.5H17.5V2.1401C17.1743 2.09685 15.943 2 14.6429 2C11.9284 2 10 3.65686 10 6.69971V9.5H7V13.5H10V22H14V13.5Z"></path>',
												'name'   => __( 'Community', 'buddyx' ),
												'teaser' => __( 'Share your site and get help in our Facebook group.', 'buddyx' ),
												'href'   => 'https://www.facebook.com/groups/191523257634994',
											),
											array(
												'icon'   => '<path d="M3 3.9934C3 3.44476 3.44495 3 3.9934 3H20.0066C20.5552 3 21 3.44495 21 3.9934V20.0066C21 20.5552 20.5551 21 20.0066 21H3.9934C3.44476 21 3 20.5551 3 20.0066V3.9934ZM5 5V19H19V5H5ZM10.6219 8.41459L15.5008 11.6672C15.6846 11.7897 15.7343 12.0381 15.6117 12.2219C15.5824 12.2658 15.5447 12.3035 15.5008 12.3328L10.6219 15.5854C10.4381 15.708 10.1897 15.6583 10.0672 15.4745C10.0234 15.4088 10 15.3316 10 15.2526V8.74741C10 8.52649 10.1791 8.34741 10.4 8.34741C10.479 8.34741 10.5562 8.37078 10.6219 8.41459Z"></path>',
												'name'   => __( 'Videos', 'buddyx' ),
												'teaser' => __( 'Bite-sized tutorials for every step of setup.', 'buddyx' ),
												'href'   => 'https://www.youtube.com/playlist?list=PLlkJGdi68l-_vW61BtksEfeXVUNYeMnLd',
											),
											array(
												'icon'   => '<path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM5.76282 17H20V5H4V18.3851L5.76282 17ZM8 10H16V12H8V10Z"></path>',
												'name'   => __( 'Support', 'buddyx' ),
												'teaser' => __( 'Get in touch with our support team.', 'buddyx' ),
												'href'   => 'https://wbcomdesigns.com/support',
											),
											array(
												'icon'   => '<path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM4 18.3851L5.76282 17H20V5H4V18.3851ZM11 13H13V15H11V13ZM11 7H13V12H11V7Z"></path>',
												'name'   => __( 'Give us feedback', 'buddyx' ),
												'teaser' => __( 'Leave a 5-star review if BuddyX helped you.', 'buddyx' ),
												'href'   => 'https://wordpress.org/support/theme/buddyx/reviews/?rate=5#new-post',
											),
										);

										foreach ( $buddyx_sidebar_links as $buddyx_sidebar_link ) :
											?>
											<a class="buddyx-sidebar-row" href="<?php echo esc_url( $buddyx_sidebar_link['href'] ); ?>" target="_blank" rel="noopener">
												<span class="buddyx-sidebar-icon">
													<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><?php echo $buddyx_sidebar_link['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon markup curated inline above, not user input. ?></svg>
												</span>
												<span class="buddyx-sidebar-row-text">
													<strong><?php echo esc_html( $buddyx_sidebar_link['name'] ); ?></strong>
													<span><?php echo esc_html( $buddyx_sidebar_link['teaser'] ); ?></span>
												</span>
												<span class="buddyx-sidebar-row-chevron">
													<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m9 18 6-6-6-6"/></svg>
												</span>
											</a>
											<?php
										endforeach;
										?>
									</div>
								</div>

							</div>

							</div><!-- .buddyx-dashboard-body -->
						</div><!-- .tab1 -->

						<div role="tabpanel" class="get-pro-buddyx-theme" aria-labelledby="<?php esc_attr_e( 'tab2', 'buddyx' ); ?>" hidden>
							<div class="buddyx-tabs-content">

							<div class="buddyx-bundle-hero">
								<div class="buddyx-bundle-hero-icon">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-pro.png' ); ?>" alt="<?php esc_attr_e( 'BuddyX Pro Theme', 'buddyx' ); ?>">
								</div>
								<div class="buddyx-bundle-hero-body">
									<span class="buddyx-bundle-badge"><?php esc_html_e( '#1 WordPress community theme', 'buddyx' ); ?></span>
									<h2 class="buddyx-tabs-title"><?php esc_html_e( 'Get BuddyX Pro Theme', 'buddyx' ); ?></h2>
									<p class="buddyx-bundle-tagline"><?php esc_html_e( 'The #1 WordPress social network and community theme, powered by BuddyPress or BuddyBoss Platform. Everything in BuddyX, plus the customizer settings, layouts, and integrations compared below.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></a>
								</div>
							</div>

							<div class="buddyx-welcome-column buddyx-pro-highlights-column">
								<h2 class="buddyx-tabs-title"><?php esc_html_e( 'Why upgrade to BuddyX Pro', 'buddyx' ); ?></h2>
								<p class="buddyx-family-intro"><?php esc_html_e( 'BuddyX covers the essentials. Pro adds the controls that make a community site feel finished.', 'buddyx' ); ?></p>
								<div class="buddyx-pro-highlights">
								<?php
								$buddyx_pro_highlights = array(
									array(
										'icon'  => '<rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18"></path>',
										'title' => __( 'Advanced header builder', 'buddyx' ),
										'desc'  => __( 'Top bar, sticky header, multiple header layouts, a mega More menu, and menu effects.', 'buddyx' ),
									),
									array(
										'icon'  => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" x2="3" y1="12" y2="12"></line>',
										'title' => __( 'Branded login pages', 'buddyx' ),
										'desc'  => __( 'Theme the WordPress login: custom login and forgot forms, buttons, and login skins.', 'buddyx' ),
									),
									array(
										'icon'  => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
										'title' => __( 'Full BuddyPress control', 'buddyx' ),
										'desc'  => __( 'Activity share and reactions, plus complete member and group directory, header, and navigation layouts.', 'buddyx' ),
									),
									array(
										'icon'  => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path>',
										'title' => __( 'WooCommerce shop styling', 'buddyx' ),
										'desc'  => __( 'Product listing styles, products per page, a filter button, and sale-badge styles.', 'buddyx' ),
									),
								);
								foreach ( $buddyx_pro_highlights as $buddyx_pro_highlight ) :
								?>
								<div class="buddyx-highlight-card">
									<span class="buddyx-family-card-logo">
										<svg width="28" height="28" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?php echo $buddyx_pro_highlight['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Curated inline icon markup, not user input. ?></svg>
									</span>
									<h4 class="buddyx-family-card-name"><?php echo esc_html( $buddyx_pro_highlight['title'] ); ?></h4>
									<p class="buddyx-family-card-desc"><?php echo esc_html( $buddyx_pro_highlight['desc'] ); ?></p>
								</div>
								<?php
								endforeach;
								?>
								</div>
							</div>

							<div class="buddyx-welcome-column buddyx-features-column">
								<h2 class="buddyx-tabs-title"><?php esc_html_e( 'Compare Features Of BuddyX With BuddyX Pro', 'buddyx' ); ?></h2>
								<p class="buddyx-family-intro"><?php esc_html_e( 'Take your time and compare every feature.', 'buddyx' ); ?></p>

								<div class="buddyx-row features">
									<div class="buddyx-features-table-wrap">
										<div class="buddyx-features-table-inner">
										<table class="buddyx-features-table">
											<thead>
												<tr>
													<th style="text-align: left"><h3><?php esc_html_e( 'Features', 'buddyx' ); ?></h3></th>
													<th style="text-align: center"><h3><?php esc_html_e( 'BuddyX', 'buddyx' ); ?></h3></th>
													<th style="text-align: center"><h3><?php esc_html_e( 'BuddyX Pro', 'buddyx' ); ?></h3></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Layout', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Site Layout', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Content Layout Width', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Sidebar Width', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Global Border Radius', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Buttons Border Radius', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Form Border Radius', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Page Mapping', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Sign-in Popup | Register Form Fields', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Scroll Top', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Site Loader', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Loading Text', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Typography', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Site Title Settings', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Headings', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Menu', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Body', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Google Fonts', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Font Size (PX/EM)', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Text Transform', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Line Height', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Letter Spacing', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Header', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Top Bar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Sticky Header', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Header Layout', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Header Menu Position', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Menu Effects', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'More Menu', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Site Search Style', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Login/Register Button Style', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Background Color', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Site Search', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Site Cart', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'BuddyPress Components', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Side Panel', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Side Panel', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Sub Header', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Customize Background', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Content Typography', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Breadcrumbs', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Skin Colors', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Dark Mode', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Custom Colors', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Body Background Color', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Theme Color', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Link Color', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Primary Header', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Side Panel', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Button Background Color / Hover', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Button Text Color / Hover', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Button Border Color / Hover', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Site Blog', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'List Layout', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Grid Layout', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Masonry Layout', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Blog Layout Style', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Post Per Row', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Site Sidebar', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'No Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Left Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Right Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Both Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'BuddyPress Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'WooCommerce Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'bbPress Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'LearnDash Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Sticky Sidebar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'WP Login', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Customize Your Logo Section', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Choose WP Login Theme', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Customize Login Form', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Customize Forget Form', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Customize Button', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Site Footer', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Footer Section', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Copyright Section', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'BuddyPress', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Activity Control', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'BuddyPress Activity Share', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Activity Reaction', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Activity Load More', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Members Multiple Directory Layout', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Members Default Cover Background', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Groups Multiple Directory View', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Group Default Cover Background', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Member Multiple Header Layout', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Member Primary Navigation Layout', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Group Multiple Header Layout', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Group Primary Navigation Style', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>

												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'WooCommerce', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Product Listing Style', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Shop Products Per Page', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Display Filter Button', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Sale Badge Style', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td class="buddyx-main-feature"><?php esc_html_e( 'Integrations', 'buddyx' ); ?></td>
													<td class="buddyx-main-feature"></td>
													<td class="buddyx-main-feature"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'BuddyPress', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'BuddyBoss Platform', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'WooCommerce', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'LearnDash', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'LearnPress', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'LifterLMS', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'TutorLMS', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'bbPress', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Dokan', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'WC Vendors', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'GamiPress', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Paid Memberships', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'The Event Calendar', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
											</tbody>
										</table>
									</div><!-- .buddyx-features-table-inner -->
								</div><!-- .buddyx-features-table-wrap -->
							</div><!-- .features -->
							</div><!-- .buddyx-features-column -->
							</div><!-- .buddyx-tabs-content -->
						</div><!-- .tab2 -->

						<div role="tabpanel" class="bp-plugin-addons" aria-labelledby="<?php esc_attr_e( 'tab3', 'buddyx' ); ?>" hidden>
							<div class="buddyx-tabs-content buddyx-addon-body">

								<div class="buddyx-bundle-hero">
									<div class="buddyx-bundle-hero-icon">
										<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/recommended-plugins.png' ); ?>" alt="<?php esc_attr_e( 'BuddyPress Community Bundle', 'buddyx' ); ?>">
									</div>
									<div class="buddyx-bundle-hero-body">
										<span class="buddyx-bundle-badge"><?php esc_html_e( '26 premium plugins - one bundle', 'buddyx' ); ?></span>
										<h2 class="buddyx-tabs-title"><?php esc_html_e( 'BuddyPress Community Bundle', 'buddyx' ); ?></h2>
										<p class="buddyx-bundle-tagline"><?php esc_html_e( 'Instead of buying add-ons one by one, get every premium BuddyPress plugin we make in a single bundle - moderation at scale, richer member profiles, better activity feeds, privacy controls and more. One purchase, all future updates.', 'buddyx' ); ?></p>
										<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-community-bundle/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get the Community Bundle', 'buddyx' ); ?></a>
									</div>
								</div>

								<div class="buddyx-bundle-highlights">
									<h3 class="buddyx-bundle-highlights-title"><?php esc_html_e( "What's inside the bundle", 'buddyx' ); ?></h3>
									<div class="buddyx-family-grid">
									<?php
									$buddyx_bundle_plugins = array(
										array(
											'name' => __( 'BuddyPress Moderation Pro', 'buddyx' ),
											'slug' => 'buddypress-moderation-pro',
											'desc' => __( 'Member-led reports, queues, automated warnings, and soft bans.', 'buddyx' ),
											'icon' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path>',
										),
										array(
											'name' => __( 'BuddyPress Profanity', 'buddyx' ),
											'slug' => 'buddypress-profanity',
											'desc' => __( 'Auto-blurs flagged words and flags them for admin review.', 'buddyx' ),
											'icon' => '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" x2="22" y1="2" y2="22"></line>',
										),
										array(
											'name' => __( 'BuddyPress Auto Friends', 'buddyx' ),
											'slug' => 'buddypress-auto-friends',
											'desc' => __( 'New members auto-connect with role-matched community members.', 'buddyx' ),
											'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" x2="19" y1="8" y2="14"></line><line x1="22" x2="16" y1="11" y2="11"></line>',
										),
										array(
											'name' => __( 'BuddyPress Profile Pro', 'buddyx' ),
											'slug' => 'buddypress-profile-pro',
											'desc' => __( 'Custom fields rendered as profile tabs and groups.', 'buddyx' ),
											'icon' => '<rect width="18" height="18" x="3" y="3" rx="2"></rect><circle cx="12" cy="10" r="3"></circle><path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"></path>',
										),
										array(
											'name' => __( 'BuddyPress Business Profile', 'buddyx' ),
											'slug' => 'buddypress-business-profile',
											'desc' => __( 'Turn member profiles into business-card layouts with CTAs.', 'buddyx' ),
											'icon' => '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
										),
										array(
											'name' => __( 'BuddyPress Statistics', 'buddyx' ),
											'slug' => 'buddypress-statistics',
											'desc' => __( 'Posts, followers, engagement, and growth on every profile.', 'buddyx' ),
											'icon' => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline>',
										),
										array(
											'name' => __( 'BuddyPress Member Blog Pro', 'buddyx' ),
											'slug' => 'buddypress-member-blog-pro',
											'desc' => __( 'Every member gets their own blog area inside the community.', 'buddyx' ),
											'icon' => '<path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>',
										),
										array(
											'name' => __( 'Who Viewed My Profile', 'buddyx' ),
											'slug' => 'who-viewed-my-profile-buddypress',
											'desc' => __( 'Visitor list with timestamps to bring members back.', 'buddyx' ),
											'icon' => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle>',
										),
										array(
											'name' => __( 'BuddyPress Hashtags', 'buddyx' ),
											'slug' => 'buddypress-hashtags',
											'desc' => __( 'Activity posts link to a trending tag page indexable by Google.', 'buddyx' ),
											'icon' => '<line x1="4" x2="20" y1="9" y2="9"></line><line x1="4" x2="20" y1="15" y2="15"></line><line x1="10" x2="8" y1="3" y2="21"></line><line x1="16" x2="14" y1="3" y2="21"></line>',
										),
										array(
											'name' => __( 'BuddyPress Schedule Activity', 'buddyx' ),
											'slug' => 'buddypress-schedule-activity',
											'desc' => __( 'Compose posts with a future publish date and time.', 'buddyx' ),
											'icon' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
										),
										array(
											'name' => __( 'BuddyPress Edit Activity', 'buddyx' ),
											'slug' => 'buddypress-edit-activity',
											'desc' => __( 'Inline edit and delete for member posts after publishing.', 'buddyx' ),
											'icon' => '<path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path>',
										),
										array(
											'name' => __( 'BuddyPress Newsfeed', 'buddyx' ),
											'slug' => 'buddypress-newsfeed',
											'desc' => __( 'Weekly email digest of the best activity each member missed.', 'buddyx' ),
											'icon' => '<rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>',
										),
										array(
											'name' => __( 'BuddyPress Quotes', 'buddyx' ),
											'slug' => 'buddypress-quotes',
											'desc' => __( 'Quote-block activity format with attribution and source link.', 'buddyx' ),
											'icon' => '<path d="M17 6H3"></path><path d="M21 12H8"></path><path d="M21 18H8"></path><path d="M3 12v6"></path>',
										),
										array(
											'name' => __( 'BuddyPress Sticky Post', 'buddyx' ),
											'slug' => 'buddypress-sticky-post',
											'desc' => __( 'Pin announcements above the feed for every member.', 'buddyx' ),
											'icon' => '<path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>',
										),
										array(
											'name' => __( 'BuddyPress Status', 'buddyx' ),
											'slug' => 'buddypress-status',
											'desc' => __( 'Member status pills, like Slack presence for communities.', 'buddyx' ),
											'icon' => '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle>',
										),
										array(
											'name' => __( 'BuddyPress Giphy', 'buddyx' ),
											'slug' => 'buddypress-giphy',
											'desc' => __( 'Embed GIFs in activity posts from the Giphy library.', 'buddyx' ),
											'icon' => '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><circle cx="9" cy="9" r="2"></circle><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>',
										),
										array(
											'name' => __( 'BuddyPress Anonymous Activity', 'buddyx' ),
											'slug' => 'buddypress-anonymous-activity',
											'desc' => __( 'Members post anonymously when surfacing sensitive topics.', 'buddyx' ),
											'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="17" x2="22" y1="8" y2="13"></line><line x1="22" x2="17" y1="8" y2="13"></line>',
										),
										array(
											'name' => __( 'BuddyPress Friend and Follow Suggestion', 'buddyx' ),
											'slug' => 'buddypress-friend-follow-suggestion',
											'desc' => __( 'Sidebar widget suggesting members to connect with.', 'buddyx' ),
											'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
										),
										array(
											'name' => __( 'BuddyPress Check-ins Pro', 'buddyx' ),
											'slug' => 'buddypress-check-ins-pro',
											'desc' => __( 'Member check-ins with map view and activity stream.', 'buddyx' ),
											'icon' => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle>',
										),
										array(
											'name' => __( 'BuddyPress Private Community Pro', 'buddyx' ),
											'slug' => 'buddypress-private-community-pro',
											'desc' => __( 'Site-wide or per-area members-only access control.', 'buddyx' ),
											'icon' => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>',
										),
										array(
											'name' => __( 'Advanced Username Manager', 'buddyx' ),
											'slug' => 'advanced-username-manager',
											'desc' => __( 'Bulk rename usernames and reserve sensitive words.', 'buddyx' ),
											'icon' => '<circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"></path>',
										),
										array(
											'name' => __( 'Restrict Email Domain for WordPress', 'buddyx' ),
											'slug' => 'restrict-email-domain-wordpress',
											'desc' => __( 'Limit signups to specific email domains.', 'buddyx' ),
											'icon' => '<path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8"></path><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path><path d="m16 19 2 2 4-4"></path>',
										),
										array(
											'name' => __( 'BuddyPress Polls', 'buddyx' ),
											'slug' => 'buddypress-polls',
											'desc' => __( 'Inline polls in the activity feed with live vote counts.', 'buddyx' ),
											'icon' => '<path d="M3 3v18h18"></path><path d="M18 17V9"></path><path d="M13 17V5"></path><path d="M8 17v-3"></path>',
										),
										array(
											'name' => __( 'WP Stories', 'buddyx' ),
											'slug' => 'wp-stories',
											'desc' => __( 'Instagram-style story rings above the feed for daily moments.', 'buddyx' ),
											'icon' => '<circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon>',
										),
										array(
											'name' => __( 'Shortcodes for BuddyPress Pro', 'buddyx' ),
											'slug' => 'shortcodes-for-buddypress-pro',
											'desc' => __( 'Surface members, groups, and activity on any page or builder.', 'buddyx' ),
											'icon' => '<polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline>',
										),
										array(
											'name' => __( 'BuddyVendor', 'buddyx' ),
											'slug' => 'buddyvendor',
											'desc' => __( 'Member commerce: each profile gets a Shop tab with products.', 'buddyx' ),
											'icon' => '<path d="M2 7h20"></path><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><path d="m2 7 3-5h14l3 5"></path><path d="M12 22V12"></path>',
										),
									);
									foreach ( $buddyx_bundle_plugins as $buddyx_bundle_plugin ) :
										$buddyx_bundle_plugin_url = add_query_arg(
											array(
												'utm_source'   => 'buddyx',
												'utm_medium'   => 'welcome',
												'utm_campaign' => 'buddyx-theme',
												'utm_content'  => sanitize_title( $buddyx_bundle_plugin['name'] ),
											),
											'https://wbcomdesigns.com/downloads/' . $buddyx_bundle_plugin['slug'] . '/'
										);
										?>
										<div class="buddyx-family-card">
											<span class="buddyx-family-card-logo">
												<svg width="28" height="28" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?php echo $buddyx_bundle_plugin['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Curated inline icon markup, not user input. ?></svg>
											</span>
											<div class="buddyx-family-card-body">
												<div class="buddyx-family-card-head">
													<h4 class="buddyx-family-card-name"><?php echo esc_html( $buddyx_bundle_plugin['name'] ); ?></h4>
												</div>
												<p class="buddyx-family-card-desc"><?php echo esc_html( $buddyx_bundle_plugin['desc'] ); ?></p>
												<div class="buddyx-family-card-links">
													<a class="buddyx-family-card-link" target="_blank" rel="noopener" href="<?php echo esc_url( $buddyx_bundle_plugin_url ); ?>">
														<?php esc_html_e( 'View plugin', 'buddyx' ); ?>
														<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 18l6-6-6-6"></path></svg>
													</a>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
									</div>
									<p class="buddyx-bundle-foot">
										<?php esc_html_e( 'Every add-on above is included in the bundle and kept up to date.', 'buddyx' ); ?>
										<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-community-bundle/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'See everything in the bundle', 'buddyx' ); ?></a>
									</p>
								</div>
							</div><!-- .buddyx-addon-body -->
						</div><!-- .tab3 -->
					</section>

				</div><!-- .buddyx-dashboard-tabs -->
			</div><!-- .welcome-inner-wrap -->
		</div><!-- .wrap -->

		<?php
	}
}
