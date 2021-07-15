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

	public function submenu_page_callback() { ?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Getting started with BuddyX', 'buddyx' ); ?></h1>
				
			<div class="buddyx-dashboard-tabs">

				<section class="content">
					<div class="tabs">
					<div role="tablist" aria-label="<?php esc_attr_e( 'Programming Languages', 'buddyx' ); ?>">
						<button role="tab" aria-selected="true" id="tab1"><?php esc_html_e( 'Home', 'buddyx' ); ?></button>
						<button role="tab" aria-selected="false" id="tab2"><?php esc_html_e( 'Get Pro', 'buddyx' ); ?></button>
						<button role="tab" aria-selected="false" id="tab3"><?php esc_html_e( 'Community Addons', 'buddyx' ); ?></button>
					</div>
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab1', 'buddyx' ); ?>">
						<div class="buddyx-home-banner">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-free.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'Thank You', 'buddyx' ); ?>" />
						</div>
						<div class="buddyx-home-body">

							<div class="buddyx-row knowledge-base">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-welcome-learn-more"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Knowledge Base', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'We have created full-proof documentation for you. It will help you to understand how our theme works.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs' ); ?>"><?php esc_html_e( 'Take Me to The Knowledge Page', 'buddyx' ); ?></a>
								</div>

								<div class="buddyx-col">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/knowledge-base-min.png' ); ?>" alt="<?php esc_attr_e( 'Knowledge Base', 'buddyx' ); ?>" />
								</div>
							</div><!-- .knowledge-base -->

							<div class="buddyx-row knowledge-base-doc">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-admin-home"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Getting Started', 'buddyx' ); ?></h4>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs-category/getting-started/' ); ?>"><?php esc_html_e( 'View', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-welcome-widgets-menus"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Menu Setting', 'buddyx' ); ?></h4>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs-category/menu-setting/' ); ?>"><?php esc_html_e( 'View', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-admin-generic"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Customizer Settings', 'buddyx' ); ?></h4>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs-category/customizer-settings/' ); ?>"><?php esc_html_e( 'View', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-update-alt"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Integrations', 'buddyx' ); ?></h4>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs-category/integrations/' ); ?>"><?php esc_html_e( 'View', 'buddyx' ); ?></a>
								</div>
							</div><!-- .knowledge-base -->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row video-tutorial">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-video-alt3"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Video Tutorial', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'How to use Floating Effects and manage CSS Transform?', 'buddyx' ); ?></p>
								</div>
							</div><!-- .video-tutorial -->

							<div class="buddyx-row">
								<div class="buddyx-col">
									<a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=Ztogq3dx4-E&feature=youtu.be' ); ?>" class="buddyx-feature-sub-title-a" target="_blank">
										<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/theme-install-video-v-min.jpg' ); ?>" class="ha-img-fluid ha-rounded" alt="<?php esc_attr_e( 'BuddyX Demo installation', 'buddyx' ); ?>" />
										<h4 class="buddyx-feature-sub-title"><?php esc_html_e( 'BuddyX Demo installation', 'buddyx' ); ?></h4>
									</a>
								</div>
								<div class="buddyx-col">
									<a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=i51CikDsbeg&feature=youtu.be' ); ?>" class="buddyx-feature-sub-title-a" target="_blank">
										<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/theme-options-v-min.jpg' ); ?>" class="ha-img-fluid ha-rounded" alt="<?php esc_attr_e( 'BuddyX Customizer Option', 'buddyx' ); ?>" />
										<h4 class="buddyx-feature-sub-title"><?php esc_html_e( 'BuddyX Customizer Option', 'buddyx' ); ?></h4>
									</a>
								</div>
							</div><!-- .video-tutorial columns -->

							<div class="buddyx-text-center">
								<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs' ); ?>"><?php esc_html_e( 'View More Videos', 'buddyx' ); ?></a>
							</div><!-- .video-tutorial button-->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row faq">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-format-chat"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'FAQ', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Frequently Asked Questions', 'buddyx' ); ?></p>
								</div>
							</div><!-- faq -->

							<div class="buddyx-row">
								<div class="buddyx-col">
									<h4 class="buddyx-faq-title"><?php esc_html_e( 'Can I use these addons in my client project?', 'buddyx' ); ?></h4>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Yes, absolutely, no holds barred. Use it to bring colorful moments to your customers. And don’t forget to check out our premium features.', 'buddyx' ); ?></p>
								</div>
								<div class="buddyx-col">
									<h4 class="buddyx-faq-title"><?php esc_html_e( 'Is there any support policy available for the free users?', 'buddyx' ); ?></h4>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Free or pro version, both comes with excellent support from us. However, pro users will get priority support.', 'buddyx' ); ?></p>
								</div>
							</div><!-- .faq columns -->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row support">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-star-filled"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Rate Us', 'buddyx' ); ?></h3>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Never underestimate your rate! Your 5 star rate will encourage us so much!', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wordpress.org/support/theme/buddyx/reviews/?rate=5#new-post' ); ?>"><?php esc_html_e( 'Rate Us ★★★★★', 'buddyx' ); ?></a>
								</div>

								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-phone"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Help Desk Hours', 'buddyx' ); ?></h3>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Days: Monday-Friday', 'buddyx' ); ?></br>
										<?php esc_html_e( 'Time: 10AM – 7PM IST', 'buddyx' ); ?></br>
										<?php esc_html_e( 'Inquiries received after the working hours or on weekends will be addressed on the next working day.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://support.wbcomdesigns.com/portal/home' ); ?>"><?php esc_html_e( 'Start Chat', 'buddyx' ); ?></a>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>"><?php esc_html_e( 'Create Ticket', 'buddyx' ); ?></a>
								</div>
							</div><!-- .support -->

						</div><!-- .buddyx-home-body -->

					</div><!-- .tab1 -->
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab2', 'buddyx' ); ?>" hidden>
						<div class="buddyx-home-banner">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-paid.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'Thank You', 'buddyx' ); ?>" />
						</div>

						<div class="buddyx-text-center">
							<h2 class="buddyx-feature-title"><?php esc_html_e( 'Features', 'buddyx' ); ?></h2>
						</div><!-- .video-tutorial button-->
					
						<div class="buddyx-row features">
						<div class="buddyx-features-table-wrap">
							<table class="buddyx-features-table">
								<thead>
									<tr>
										<th style="text-align: left"><h3><?php esc_html_e( 'Features', 'buddyx' ); ?></h3></th>
										<th style="text-align: left"><h3><?php esc_html_e( 'BuddyX', 'buddyx' ); ?></h3></th>
										<th style="text-align: left"><h3><?php esc_html_e( 'BuddyX Pro', 'buddyx' ); ?></h3></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Layout', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Site Layout', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Content Layout Width', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Site Loader', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Typography', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Site Title Settings', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Headings', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Menu', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Body', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Google Fonts', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Font Size (PX/EM)', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Text Transform', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Line Height', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Letter Spacing', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Header', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Top Bar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Sticky Header', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Header Layout', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Menu Effects', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></span></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Background Color', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Site Search', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Site Cart', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Sub Header', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Customize Background', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Content Typography', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Breadcrumbs', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Skin Colors', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Body Background Color', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Theme Color', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Link Color', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Primary Header', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Button Background Color / Hover', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Button Text Color / Hover', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Button Border Color / Hover', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Site Blog', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'List Layout', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Grid Layout', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Masonry Layout', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Blog Layout Style', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Post Per Row', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Site Sidebar', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'No Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Left Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Right Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Both Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'BuddyPress Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'WooCommerce Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'bbPress Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'LearnDash Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Sticky Sidebar', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Site Footer', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Footer Section', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Copyright Section', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'BuddyPress', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Activity Load More', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Members Multiple Directory Layout', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Members Default Cover Background', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Groups Multiple Directory View', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Group Default Cover Background', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Member Multiple Header Layout', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Member Primary Navigation Layout', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Group Multiple Header Layout', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Group Primary Navigation Style', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>

									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'WooCommerce', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Product Listing Style', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-dismiss"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td class="buddyx-main-feature"><?php esc_html_e( 'Integrations', 'buddyx' ); ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'BuddyPress', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'BuddyBoss Platform', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'WooCommerce', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'LearnDash', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'LearnPress', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'LifterLMS', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'TutorLMS', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'bbPress', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'WC Vendors', 'buddyx' ); ?></td>
										<td><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'GamiPress', 'buddyx' ); ?></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
										<td><span class="dashicons dashicons-yes-alt"></td>
									</tr>
								</tbody>
								</table>
							</div><!-- .features-wrapper -->
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-text-center">
							<h3 class="buddyx-feature-title"><?php esc_html_e( 'Get Pro and Experience all those exciting features', 'buddyx' ); ?></h3>
							<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/' ); ?>"><?php esc_html_e( 'Get Pro', 'buddyx' ); ?></a>
						</div><!-- .video-tutorial button-->

					</div><!-- .tab2 -->

					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab3', 'buddyx' ); ?>" hidden>
						<div class="buddyx-home-banner">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-addons.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'Thank You', 'buddyx' ); ?>" />
						</div>

							<div class="buddyx-row community-addons">
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Status & Reaction', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to add status on their profile and give their reactions to all the activity updates.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-status/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Private Community Pro', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Lock down the BuddyPress components to make your member\'s profile safe.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-private-community-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Status & Reaction', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to flag inappropriate content on your BuddyPress community site.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-moderation-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
							</div><!-- .community-addons -->
							<div class="buddyx-row community-addons">
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Hashtags', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Allow users to add multi-language hashtag links on BuddyPress community website.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-hashtags/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Polls', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Allow users to create and respond to polls inside the BuddyPress activity or in groups.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-polls/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Profile Pro', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Add repeate fields or group of fields to the front-end forms on the member\'s profile.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profile-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
							</div><!-- .community-addons -->
							<div class="buddyx-row community-addons">
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Resume Manager', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Allow members to create and display resumes on BuddyPress right form their profile page.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-resume-manager/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Quotes', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Allow BuddyPress users to post content with attractive background colors and beautiful images.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-quotes/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Sticky Post', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Make your posts stick on BuddyPress by pinning site-wide and group activities on the top of the community wall.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-sticky-post/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
							</div><!-- .community-addons -->
							<div class="buddyx-row community-addons">
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Profanity', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Specify a list of bad words to control the content in your BuddyPress community.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profanity/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Newsfeed', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Organize all the BuddyPress activity-streams just like Facebook newsfeed.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-newsfeed/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'BuddyPress Auto Friends', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Add selected community members as common friends for all members.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-auto-friends/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
							</div><!-- .community-addons -->
							<div class="buddyx-row community-addons">
								<div class="buddyx-col">
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Shortcodes for BuddyPress Pro', 'buddyx' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Full control on BuddyPress/BuddyBoss Platform template-driven pages. Introducing Elementor widgets for activity stream, group, and member directory with various parameters to set as per your choice.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/shortcodes-for-buddypress-pro/?utm_source=buddyx&utm_medium=welcome&utm_campaign=buddyx-theme' ); ?>"><?php esc_html_e( 'Try Now', 'buddyx' ); ?></a>
								</div>
								<div class="buddyx-col"></div>
								<div class="buddyx-col"></div>
							</div><!-- .community-addons -->

					</div><!-- .tab3 -->
				</section>

			</div><!-- .buddyx-dashboard-tabs -->
		</div><!-- .wrap -->
		
		<?php
	}
}
