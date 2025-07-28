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

	public function submenu_page_callback() {
		// instence of tgmpa to check plugins are installed.
		$tgmpa = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		?>
		<div class="buddyx-top-banner-wrapper">
			<div class="buddyx-top-banner">				
				<h1 class="buddyx-banner-title">
					<?php esc_html_e( 'Welcome to BuddyX Theme', 'buddyx' ); ?>
				</h1>
				<span class="buddyx-theme-version">
					<?php esc_html_e( 'Version', 'buddyx' ); ?> <?php echo esc_html( wp_get_theme( get_template() )->get( 'Version' ) ); ?>
				</span>
				<div class="buddyx-banner-description">
					<p><?php esc_html_e( 'Thank you for purchasing the BuddyX Theme!', 'buddyx' ); ?></p>
					<p><?php esc_html_e( 'Below, you will find information on setting up the theme to start using it!', 'buddyx' ); ?></p>
				</div>
			</div>
		</div>
		<div class="wrap">
			<div class="welcome-inner-wrap">
				<div class="buddyx-dashboard-tabs">

					<section class="content">
						<div class="tabs">
						<div role="tablist" aria-label="<?php esc_attr_e( 'Programming Languages', 'buddyx' ); ?>">
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
										<div class="buddyx-box-item">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashicons-format-image.png' ); ?>" alt="BuddyX Logo">
											</span>
											<h4 class="buddyx-box-item-name"><?php esc_html_e( 'Upload Logo', 'buddyx' ); ?></h4>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=custom_logo&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Go to option', 'buddyx' ); ?></a>
										</div>

										<div class="buddyx-box-item">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashicons-admin-appearance.png' ); ?>" alt="BuddyX Colors">
											</span>
											<h4 class="buddyx-box-item-name"><?php esc_html_e( 'Set Colors', 'buddyx' ); ?></h4>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=body_background_color&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Go to option', 'buddyx' ); ?></a>
										</div>

										<div class="buddyx-box-item">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashicons-layout.png' ); ?>" alt="BuddyX Layout">
											</span>
											<h4 class="buddyx-box-item-name"><?php esc_html_e( 'Site Layout', 'buddyx' ); ?></h4>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=site_layout&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Go to option', 'buddyx' ); ?></a>
										</div>

										<div class="buddyx-box-item">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashicons-editor-paragraph.png' ); ?>" alt="BuddyX Typography">
											</span>
											<h4 class="buddyx-box-item-name"><?php esc_html_e( 'Set Typography	', 'buddyx' ); ?></h4>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=typography_panel&return=' . admin_url( 'admin.php?page=buddyx-options' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Go to option', 'buddyx' ); ?></a>
										</div>

										<div class="buddyx-box-item">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashicons-admin-customizer.png' ); ?>" alt="BuddyX Header">
											</span>
											<h4 class="buddyx-box-item-name"><?php esc_html_e( 'Header Layout', 'buddyx' ); ?></h4>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=site_header_section&return=' . admin_url( 'admin.php?page=buddyx-options' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Go to option', 'buddyx' ); ?></a>
										</div>

										<div class="buddyx-box-item">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashicons-blog-layout.png' ); ?>" alt="BuddyX Blog">
											</span>
											<h4 class="buddyx-box-item-name"><?php esc_html_e( 'Site Blog', 'buddyx' ); ?></h4>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=site_blog_section&return=' . admin_url( 'admin.php?page=buddyx-welcome' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Go to option', 'buddyx' ); ?></a>
										</div>
									</div>
									</div>

									<!-- End Customizer Options -->

									<!-- BuddyX Theme Importer Plugin -->
									<div class="buddyx-importer-wrapper">
										<div class="buddyx-welcome-column">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-demo-installer.png' ); ?>" alt="BuddyX Installer">
											</span>
											<h2 class="buddyx-tabs-title">
												<?php esc_html_e( 'Demo Installation', 'buddyx' ); ?>												
											</h2>
											<p><?php esc_html_e( 'The theme comes with one-click demo installation support. Try the demo and install it on your WordPress BuddyPress site.', 'buddyx' ); ?></p>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/docs/buddyx-free-theme/getting-started-buddyx-free-theme/demo-installation-2/' ); ?>" target="_blank"><?php esc_html_e( 'Demo Installation', 'buddyx' ); ?></a>
										</div>										
										<div class="buddyx-welcome-column">
											<span class="buddyx-box-item-icon">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/recommended-plugins.png' ); ?>" alt="BuddyX Installer">
											</span>
											<h2 class="buddyx-tabs-title">
												<?php esc_html_e( 'Install Recommended Plugins', 'buddyx' ); ?>											
											</h2>
											<p><?php esc_html_e( 'Enhancing your website functionality with WordPress plugins is easy. You can install, activate, and begin using WordPress plugins in minutes.', 'buddyx' ); ?></p>
											<?php if ( ! $tgmpa->is_tgmpa_complete() ) { ?>
												<a class="buddyx-box-item-link" href="<?php echo esc_url( admin_url() . 'admin.php?page=tgmpa-install-plugins' ); ?>" target="_blank"><?php esc_html_e( 'Install now', 'buddyx' ); ?></a>
											<?php } else { ?>
												<a class="buddyx-box-item-link all-plugin-installed" href="javascript:void(0)"><?php esc_html_e( 'Installed', 'buddyx' ); ?></a>
											<?php } ?>
										</div>
									</div>

									<div class="buddyx-welcome-column buddyx-pro-section child-theme-section">
										<span class="buddyx-box-item-icon">
											<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/child-theme.png' ); ?>" alt="child theme">
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
											<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-pro.png' ); ?>" alt="BuddyX Pro">
										</span>
										<div class="buddyx-pro-theme-info">
											<h2 class="buddyx-tabs-title">
												<?php esc_html_e( 'Get BuddyX Pro Theme', 'buddyx' ); ?>												
											</h2>
											<p><?php esc_html_e( '#1 WordPress Social Network and Community Theme Powered by BuddyPress or BuddyBoss Platform.', 'buddyx' ); ?></p>
											<a class="buddyx-box-item-link" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/' ); ?>" target="_blank"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></a>
										</div>
									</div>

									<!-- End BuddyX Theme Importer Plugin -->
							</div>

							<!-- Welcome Page Sidebar -->
							<div class="buddyx-theme-sidebar">
								<div class="list-section-wrap-widgets">
									<h3><?php esc_html_e( 'Document', 'buddyx' ); ?>
										<svg width="26" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"></path></svg>
									</h3>
									<div class="buddyx-quick-setting-section">
									<p><?php esc_html_e( 'We have created foolproof documentation for you. It will help you to understand how our plugin works.', 'buddyx' ); ?></p>
									<p>
										<a href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/doc_category/buddyx-free-theme/' ); ?>" class="buddyx-button" target="_blank"><?php esc_html_e( 'Go to Documentation', 'buddyx' ); ?></a>
									</p>
									</div>
								</div>
								<div class="list-section-wrap-widgets">
									<h3><?php esc_html_e( 'Community', 'buddyx' ); ?>
										<svg width="26" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 13.5H16.5L17.5 9.5H14V7.5C14 6.47062 14 5.5 16 5.5H17.5V2.1401C17.1743 2.09685 15.943 2 14.6429 2C11.9284 2 10 3.65686 10 6.69971V9.5H7V13.5H10V22H14V13.5Z"></path></svg>
									</h3>
									<div class="buddyx-quick-setting-section">
									<p><?php esc_html_e( 'Join our community! Share your site, ask a question, and help others.', 'buddyx' ); ?></p>
									<p>
										<a href="<?php echo esc_url( 'https://www.facebook.com/groups/191523257634994' ); ?>" class="buddyx-button" target="_blank"><?php esc_html_e( 'Go to Facebook Group', 'buddyx' ); ?></a>
									</p>
									</div>
								</div>
								<div class="list-section-wrap-widgets">
									<h3><?php esc_html_e( 'Videos', 'buddyx' ); ?>
										<svg width="26" height="26" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3.9934C3 3.44476 3.44495 3 3.9934 3H20.0066C20.5552 3 21 3.44495 21 3.9934V20.0066C21 20.5552 20.5551 21 20.0066 21H3.9934C3.44476 21 3 20.5551 3 20.0066V3.9934ZM5 5V19H19V5H5ZM10.6219 8.41459L15.5008 11.6672C15.6846 11.7897 15.7343 12.0381 15.6117 12.2219C15.5824 12.2658 15.5447 12.3035 15.5008 12.3328L10.6219 15.5854C10.4381 15.708 10.1897 15.6583 10.0672 15.4745C10.0234 15.4088 10 15.3316 10 15.2526V8.74741C10 8.52649 10.1791 8.34741 10.4 8.34741C10.479 8.34741 10.5562 8.37078 10.6219 8.41459Z"></path></svg>
									</h3>
									<div class="buddyx-quick-setting-section">
									<p><?php esc_html_e( 'Welcome to our WordPress theme video series! Whether you\'re a newbie or a seasoned website creator, our bite-sized tutorials are packed with tips, tricks, and hacks to supercharge your WordPress journey.', 'buddyx' ); ?></p>
									<p>
										<a href="<?php echo esc_url( 'https://www.youtube.com/playlist?list=PLlkJGdi68l-_vW61BtksEfeXVUNYeMnLd' ); ?>" class="buddyx-button" target="_blank"><?php esc_html_e( 'Watch Now', 'buddyx' ); ?></a>
									</p>
									</div>
								</div>
								<div class="list-section-wrap-widgets">
									<h3><?php esc_html_e( 'Support', 'buddyx' ); ?>
										<svg width="26" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM5.76282 17H20V5H4V18.3851L5.76282 17ZM8 10H16V12H8V10Z"></path></svg>
									</h3>
									<div class="buddyx-quick-setting-section">
									<p><?php esc_html_e( 'Have a question, we are happy to help! Get in touch with our support team.', 'buddyx' ); ?></p>
									<p>
										<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>" class="buddyx-button" target="_blank"><?php esc_html_e( 'Submit a Ticket', 'buddyx' ); ?></a>
									</p>
									</div>
								</div>
								<div class="list-section-wrap-widgets">
									<h3><?php esc_html_e( 'Give us feedback', 'buddyx' ); ?>
										<svg width="26" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM4 18.3851L5.76282 17H20V5H4V18.3851ZM11 13H13V15H11V13ZM11 7H13V12H11V7Z"></path></svg>
									</h3>
									<div class="buddyx-quick-setting-section">
									<p><?php esc_html_e( 'Remember to pay attention to your rate! Your 5-star review will encourage us so much!', 'buddyx' ); ?></p>
									<p>
										<a href="<?php echo esc_url( 'https://wordpress.org/support/theme/buddyx/reviews/?rate=5#new-post' ); ?>" class="buddyx-button" target="_blank"><?php esc_html_e( 'Write a Review ', 'buddyx' ); ?></a>
									</p>
									</div>
								</div>

							</div>

							</div><!-- .buddyx-dashboard-body -->
						</div><!-- .tab1 -->						

						<div role="tabpanel" class="get-pro-buddyx-theme" aria-labelledby="<?php esc_attr_e( 'tab2', 'buddyx' ); ?>" hidden>							

							<div class="buddyx-welcome-column">
							<div class="buddyx-welcome-column buddyx-pro-section">
								<div class="buddyx-pro-theme-info">
									<h2 class="buddyx-tabs-title">
										<?php esc_html_e( 'Get BuddyX Pro Theme', 'buddyx' ); ?>												
									</h2>
									<p><?php esc_html_e( '#1 WordPress Social Network and Community Theme Powered by BuddyPress or BuddyBoss Platform', 'buddyx' ); ?></p>
									<a class="buddyx-box-item-link" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/' ); ?>" target="_blank"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></a>
								</div>
							</div>

							<div class="buddyxpro-features-compare-content buddyx-text-center">
								<h2 class="buddyx-feature-title"><?php esc_html_e( 'Compare Features Of BuddyX With BuddyX Pro', 'buddyx' ); ?></h2>
								<p class="buddyx-col-content"><?php esc_html_e( 'Take Your Time & Compare Every Feature', 'buddyx' ); ?></p>
							</div><!-- .video-tutorial button-->

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
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Buttons Border Radius', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></span></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Form Border Radius', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
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
													<td style="text-align: center"><span class="icon-close"></span></td>
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
													<td style="text-align: center"><span class="icon-close"></span></td>
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
													<td style="text-align: center"><span class="icon-close"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Custom Colors', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></td>
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
													<td style="text-align: center"><span class="icon-close"></td>
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
													<td style="text-align: center"><span class="icon-close"></td>
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
							</div>
						</div><!-- .tab2 -->

						<div role="tabpanel" class="bp-plugin-addons" aria-labelledby="<?php esc_attr_e( 'tab3', 'buddyx' ); ?>" hidden>
							<div class="buddyx-tabs-content buddyx-addon-body">

								<div class="buddyx-welcome-column buddyx-pro-section">
									<span class="buddyx-box-item-icon">
										<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/recommended-plugins.png' ); ?>" alt="BuddyX Installer">
									</span>
									<div class="buddyx-pro-theme-info">
										<h2 class="buddyx-tabs-title">
											<?php esc_html_e( 'BuddyPress Premium add-ons', 'buddyx' ); ?>												
										</h2>
										<p><?php esc_html_e( 'Extend your social community website with our premium add-ons for BuddyPress.', 'buddyx' ); ?></p>
										<a class="buddyx-box-item-link" href="<?php echo esc_url( 'https://wbcomdesigns.com/plugins/premium-buddypress-add-ons/' ); ?>" target="_blank"><?php esc_html_e( 'View More Addons', 'buddyx' ); ?></a>
									</div>
								</div>

								<div class="buddyx-row addons">
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Business Profile', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Business Pages enable your members to connect with their customers, fans, and followers within your social community.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-business-profile/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Ideapush Integration', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'The BuddyPress IdeaPush Integration is an addon of IdeaPush that helps you manage your created ideas through the BuddyPress Profile.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-ideapush-integration/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Contact Me', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Contact Me displays a contact form on a member\'s profiles, allowing logged-in and non-logged-in visitors can be in touch with our community members.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-contact-me/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'WP Stories', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Social Stories offer a more personal way to interact with your audience.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/wp-stories/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Who Viewed My Profile', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'The Who Viewed My Profile Plugin helps you to know about your profile visitors. It displays the count of the profile views on the member profile header...', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/who-viewed-my-profile-buddypress/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Member Blog Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BP Member Blog Pro plugin provides each of your site users their own writing environment with a fantastic user experience.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-member-blog-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyVendor', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'With the BuddyVendor plugin, you can quickly turn your BuddyPress community into a social marketplace.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyvendor/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Statistics', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Stats plugin creates an activity log of everything that happens on your BuddyPress-powered community site...', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-statistics/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Check-Ins Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow community members and groups to post updates along with selecting their current location.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-check-ins-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Friend & Follow Suggestion', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Friends and Follow suggestions plugin assists you with improving your BuddyPress or BuddyBoss Platform-based community.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-friend-follow-suggestion/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Giphy', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Attach GIF's into your BuddyPress activity, comments, and messages.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-giphy/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Shortcodes For BuddyPress Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Add and Customize the BuddyPress components on any of the WordPress pages/posts using elementor widgets.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/shortcodes-for-buddypress-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Sticky Post', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Make your posts sticky on BuddyPress by pinning site-wide and group activities on the top of the community wall.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-sticky-post/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Quotes', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow BuddyPress users to post content with attractive background colors and beautiful images.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-quotes/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Auto Friends', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Add selected community members as common friends for all members.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-auto-friends/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Newsfeed', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Orgnanize all the BuddyPress activity-streams just like Facebook newsfeed.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-newsfeed/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Status', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to add status on their profile and give their reactions to all the activity updates.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-status/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Hashtags', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow users to add multi-language hashtag links on BuddyPress community website.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-hashtags/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Moderation Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to flag inappropriate content on your BuddyPress community site.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-moderation-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Private Community Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Lockdown the BuddyPress components to make your member's profile safe.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-private-community-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Polls', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow users to create and respond to polls inside the BuddyPress activity or in groups.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-polls/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Profanity', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Specify a list of bad words to control the content in your BuddyPress community.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profanity/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Resume Manager', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to create and display resumes on BuddyPress right from their profile page.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-resume-manager/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Profile Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Add repeater fields or group of fields to the front-end forms on the member's profile.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profile-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
								</div><!-- .addons -->

							</div><!-- .buddyx-addon-body -->
						</div><!-- .tab3 -->
					</section>

				</div><!-- .buddyx-dashboard-tabs -->
			</div><!-- .welcome-inner-wrap -->
		</div><!-- .wrap -->
		
		<?php
	}
}
