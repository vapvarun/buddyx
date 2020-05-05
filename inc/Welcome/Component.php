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
			'manage_options',
			'buddyxpro-welcome',
			array( &$this, 'submenu_page_callback' )
		);
	}

	public function submenu_page_callback() { ?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Getting started with BuddyX', 'buddyx' ); ?></h1>
				
			<div class="buddyx-dashboard-tabs">

				<section class="content">
					<div class="tabs">
					<div role="tablist" aria-label="Programming Languages">
						<button role="tab" aria-selected="true" id="tab1">Home</button>
						<button role="tab" aria-selected="false" id="tab2">Get Pro</button>
					</div>
					<div role="tabpanel" aria-labelledby="tab1">
						<div class="buddyx-home-banner">
							<img src="https://wbcomdesigns.com/wp-content/uploads/2020/05/thanks-theme-min.jpg" class="size-medium_large" alt="Thank You" />
						</div>

						<div class="buddyx-home-body">

							<div class="buddyx-row knowledge-base">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-welcome-learn-more"></span></div>
									<h3 class="buddyx-feature-title">Knowledge Base</h3>
									<p class="buddyx-col-content">We have created full-proof documentation for you. It will help you to understand how our theme works.</p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="https://buddyxtheme.com/docs">Take Me to The Knowledge Page</a>
								</div>

								<div class="buddyx-col">
									<img width="1414" height="1072" src="https://wbcomdesigns.com/wp-content/uploads/2020/05/knowledge-base-min.png" alt="Knowledge Base">
								</div>
							</div><!-- .knowledge-base -->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row video-tutorial">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-video-alt3"></span></div>
									<h3 class="buddyx-feature-title">Video Tutorial</h3>
									<p class="buddyx-col-content">How to use Floating Effects and manage CSS Transform?</p>
								</div>
							</div><!-- .video-tutorial -->

							<div class="buddyx-row">
								<div class="buddyx-col">
									<a href="https://www.youtube.com/watch?v=KSRaUaD30Jc" class="buddyx-feature-sub-title-a">
										<img class="ha-img-fluid ha-rounded" src="https://buddyxtheme.com/wp-content/plugins/happy-elementor-addons/assets/imgs/admin/crossdomain-video-cover.jpg" alt="BuddyX Demo installation">
										<h4 class="buddyx-feature-sub-title">BuddyX Demo installation</h4>
									</a>
								</div>
								<div class="buddyx-col">
									<a href="https://www.youtube.com/watch?v=KSRaUaD30Jc" class="buddyx-feature-sub-title-a">
										<img class="ha-img-fluid ha-rounded" src="https://buddyxtheme.com/wp-content/plugins/happy-elementor-addons/assets/imgs/admin/crossdomain-video-cover.jpg" alt="BuddyX Customizer Option">
										<h4 class="buddyx-feature-sub-title">BuddyX Customizer Option</h4>
									</a>
								</div>
								<div class="buddyx-col">
								<a href="https://www.youtube.com/watch?v=KSRaUaD30Jc" class="buddyx-feature-sub-title-a">
									<img class="ha-img-fluid ha-rounded" src="https://buddyxtheme.com/wp-content/plugins/happy-elementor-addons/assets/imgs/admin/crossdomain-video-cover.jpg" alt="Setting Up BuddyPress">
									<h4 class="buddyx-feature-sub-title">Setting Up BuddyPress</h4>
								</a>
								</div>
							</div><!-- .video-tutorial columns -->

							<div class="buddyx-text-center">
								<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="https://buddyxtheme.com/docs">View More Videos</a>
							</div><!-- .video-tutorial button-->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row faq">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-format-chat"></span></div>
									<h3 class="buddyx-feature-title">FAQ</h3>
									<p class="buddyx-col-content">Frequently Asked Questions</p>
								</div>
							</div><!-- faq -->

							<div class="buddyx-row">
								<div class="buddyx-col">
									<h4 class="buddyx-faq-title">Can I use these addons in my client project?</h4>
									<p class="buddyx-faq-content">Yes, absolutely, no holds barred. Use it to bring colorful moments to your customers. And don’t forget to check out our premium features.</p>
								</div>
								<div class="buddyx-col">
									<h4 class="buddyx-faq-title">Is there any support policy available for the free users?</h4>
									<p class="buddyx-faq-content">Free or pro version, both comes with excellent support from us. However, pro users will get priority support.</p>
								</div>
							</div><!-- .faq columns -->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row support">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-email"></span></div>
									<h3 class="buddyx-feature-title">Support</h3>
									<p class="buddyx-faq-content">Submit your tickets to get solutions from our WordPress experts within the next 24 hours.</p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="https://wbcomdesigns.com/support">Create Ticket</a>
								</div>

								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-phone"></span></div>
									<h4 class="buddyx-feature-title">Help Desk Hours</h3>
									<p class="buddyx-faq-content">Days: Monday-Friday</br>
										Time: 10AM – 7PM GMT</br>
										Inquiries received after the working hours or on weekends will be addressed on the next working day.</p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="https://support.wbcomdesigns.com/portal/home">Start Chat</a>
								</div>
							</div><!-- .support -->
							

						</div><!-- .buddyx-home-body -->

					</div><!-- .tab1 -->
					<div role="tabpanel" aria-labelledby="tab2" hidden>
						<div class="buddyx-home-banner">
							<img src="https://wbcomdesigns.com/wp-content/uploads/2020/05/choose-pro-theme-min.jpg" class="size-medium_large" alt="Thank You" />
						</div>

						<div class="buddyx-text-center">
							<h2 class="buddyx-feature-title">Features</h2>
						</div><!-- .video-tutorial button-->
					
						<div class="buddyx-row features">
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title">Multiple Header Variations</h3>
								<p class="buddyx-col-content">Website header attracts the site visitors’ attention first. Keeping this fact in mind, BuddyX theme is designed with attractive header styles that make your site look more interactive. Try out the various header designs and select the best one for your community website.</p>
							</div>
							<div class="buddyx-col">
								<img width="1024" height="418" src="https://wbcomdesigns.com/wp-content/uploads/edd/2020/05/Multiple-Header-min-1024x418.png" class="size-large" alt="Multiple Header Variations">
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<img width="1024" height="418" src="https://wbcomdesigns.com/wp-content/uploads/edd/2020/05/member-group-layout-min.png" class="size-large" alt="Members and Groups Layout">
							</div>
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title">Members and Groups Layout</h3>
								<p class="buddyx-col-content">In order to make your community website more engaging and interactive, BuddyX theme comes with clean and most flexible members and group directory layouts. It makes your website more user-friendly and attractive.</p>
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title">Multiple Member &#38; Group Header Layouts</h3>
								<p class="buddyx-col-content">BuddyX offers beautiful layouts for members and group pages for your social network community website. Site admin can select layouts to style the profile header of their community members as well as for groups.</p>
							</div>
							<div class="buddyx-col">
								<img width="1024" height="770" src="https://wbcomdesigns.com/wp-content/uploads/edd/2020/05/Member-Header-1-min.png" class="size-large" alt="Multiple Member &#38; Group Header Layouts">
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<img width="1024" height="757" src="https://wbcomdesigns.com/wp-content/uploads/edd/2020/05/shop-min-1024x757.png" class="size-large" alt="WooCommerce Ready">
							</div>
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title">WooCommerce Ready</h3>
								<p class="buddyx-col-content">Want more than just a BuddyPress or bbPress WordPress theme? BuddyX will match your requirements! Try this amazing social network theme that offers support to the most widely used WordPress e-commerce plugin— WooCommerce. Integrate online store functionalities with your website today and sell your products to your community members.</p>
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title">LearnDash Support</h3>
								<p class="buddyx-col-content">If you are looking for a powerful social learning solution, BuddyX is perfect! The theme is fully compatible with the powerful WordPress LMS plugin→ LearnDash. You can easily create a community for your students to engage learners with a social experience and allow them to share their progress with others.</p>
							</div>
							<div class="buddyx-col">
								<img width="1024" height="668" src="https://wbcomdesigns.com/wp-content/uploads/edd/2020/05/Learndash-min-1024x668.png" class="size-large" alt="LearnDash Support">
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<img width="1024" height="669" src="https://buddyxtheme.com/wp-content/uploads/2020/04/Buddyboss-1024x669.png" class="size-large" alt="Get Your Site Ready With Just 1-Click">
							</div>
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title">Get Your Site Ready With Just 1-Click</h3>
								<p class="buddyx-col-content">Do you like the look and style of the Demo? Would you like to create your social network just like it? BuddyX theme offers one-click demo installation support to help you get started with your website within minutes.</p>
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-text-center">
							<h3 class="buddyx-feature-title">Get Pro and Experience all those exciting features</h3>
							<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="https://wbcomdesigns.com/downloads/buddyx-pro-theme/">Get Pro</a>
						</div><!-- .video-tutorial button-->

					</div><!-- .tab2 -->
				</section>


					
			</div><!-- .buddyx-dashboard-tabs -->
		</div><!-- .wrap -->
		
		<script>

			const tabs = document.querySelector('.buddyx-dashboard-tabs .tabs');
			const tabButtons = tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tab"]');
			const tabPanels = Array.from(tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tabpanel"]'));

			function handleTabClick(event) {
			// Hide tab panels
			tabPanels.forEach(panel => panel.hidden = true);

			// Mark all tabs as unselected
			tabButtons.forEach(tab => tab.setAttribute("aria-selected", false));

			// Mark the clicked tab as selected
			event.currentTarget.setAttribute("aria-selected", true);

			// Find the associated tabPanel and show it
			const { id } = event.currentTarget;

			// Find in the array of tabPanels
			const tabPanel = tabPanels.find(
				panel => panel.getAttribute('aria-labelledby') === id
			);
			tabPanel.hidden = false;
			}

			tabButtons.forEach(button => button.addEventListener('click', handleTabClick));

		</script>
	<?php }
}
