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
									<h3><?php esc_html_e( 'Document', 'buddyx' ); ?> <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M12.9399 22.5202H15.0133H6.71994C6.17006 22.5202 5.6427 22.3024 5.25387 21.9146C4.86505 21.5269 4.64661 21.001 4.64661 20.4527V5.97968C4.64661 5.43132 4.86505 4.90543 5.25387 4.51769C5.6427 4.12994 6.17006 3.91211 6.71994 3.91211H19.1599C19.7098 3.91211 20.2372 4.12994 20.626 4.51769C21.0148 4.90543 21.2333 5.43132 21.2333 5.97968V14.2499" stroke="#1F2229" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M18.1233 18.3851C18.1233 18.6593 18.2325 18.9222 18.4269 19.1161C18.6213 19.31 18.885 19.4189 19.16 19.4189H22.27C22.5449 19.4189 22.8086 19.31 23.003 19.1161C23.1974 18.9222 23.3066 18.6593 23.3066 18.3851C23.3066 18.1109 23.1974 17.848 23.003 17.6541C22.8086 17.4602 22.5449 17.3513 22.27 17.3513H19.16C18.885 17.3513 18.6213 17.4602 18.4269 17.6541C18.2325 17.848 18.1233 18.1109 18.1233 18.3851ZM23.3066 22.5202C23.3066 22.2461 23.1974 21.9831 23.003 21.7892C22.8086 21.5954 22.5449 21.4865 22.27 21.4865H19.16C18.885 21.4865 18.6213 21.5954 18.4269 21.7892C18.2325 21.9831 18.1233 22.2461 18.1233 22.5202C18.1233 22.7944 18.2325 23.0574 18.4269 23.2512C18.6213 23.4451 18.885 23.554 19.16 23.554H22.27C22.5449 23.554 22.8086 23.4451 23.003 23.2512C23.1974 23.0574 23.3066 22.7944 23.3066 22.5202Z" fill="#1F2229"></path>
									<path d="M9.82996 15.8007L12.658 9.69102C12.683 9.63746 12.7228 9.59212 12.7727 9.56036C12.8227 9.52859 12.8807 9.51172 12.94 9.51172C12.9992 9.51172 13.0572 9.52859 13.1072 9.56036C13.1571 9.59212 13.1969 9.63746 13.2219 9.69102L16.05 15.8007" stroke="#1F2229" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									<path d="M10.452 13.2162H15.428V15.077H10.452V13.2162Z" fill="#1F2229"></path>
									</svg>
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
									<svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g clip-path="url(#clip0_44_2454)">
										<path d="M25.5366 12C25.5366 5.3725 19.9671 0 13.0966 0C6.22611 0 0.656616 5.3725 0.656616 12C0.656616 17.9895 5.20551 22.954 11.1529 23.854V15.469H7.99414V12H11.1529V9.356C11.1529 6.349 13.0101 4.6875 15.8516 4.6875C17.2122 4.6875 18.636 4.922 18.636 4.922V7.875H17.0676C15.5224 7.875 15.0404 8.8 15.0404 9.75V12H18.4904L17.9389 15.469H15.0404V23.854C20.9877 22.954 25.5366 17.989 25.5366 12Z" fill="black"></path>
									</g>
									<defs>
										<clipPath id="clip0_44_2454">
										<rect width="24.88" height="24" fill="white" transform="translate(0.656616)"></rect>
										</clipPath>
									</defs>
									</svg>
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
									<svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19.5617 7C19.7904 5.69523 18.7863 4.5 17.4617 4.5H6.53788C5.21323 4.5 4.20922 5.69523 4.43784 7" stroke="#1C274C" stroke-width="1.5"/>
									<path d="M17.4999 4.5C17.5283 4.24092 17.5425 4.11135 17.5427 4.00435C17.545 2.98072 16.7739 2.12064 15.7561 2.01142C15.6497 2 15.5194 2 15.2588 2H8.74099C8.48035 2 8.35002 2 8.2436undefined1142C7.22584 2.12064 6.45481 2.98072 6.45704 4.00434C6.45727 4.11135 6.47146 4.2409 6.49983 4.5" stroke="#1C274C" stroke-width="1.5"/>
									<path d="M21.1935 16.793C20.8437 19.2739 20.6689 20.5143 19.7717 21.2572C18.8745 22 17.5512 22 14.9046 22H9.09536C6.44881 22 5.12553 22 4.22834 21.2572C3.33115 20.5143 3.15626 19.2739 2.80648 16.793L2.38351 13.793C1.93748 10.6294 1.71447 9.04765 2.66232 8.02383C3.61017 7 5.29758 7 8.67239 7H15.3276C18.7024 7 20.3898 7 21.3377 8.02383C22.0865 8.83268 22.1045 9.98979 21.8592 12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
									<path d="M14.5812 13.6159C15.1396 13.9621 15.1396 14.8582 14.5812 15.2044L11.2096 17.2945C10.6669 17.6309 10 17.1931 10 16.5003L10 12.32C10 11.6273 10.6669 11.1894 11.2096 11.5258L14.5812 13.6159Z" stroke="#1C274C" stroke-width="1.5"/>
									</svg>
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
										<svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M21.9301 1.5C22.205 1.5 22.4687 1.60536 22.6631 1.79289C22.8575 1.98043 22.9667 2.23478 22.9667 2.5V18.5C22.9667 18.7652 22.8575 19.0196 22.6631 19.2071C22.4687 19.3946 22.205 19.5 21.9301 19.5H16.6311L13.2931 22.398C13.1027 22.5631 12.8558 22.6545 12.5998 22.6545C12.3438 22.6545 12.0969 22.5631 11.9065 22.398L8.56847 19.5H3.27007C2.99512 19.5 2.73144 19.3946 2.53703 19.2071C2.34262 19.0196 2.2334 18.7652 2.2334 18.5V2.5C2.2334 2.23478 2.34262 1.98043 2.53703 1.79289C2.73144 1.60536 2.99512 1.5 3.27007 1.5H21.9301ZM20.8934 3.5H4.30673V17.5H9.36411L12.6001 20.309L15.8355 17.5H20.8934V3.5ZM17.7834 10C17.7834 9.86739 17.7288 9.74021 17.6316 9.64645C17.5344 9.55268 17.4025 9.5 17.2651 9.5H7.93506C7.79759 9.5 7.66575 9.55268 7.56855 9.64645C7.47134 9.74021 7.41673 9.86739 7.41673 10V11C7.41673 11.1326 7.47134 11.2598 7.56855 11.3536C7.66575 11.4473 7.79759 11.5 7.93506 11.5H17.2651C17.4025 11.5 17.5344 11.4473 17.6316 11.3536C17.7288 11.2598 17.7834 11.1326 17.7834 11V10Z" fill="black"></path>
									</svg>
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
										<svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M21.9301 1.5C22.205 1.5 22.4687 1.60536 22.6631 1.79289C22.8575 1.98043 22.9667 2.23478 22.9667 2.5V18.5C22.9667 18.7652 22.8575 19.0196 22.6631 19.2071C22.4687 19.3946 22.205 19.5 21.9301 19.5H16.6311L13.2931 22.398C13.1027 22.5631 12.8558 22.6545 12.5998 22.6545C12.3438 22.6545 12.0969 22.5631 11.9065 22.398L8.56847 19.5H3.27007C2.99512 19.5 2.73144 19.3946 2.53703 19.2071C2.34262 19.0196 2.2334 18.7652 2.2334 18.5V2.5C2.2334 2.23478 2.34262 1.98043 2.53703 1.79289C2.73144 1.60536 2.99512 1.5 3.27007 1.5H21.9301ZM20.8934 3.5H4.30673V17.5H9.36411L12.6001 20.309L15.8355 17.5H20.8934V3.5ZM17.7834 10C17.7834 9.86739 17.7288 9.74021 17.6316 9.64645C17.5344 9.55268 17.4025 9.5 17.2651 9.5H7.93506C7.79759 9.5 7.66575 9.55268 7.56855 9.64645C7.47134 9.74021 7.41673 9.86739 7.41673 10V11C7.41673 11.1326 7.47134 11.2598 7.56855 11.3536C7.66575 11.4473 7.79759 11.5 7.93506 11.5H17.2651C17.4025 11.5 17.5344 11.4473 17.6316 11.3536C17.7288 11.2598 17.7834 11.1326 17.7834 11V10Z" fill="black"></path>
									</svg>
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
											<div class="buddyx-col-image">
												<img width="768" height="768" src="<?php echo esc_url( 'https://wbcom.b-cdn.net/wp-content/uploads/edd/2023/05/BuddyPress-Business-Profile.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Business-Profile', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Business Profile', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Business Pages enable your members to connect with their customers, fans, and followers within your social community.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-business-profile/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img width="768" height="768" src="<?php echo esc_url( 'https://wbcom.b-cdn.net/wp-content/uploads/edd/2023/06/ideapush.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'ideapush', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Ideapush Integration', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'The BuddyPress IdeaPush Integration is an addon of IdeaPush that helps you manage your created ideas through the BuddyPress Profile.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-ideapush-integration/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img width="768" height="768" src="<?php echo esc_url( 'https://wbcom.b-cdn.net/wp-content/uploads/edd/2023/03/BuddyPress-Contact-Me.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Contact-Me', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Contact Me', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Contact Me displays a contact form on a member\'s profiles, allowing logged-in and non-logged-in visitors can be in touch with our community members.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-contact-me/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/07/WP_Story.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'WP_Story', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'WP Stories', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Social Stories offer a more personal way to interact with your audience.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/wp-stories/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/06/BP_profile-view.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BP_profile-view', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Who Viewed My Profile', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'The Who Viewed My Profile Plugin helps you to know about your profile visitors. It displays the count of the profile views on the member profile header...', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/who-viewed-my-profile-buddypress/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/1-member-blog.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'member-blog', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Member Blog Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BP Member Blog Pro plugin provides each of your site users their own writing environment with a fantastic user experience.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-member-blog-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-BuddyBoss-multi-vendor-marketplace.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-multi-vendor-marketplace', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyVendor', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'With the BuddyVendor plugin, you can quickly turn your BuddyPress community into a social marketplace.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyvendor/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/06/stat.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'stat', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Statistics', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Stats plugin creates an activity log of everything that happens on your BuddyPress-powered community site...', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-statistics/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-places.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-places', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Check-Ins Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow community members and groups to post updates along with selecting their current location.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-check-ins-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Friend-Follow-Suggestion.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Friend-Follow-Suggestion', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Friend & Follow Suggestion', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'BuddyPress Friends and Follow suggestions plugin assists you with improving your BuddyPress or BuddyBoss Platform-based community.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-friend-follow-suggestion/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Animated-Gif-Share.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Animated-Gif-Share', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Giphy', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Attach GIF's into your BuddyPress activity, comments, and messages.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-giphy/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-BuddyBoss-shortcodes.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-BuddyBoss-shortcodes', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Shortcodes For BuddyPress Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Add and Customize the BuddyPress components on any of the WordPress pages/posts using elementor widgets.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/shortcodes-for-buddypress-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Sticky-Post.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Sticky-Post', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Sticky Post', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Make your posts sticky on BuddyPress by pinning site-wide and group activities on the top of the community wall.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-sticky-post/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-color-background-activity.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-color-background-activity', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Quotes', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow BuddyPress users to post content with attractive background colors and beautiful images.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-quotes/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-BuddyBoss-autofriend.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-BuddyBoss-autofriend', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Auto Friends', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Add selected community members as common friends for all members.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-auto-friends/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Newsfeed-member-profile.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Newsfeed-member-profile', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Newsfeed', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Orgnanize all the BuddyPress activity-streams just like Facebook newsfeed.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-newsfeed/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Reactions-and-Status.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Reactions-and-Status', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Status', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to add status on their profile and give their reactions to all the activity updates.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-status/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Hashtags-BuddyBoss.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Hashtags', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Hashtags', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow users to add multi-language hashtag links on BuddyPress community website.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-hashtags/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Moderation-BuddyBoss-1.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Moderation', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Moderation Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to flag inappropriate content on your BuddyPress community site.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-moderation-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Private-Community-Pro.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Private-Community-Pro', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Private Community Pro', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Lockdown the BuddyPress components to make your member's profile safe.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-private-community-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Polls-BuddyBoss-Platform.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Polls', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Polls', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow users to create and respond to polls inside the BuddyPress activity or in groups.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-polls/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Profanity-BuddyBoss.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Profanity', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Profanity', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Specify a list of bad words to control the content in your BuddyPress community.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profanity/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Resume-Manager.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Resume-Manager', 'buddyx' ); ?>" />
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Resume Manager', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to create and display resumes on BuddyPress right from their profile page.', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-resume-manager/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Get It Now', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
											<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2022/03/BuddyPress-Profile-Pro.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress-Profile-Pro', 'buddyx' ); ?>" />
											</div>
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
