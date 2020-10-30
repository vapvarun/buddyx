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
					</div>
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab1', 'buddyx' ); ?>">
						<div class="buddyx-home-banner">
							<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/thanks-theme-min.jpg' );?>" class="size-medium_large" alt="<?php esc_attr_e('Thank You','buddyx');?>" />
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
									<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/knowledge-base-min.png' );?>" alt="<?php esc_attr_e('Knowledge Base','buddyx');?>" />
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
										<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/theme-install-video-v-min.jpg' );?>" class="ha-img-fluid ha-rounded" alt="<?php esc_attr_e('BuddyX Demo installation','buddyx');?>" />
										<h4 class="buddyx-feature-sub-title"><?php esc_html_e( 'BuddyX Demo installation', 'buddyx' ); ?></h4>
									</a>
								</div>
								<div class="buddyx-col">
									<a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=i51CikDsbeg&feature=youtu.be' ); ?>" class="buddyx-feature-sub-title-a" target="_blank">
										<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/theme-options-v-min.jpg' );?>" class="ha-img-fluid ha-rounded" alt="<?php esc_attr_e('BuddyX Customizer Option','buddyx');?>" />
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
									<div class="buddyx-title-icon"><span class="dashicons dashicons-email"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Support', 'buddyx' ); ?></h3>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Submit your tickets to get solutions from our WordPress experts within the next 24 hours.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>"><?php esc_html_e( 'Create Ticket', 'buddyx' ); ?></a>
								</div>

								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-phone"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Help Desk Hours', 'buddyx' ); ?></h3>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Days: Monday-Friday', 'buddyx' ); ?></br>
										<?php esc_html_e( 'Time: 10AM – 7PM GMT', 'buddyx' ); ?></br>
										<?php esc_html_e( 'Inquiries received after the working hours or on weekends will be addressed on the next working day.', 'buddyx' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://support.wbcomdesigns.com/portal/home' ); ?>"><?php esc_html_e('Start Chat','buddyx' );?></a>
								</div>
							</div><!-- .support -->
							

						</div><!-- .buddyx-home-body -->

					</div><!-- .tab1 -->
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab2', 'buddyx' ); ?>" hidden>
						<div class="buddyx-home-banner">
							<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/choose-pro-theme-min.jpg' );?>" class="size-medium_large" alt="<?php esc_attr_e('Thank You','buddyx');?>" />
						</div>

						<div class="buddyx-text-center">
							<h2 class="buddyx-feature-title"><?php esc_html_e( 'Features', 'buddyx' ); ?></h2>
						</div><!-- .video-tutorial button-->
					
						<div class="buddyx-row features">
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title"><?php esc_html_e( 'Multiple Header Variations', 'buddyx' ); ?></h3>
								<p class="buddyx-col-content"><?php esc_html_e( 'Website header attracts the site visitors’ attention first. Keeping this fact in mind, BuddyX theme is designed with attractive header styles that make your site look more interactive. Try out the various header designs and select the best one for your community website.', 'buddyx' ); ?></p>
							</div>
							<div class="buddyx-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Multiple-Header-min-1024x418.png' );?>" class="size-large" alt="<?php esc_attr_e('Multiple Header Variations','buddyx');?>" />
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/member-group-layout-min.png' );?>" class="size-large" alt="<?php esc_attr_e('Members and Groups Layout','buddyx');?>" />
							</div>
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title"><?php esc_html_e( 'Members and Groups Layout', 'buddyx' ); ?></h3>
								<p class="buddyx-col-content"><?php esc_html_e( 'In order to make your community website more engaging and interactive, BuddyX theme comes with clean and most flexible members and group directory layouts. It makes your website more user-friendly and attractive.', 'buddyx' ); ?></p>
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title"><?php esc_html_e( 'Multiple Member &#38; Group Header Layouts', 'buddyx' ); ?></h3>
								<p class="buddyx-col-content"><?php esc_html_e( 'BuddyX offers beautiful layouts for members and group pages for your social network community website. Site admin can select layouts to style the profile header of their community members as well as for groups.', 'buddyx' ); ?></p>
							</div>
							<div class="buddyx-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Member-Header-1-min.png' );?>" class="size-large" alt="<?php esc_attr_e('Multiple Member &#38; Group Header Layouts','buddyx');?>" />
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/shop-min-1024x757.png' );?>" class="size-large" alt="<?php esc_attr_e('WooCommerce Ready','buddyx');?>" />
							</div>
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title"><?php esc_html_e( 'WooCommerce Ready', 'buddyx' ); ?></h3>
								<p class="buddyx-col-content"><?php esc_html_e( 'Want more than just a BuddyPress or bbPress WordPress theme? BuddyX will match your requirements! Try this amazing social network theme that offers support to the most widely used WordPress e-commerce plugin— WooCommerce. Integrate online store functionalities with your website today and sell your products to your community members.', 'buddyx' ); ?></p>
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title"><?php esc_html_e( 'LearnDash Support', 'buddyx' ); ?></h3>
								<p class="buddyx-col-content"><?php esc_html_e( 'If you are looking for a powerful social learning solution, BuddyX is perfect! The theme is fully compatible with the powerful WordPress LMS plugin→ LearnDash. You can easily create a community for your students to engage learners with a social experience and allow them to share their progress with others.', 'buddyx' ); ?></p>
							</div>
							<div class="buddyx-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Learndash-min-1024x668.png' );?>" class="size-large" alt="<?php esc_attr_e('LearnDash Support','buddyx');?>" />
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-row features">
							<div class="buddyx-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Buddyboss-1024x669.png' );?>" class="size-large" alt="<?php esc_attr_e('Get Your Site Ready With Just 1-Click','buddyx');?>" />
							</div>
							<div class="buddyx-col">
								<h3 class="buddyx-feature-title"><?php esc_html_e( 'Get Your Site Ready With Just 1-Click', 'buddyx' ); ?></h3>
								<p class="buddyx-col-content"><?php esc_html_e( 'Do you like the look and style of the Demo? Would you like to create your social network just like it? BuddyX theme offers one-click demo installation support to help you get started with your website within minutes.', 'buddyx' ); ?></p>
							</div>
						</div><!-- .features -->

						<div class="buddyx-divider"></div><!-- .buddyx-divider -->

						<div class="buddyx-text-center">
							<h3 class="buddyx-feature-title"><?php esc_html_e( 'Get Pro and Experience all those exciting features', 'buddyx' ); ?></h3>
							<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddyx-pro-theme/' ); ?>"><?php esc_html_e('Get Pro','buddyx' ); ?></a>
						</div><!-- .video-tutorial button-->

					</div><!-- .tab2 -->
				</section>


					
			</div><!-- .buddyx-dashboard-tabs -->
		</div><!-- .wrap -->
	<?php }
}
