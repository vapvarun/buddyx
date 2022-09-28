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

	public function submenu_page_callback() { ?>
		<div class="buddyx-top-banner-wrapper">
			<div class="buddyx-top-banner">
				<span class="buddyx-theme-version">
					<?php esc_html_e( 'Version', 'buddyx' ); ?> <?php echo esc_html( wp_get_theme( get_template() )->get( 'Version' ) ); ?>
				</span>
				<h1 class="buddyx-banner-titile">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyx-theme-name.png' ); ?>" alt="<?php esc_attr_e( 'buddyx-theme', 'buddyx' ); ?>">
				</h1>
				<div class="buddyx-banner-description">
					<p><?php esc_html_e( 'Thank you for your purchase! Below you will find information on how to setup the theme to start using it!', 'buddyx' ); ?></p>
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
							<button role="tab" aria-selected="false" id="tab2"><?php esc_html_e( 'Help & Support', 'buddyx' ); ?></button>
							<button role="tab" aria-selected="false" id="tab3"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></button>
							<button role="tab" aria-selected="false" id="tab4"><?php esc_html_e( 'Community Addons', 'buddyx' ); ?></button>
						</div>
						<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab1', 'buddyx' ); ?>">
							<div class="buddyx-tabs-content buddyx-dashboard-body">
								<h2 class="buddyx-tabs-titile">
									<?php esc_html_e( 'Welcome to BuddyX Pro Theme', 'buddyx' ); ?>
								</h2>
								<div class="buddyx-row knowledge-base">
									<div class="buddyx-col col-left">
										<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/knowledge-base.png' ); ?>" alt="<?php esc_attr_e( 'knowledge-base', 'buddyx' ); ?>">
									</div>
									<div class="buddyx-col col-right">
										<h3 class="buddyx-feature-title"><?php esc_html_e( 'Knowledge Base', 'buddyx' ); ?></h3>
										<p class="buddyx-col-content"><?php esc_html_e( 'We have created full-proof documentation for you. It will help you to understand how our plugin works.', 'buddyx' ); ?></p>
										<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/doc_category/buddyx-theme/' ); ?>"><?php esc_html_e( 'View More Knowledge Page', 'buddyx' ); ?></a>
									</div>
								</div><!-- .knowledge-base -->

								<div class="buddyx-row video-tutorial">
									<div class="buddyx-col col-full">
										<h3 class="buddyx-feature-title"><?php esc_html_e( 'Video Tutorial', 'buddyx' ); ?></h3>
										<p class="buddyx-col-content"><?php esc_html_e( 'How to use Floating Effects and manage CSS Transform?', 'buddyx' ); ?></p>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/demo-installation.svg' ); ?>" alt="<?php esc_attr_e( 'demo-installation', 'buddyx' ); ?>">
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyX Demo installation', 'buddyx' ); ?></h4>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=Ztogq3dx4-E&feature=youtu.be' ); ?>"><?php esc_html_e( 'View Video', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/customizer-options.svg' ); ?>" alt="<?php esc_attr_e( 'demo-installation', 'buddyx' ); ?>">
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'BuddyX Customizer Options', 'buddyx' ); ?></h4>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=i51CikDsbeg&feature=youtu.be' ); ?>"><?php esc_html_e( 'View Video', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/theme-integrations.svg' ); ?>" alt="<?php esc_attr_e( 'demo-installation', 'buddyx' ); ?>">
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Theme Integrations', 'buddyx' ); ?></h4>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/docs/buddyx-theme/pro-integrations/gamipress/' ); ?>"><?php esc_html_e( 'View More Integrations', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
								</div><!-- .video-tutorial -->
								<div class="buddyx-row faq">
									<div class="buddyx-col col-full">
										<h2 class="buddyx-feature-title">
											<?php esc_html_e( 'Frequently Asked Questions', 'buddyx' ); ?>
										</h2>
									</div>
									<div class="buddyx-col col-left">
										<h4 class="buddyx-faq-title"><?php esc_html_e( 'Can I use these addons in my client project?', 'buddyx' ); ?></h4>
										<p class="buddyx-col-content"><?php esc_html_e( 'Yes, absolutely, no holds barred. Use it to bring colorful moments to your customers. And don’t forget to check out our premium features.', 'buddyx' ); ?></p>
									</div>
									<div class="buddyx-col col-right">
										<h4 class="buddyx-faq-title"><?php esc_html_e( 'Is there any support policy available for the free users?', 'buddyx' ); ?></h4>
										<p class="buddyx-col-content"><?php esc_html_e( 'Free or pro version, both comes with excellent support from us. However, pro users will get priority support.', 'buddyx' ); ?></p>
									</div>
								</div><!-- faq -->
							</div><!-- .buddyx-dashboard-body -->
						</div><!-- .tab1 -->

						<div role="tabpanel" aria-labelledby="tab2" hidden>
							<div class="buddyx-tabs-content buddyx-support-body">
								<h2 class="buddyx-tabs-titile">
									<?php esc_html_e( 'How Can We Help You?', 'buddyx' ); ?>
								</h2>
								<p class="buddyx-col-content"><?php esc_html_e( 'Our team is here to help you out at anytime. If you have any idea about how we could improve. You can share access to your site on our helpdesk if it can help getting faster.', 'buddyx' ); ?></p>
								<div class="buddyx-row support">
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/support-ticket.svg' ); ?>" alt="<?php esc_attr_e( 'demo-installation', 'buddyx' ); ?>">
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Tickets Support', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Open a ticket on our helpdesk, we don't guarantee a fast response but within a week. Except if you've purchased one of our product we'll reply within 24 hours.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>"><?php esc_html_e( 'Create Ticket', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/support-team.svg' ); ?>" alt="<?php esc_attr_e( 'demo-installation', 'buddyx' ); ?>">
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Connect with Our Support Team', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( "Create a new thread on our theme page, participation is open to anyone from all around the world. We'll be there to help as well but can't guarantee any delay.", 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>"><?php esc_html_e( 'Contact Us', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
									<div class="buddyx-col col-same">
										<div class="buddyx-col-wrapper">
											<div class="buddyx-col-image">
												<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/review.svg' ); ?>" alt="<?php esc_attr_e( 'demo-installation', 'buddyx' ); ?>">
											</div>
											<div class="buddyx-col-text">
												<h4 class="buddyx-feature-title"><?php esc_html_e( 'Theme Integrations', 'buddyx' ); ?></h4>
												<p class="buddyx-col-content"><?php esc_html_e( 'Never underestimate your rate! Your 5 star rate will encourage us so much!', 'buddyx' ); ?></p>
												<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wordpress.org/support/theme/buddyx/reviews/?rate=5#new-post' ); ?>"><?php esc_html_e( 'Rate Us ★★★★★', 'buddyx' ); ?></a>
											</div>
										</div>
									</div>
								</div><!-- .support -->
							</div><!-- .buddyx-support-body -->
						</div><!-- .tab2 -->

						<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab3', 'buddyx' ); ?>" hidden>
							<div class="buddyxpro-features-banner">
								<div class="buddyxpro-features-content">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyxpro-theme-name.png' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'buddyxpro-theme-name', 'buddyx' ); ?>" />
									<p class="buddyx-col-content"><?php esc_html_e( "What's Inside the BuddyX Pro Theme", 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/' ); ?>"><?php esc_html_e( 'Get BuddyX Pro', 'buddyx' ); ?></a>
								</div>
								<div class="buddyxpro-features-image">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/buddyxpro-web-page.png' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'buddyxpro-web-page', 'buddyx' ); ?>" />
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
													<td><?php esc_html_e( 'Site Loader', 'buddyx' ); ?></td>
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
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Header Layout', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-close"></span></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Menu Effects', 'buddyx' ); ?></td>
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
													<td><?php esc_html_e( 'WC Vendors', 'buddyx' ); ?></td>
													<td class="bx-limited" style="text-align: center"><?php esc_html_e( 'Limited', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'GamiPress', 'buddyx' ); ?></td>
													<td style="text-align: center"><span class="icon-check"></td>
													<td style="text-align: center"><span class="icon-check"></td>
												</tr>
											</tbody>
										</table>
									</div><!-- .buddyx-features-table-inner -->
								</div><!-- .buddyx-features-table-wrap -->
							</div><!-- .features -->

						</div><!-- .tab3 -->

						<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab4', 'buddyx' ); ?>" hidden>
							<div class="buddyx-tabs-content buddyx-addon-body">
								<h2 class="buddyx-tabs-titile">
									<?php esc_html_e( 'Premium add-ons', 'buddyx' ); ?>
								</h2>
								<p class="buddyx-col-content"><?php esc_html_e( 'Extend your social community website with our premium add-ons for BuddyPress.', 'buddyx' ); ?></p>

								<div class="buddyx-row addons">
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
						</div><!-- .tab4 -->
					</section>

				</div><!-- .buddyx-dashboard-tabs -->
			</div><!-- .welcome-inner-wrap -->
		</div><!-- .wrap -->
		
		<?php
	}
}
