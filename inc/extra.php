<?php
/**
 * The `buddyx()` extra.
 *
 * @package buddyx
 */

// Content wrapper
if ( ! function_exists( 'buddyx_content_top' ) ) {
	function buddyx_content_top() {
		?>
		<div class="site-wrapper">
		<?php
	}
}

add_action( 'buddyx_before_content', 'buddyx_content_top' );

if ( ! function_exists( 'buddyx_content_bottom' ) ) {
	function buddyx_content_bottom() {
		?>
		</div>
		<?php
	}
}

add_action( 'buddyx_after_content', 'buddyx_content_bottom' );

// Site Sub Header
if ( ! function_exists( 'buddyx_sub_header' ) ) {
	add_action( 'buddyx_sub_header', 'buddyx_sub_header' );

	function buddyx_sub_header() {
		global $post;
		if ( is_front_page() ) {
			return;
		}
		?>
	<div class="site-sub-header">
		<div class="container">
			<?php
			if ( get_post_type() === 'post' || is_single() || is_archive( 'post-type-archive-forum' ) || is_archive( 'post-type-archive-topic' ) || is_archive( 'post-type-archive-ideas' ) && ( function_exists( 'is_shop' ) && ! is_shop() ) ) {
				get_template_part( 'template-parts/content/page_header' );
				$breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					buddyx_the_breadcrumb();
				}
			} elseif ( get_post_type() === 'page' || is_singular() ) {
					// PAGE
					get_template_part( 'template-parts/content/entry_title', get_post_type() );
					$breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					buddyx_the_breadcrumb();
				}
			}
			?>
		</div>
	</div>
		<?php
	}
}

/*
 * BREADCRUMBS
 */
if ( ! function_exists( 'buddyx_the_breadcrumb' ) ) {
	/**
	 * Displays breadcrumb navigation for BuddyX theme, with caching.
	 *
	 * This function checks if Yoast SEO breadcrumbs are enabled. If so, it uses
	 * `yoast_breadcrumb` to display them. If not, it falls back to the `buddyx_get_breadcrumb`
	 * function to display custom breadcrumbs. The output is cached using `wp_cache` to improve
	 * performance and avoid regenerating breadcrumbs on every page load.
	 *
	 * Caching is performed based on the current post or page ID, and the cache duration is set
	 * to 12 hours.
	 *
	 * @return void
	 */
	function buddyx_the_breadcrumb() {
		// Generate a unique cache key based on the post ID or page ID.
		$post_id   = get_the_ID();
		$cache_key = 'buddyx_breadcrumb_' . $post_id;

		// Try to get the cached breadcrumb.
		$breadcrumb = wp_cache_get( $cache_key, 'buddyx_breadcrumb' );

		if ( false === $breadcrumb ) {
			// No cached breadcrumb, generate it.
			ob_start();

			$wpseo_titles = get_option( 'wpseo_titles' );
			if ( function_exists( 'yoast_breadcrumb' ) && isset( $wpseo_titles['breadcrumbs-enable'] ) && $wpseo_titles['breadcrumbs-enable'] == 1 ) {
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
			} else {
				echo '<div class="buddyx-breadcrumbs">';
					buddyx_get_breadcrumb();
				echo '</div>';
			}

			// Store the generated breadcrumb.
			$breadcrumb = ob_get_clean();
			wp_cache_set( $cache_key, $breadcrumb, 'buddyx_breadcrumb', 12 * HOUR_IN_SECONDS ); // Cache for 12 hours.
		}

		// Output the breadcrumb.
		echo wp_kses_post( $breadcrumb );
	}
}

// Site Loader
if ( ! function_exists( 'buddyx_site_loader' ) ) {
	function buddyx_site_loader() {
		$loader = get_theme_mod( 'site_loader', buddyx_defaults( 'site-loader' ) );
		if ( $loader == '1' ) {
			echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
		}
	}
}

// Site Search and WooCommerce Cart Icon.
if ( ! function_exists( 'buddyx_site_menu_icon' ) ) {
	/**
	 * Renders site menu icons, including a search icon and a WooCommerce cart icon.
	 * The function checks the theme settings to determine if the icons should be displayed.
	 */
	function buddyx_site_menu_icon() {
		// Get the settings for search and cart icons from the theme customizer.
		$searchicon = (int) get_theme_mod( 'site_search', buddyx_defaults( 'site-search' ) );
		$carticon   = (int) get_theme_mod( 'site_cart', buddyx_defaults( 'site-cart' ) );

		// Check if either search or cart icon is enabled.
		if ( ! empty( $searchicon ) || ! empty( $carticon ) ) :
			?>
			<div class="menu-icons-wrapper">
				<?php
				// Render the search icon if enabled.
				if ( ! empty( $searchicon ) ) :
					?>
					<div class="search" <?php echo apply_filters( 'buddyx_search_slide_toggle_data_attrs', '' ); ?>>
						<a href="#" id="overlay-search" class="search-icon" title="<?php esc_attr_e( 'Search', 'buddyx' ); ?>">
							<span class="fa fa-search"></span>
						</a>
						<div class="top-menu-search-container" <?php echo apply_filters( 'buddyx_search_field_toggle_data_attrs', '' ); ?>>
							<?php get_search_form(); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php
				// Render the cart icon if enabled and WooCommerce is active.
				if ( ! empty( $carticon ) && function_exists( 'is_woocommerce' ) ) :
					buddyx_render_cart_icon();
				endif;
				?>
			</div>
			<?php
		endif;
	}
}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'buddyx_footer_custom_text' ) ) {
	/**
	 * Retrieves and formats the custom footer text based on theme settings.
	 * This function checks if a custom copyright text is set in the theme customizer.
	 * If a custom text is provided, it replaces placeholders with the current year, site title, and theme author link.
	 * If no custom text is set, it generates a default copyright message with the site title and a link to the BuddyX theme.
	 *
	 * @return string The formatted footer text.
	 */
	function buddyx_footer_custom_text() {
		// Get the custom copyright text from the theme customizer.
		$copyright = get_theme_mod( 'site_copyright_text' );

		// Check if custom copyright text is set.
		if ( $copyright ) {
			// Replace placeholders with actual values.
			$output = str_replace(
				array( '[current_year]', '[site_title]', '[theme_author]' ),
				array(
					date_i18n( 'Y' ), // Current year.
					esc_html( get_bloginfo( 'name' ) ), // Site title.
					'<a href="' . esc_url( 'https://wbcomdesigns.com/downloads/buddyx-theme/' ) . '">' . esc_html__( 'BuddyX WordPress Theme', 'buddyx' ) . '</a>', // Theme author link.
				),
				$copyright
			);
		} else {
			// Generate default copyright text.
			$output = sprintf(
				'Copyright &copy; %s <span class="buddyx-footer-site-title"><a href="%s">%s</a></span> | Powered by <a href="%s">%s</a>',
				date_i18n( 'Y' ), // Current year.
				esc_url( home_url( '/' ) ), // Site URL.
				esc_html( get_bloginfo( 'name' ) ), // Site title.
				esc_url( 'https://wbcomdesigns.com/downloads/buddyx-theme/' ), // Theme URL.
				esc_html__( 'BuddyX WordPress Theme', 'buddyx' ) // Translated theme name.
			);
		}

		// Apply filter to allow modifications to the footer text.
		return apply_filters( 'buddyx_footer_copyright_text', $output );
	}
}

/**
 * Categorized Blog
 * Find out if blog has more than one category.
 */
if ( ! function_exists( 'buddyx_categorized_blog' ) ) {
	function buddyx_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'buddyx_category_count' ) ) ) {

			$all_the_cool_cats = get_categories( array( 'hide_empty' => 1 ) );
			$all_the_cool_cats = count( $all_the_cool_cats );
			set_transient( 'buddyx_category_count', $all_the_cool_cats );

		}

		if ( 1 !== (int) $all_the_cool_cats ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Blog Post Meta
 */
if ( ! function_exists( 'buddyx_posted_on' ) ) {

	function buddyx_posted_on() {

		global $post;

		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="entry-featured">' . esc_html__( 'Sticky', 'buddyx' ) . '</span>';
		}

		if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && buddyx_categorized_blog() ) {
			echo '<span class="entry-cat-links">' . get_the_category_list( ', ' ) . '</span>';
		}

		if ( ! is_search() ) {

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				echo '<span class="entry-comments-link">';
				comments_popup_link( esc_html__( 'Leave a comment', 'buddyx' ), esc_html__( '1 Comment', 'buddyx' ), esc_html__( '% Comments', 'buddyx' ) );
				echo '</span>';
			}
		}

		edit_post_link( esc_html__( 'Edit', 'buddyx' ), '<span class="entry-edit-link">', '</span>' );
	}
}

/**
 * Managing 404 URL in Frontend
 */
if ( ! function_exists( 'buddyx_404_redirect' ) ) {
	/**
	 * Redirects 404 error pages to a custom page set in the theme customizer.
	 * This function checks if the current page is a 404 error page. If so, it retrieves the custom page ID set in the theme customizer
	 * and redirects the user to that page using a 301 (permanent) redirect.
	 * If no custom page ID is set, the user remains on the 404 error page.
	 *
	 * It uses `wp_safe_redirect()` to prevent redirection to potentially unsafe URLs and ensures no further code is executed
	 * after the redirection.
	 *
	 * @return void
	 */
	function buddyx_404_redirect() {
		// Check if the current page is a 404 error page.
		if ( is_404() ) {
			// Retrieve the custom page ID for 404 redirects from the theme customizer.
			$redirect_page_id = get_theme_mod( 'buddyx_404_page', 0 );

			// Exclude rtMedia routes by checking the request URI.
			if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/media/' ) !== false ) {
				// The current URL contains `/media/`, so skip redirection.
				return;
			}

			// If a valid page ID is found, redirect to that page.
			if ( $redirect_page_id ) {
				// Get the URL of the redirect page.
				$redirect_url = get_permalink( $redirect_page_id );

				// Perform a safe redirect to the custom page URL.
				wp_safe_redirect( $redirect_url, 301 );

				// Exit to ensure no further code is executed after the redirect.
				exit;
			}
		}
	}

	// Hook the function to the `template_redirect` action to ensure it runs before the template is loaded.
	add_action( 'template_redirect', 'buddyx_404_redirect' );
}

/**
 * Add Elementor Locations Support
 */
if ( ! function_exists( 'buddyx_register_elementor_locations' ) ) {
    function buddyx_register_elementor_locations( $elementor_theme_manager ) {

        $elementor_theme_manager->register_location( 'header' );
        $elementor_theme_manager->register_location( 'footer' );

    }
    add_action( 'elementor/theme/register_locations', 'buddyx_register_elementor_locations' );
}

/**
 * Display LifterLMS Course and Lesson sidebars
 * on courses and lessons in place of the sidebar returned by
 * this function
 *
 * @param    string     $id    default sidebar id (an empty string)
 * @return   string
 */
if ( ! function_exists( 'buddyx_llms_sidebar_function' ) ) {
	function buddyx_llms_sidebar_function( $id ) {

		$my_sidebar_id = 'sidebar-right'; // replace this with your theme's sidebar ID

		return $my_sidebar_id;
	}
	add_filter( 'llms_get_theme_default_sidebar', 'buddyx_llms_sidebar_function' );
}

/**
 * Declare explicit theme support for LifterLMS course and lesson sidebars
 *
 * @return   void
 */
if ( ! function_exists( 'buddyx_llms_theme_support' ) ) {
	function buddyx_llms_theme_support() {

		add_theme_support( 'lifterlms-sidebars' );
	}
	add_action( 'after_setup_theme', 'buddyx_llms_theme_support' );
}

/**
 * Example usage for learndash-focus-header-usermenu-after action.
 */
add_action(
	'learndash-focus-header-usermenu-after',
	function ( $course_id, $user_id ) {
		?>
		<a href="#" id="buddyx-toggle-track">
			<span class="learndash-dark-mode"><i class="fa fa-moon-o"></i></span>
			<span class="learndash-light-mode"><i class="fa fa-sun-o"></i></span>
		</a>
		<?php
	},
	10,
	2
);

/**
 * Remove buddyx remove single post subheader.
 *
 * @return void
 */
if ( ! function_exists( 'buddyx_remove_single_post_subheader' ) ) {
	function buddyx_remove_single_post_subheader() {
		if ( is_single() && 'post' === get_post_type() ) {
			remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
		}
	}
	add_action( 'wp', 'buddyx_remove_single_post_subheader' );
}

/**
 * buddyx_add_post_meta_box.
 */
add_action( 'add_meta_boxes', 'buddyx_add_post_meta_box' );
if ( ! function_exists( 'buddyx_add_post_meta_box' ) ) {
	function buddyx_add_post_meta_box() {
		global $post;

		add_meta_box(
			'buddyx_post_settings',
			__( 'Post Settings', 'buddyx' ),
			'render_buddyx_add_post_settings_meta_box',
			array( 'post' ),
			'normal',
			'high'
		);

		add_meta_box(
			'buddyx_postformat_settings',
			__( 'Post Format Settings', 'buddyx' ),
			'render_buddyx_add_post_format_meta_box',
			array( 'post' ),
			'normal',
			'high'
		);
	}
}

if ( ! function_exists( 'render_buddyx_add_post_settings_meta_box' ) ) {
	function render_buddyx_add_post_settings_meta_box( $post ) {
		$post_id         = $post->ID;
		$title_overwrite = get_post_meta( $post_id, '_post_title_overwrite', true );
		$title_position  = get_post_meta( $post_id, '_post_title_position', true );
		?>
		<div class="buddyx_post_settings">
			<div class="buddyx-field buddyx-checkbox">
				<label>
					<input type="checkbox" name="_post_title_overwrite" value='yes' <?php checked( $title_overwrite, 'yes' ); ?>/>
					<?php esc_html_e( 'Overwrite Title Customizer settings', 'buddyx' ); ?>
				</label>
			</div>
			<div class="buddyx-field buddyx-radio">
				<div>
					<h4><?php esc_html_e( 'Title Style', 'buddyx' ); ?></h4>
				</div>
				<ul class="buddyx_radio_list">
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-over" <?php checked( $title_position, 'title-over' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-1.png' ); ?>" class="post-title-image"/>
						</label>
					</li>
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="half" <?php checked( $title_position, 'half' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-2.png' ); ?>" class="post-title-image"/>
						</label>
					</li>
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-above" <?php checked( $title_position, 'title-above' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-3.png' ); ?>" class="post-title-image"/>
						</label>
					</li>
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-below" <?php checked( $title_position, 'title-below' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-4.png' ); ?>" class="post-title-image"/>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'render_buddyx_add_post_format_meta_box' ) ) {
	function render_buddyx_add_post_format_meta_box( $post ) {
		$post_format       = get_post_format( $post );
		$post_id           = $post->ID;
		$post_video        = get_post_meta( $post_id, '_buddyx_post_video', true );
		$post_audio        = get_post_meta( $post_id, '_buddyx_post_audio', true );
		$post_quote        = get_post_meta( $post_id, '_buddyx_post_quote', true );
		$post_quote_author = get_post_meta( $post_id, '_buddyx_post_quote_author', true );

		$post_link_title    = get_post_meta( $post_id, '_buddyx_post_link_title', true );
		$post_link_url      = get_post_meta( $post_id, '_buddyx_post_link_url', true );
		$post_image_gallery = get_post_meta( $post_id, '_buddyx_image_gallery', true );
		?>
		<div class="buddyx_post_format-settings">
			<input type="hidden" value="<?php echo $post_format; ?>" id="buddyx_post_format"/>
			<div class="buddyx_video_format_setting">
				<p class="description"><?php esc_html_e( 'Enter Youtube, Vimeo and etc video url.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Video URL', 'buddyx' ); ?></label>
					</div>
					<input type="text" id="buddyx_post_video" name="buddyx_post_video" value="<?php echo $post_video; ?>" class="buddyx-input-text"/>
					<a href="javascript:void(0);" class="buddyx_upload_media option-tree-ui-button button button-primary light" data-id="buddyx_post_video" rel="<?php echo esc_attr( $post_id ); ?>" title="<?php esc_attr_e( 'Add Media', 'buddyx' ); ?>">
						<span class="dashicons dashicons-insert"></span>
					</a>
				</div>
			</div>

			<div class="buddyx_audio_format_setting">
				<p class="description"><?php esc_html_e( 'Enter audio url.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Audio URL', 'buddyx' ); ?></label>
					</div>
					<input type="text" id="buddyx_post_audio" name="buddyx_post_audio" value="<?php echo $post_audio; ?>" class="buddyx-input-text"/>
					<a href="javascript:void(0);" class="buddyx_upload_media option-tree-ui-button button button-primary light" data-id="buddyx_post_audio" rel="<?php echo esc_attr( $post_id ); ?>" title="<?php esc_attr_e( 'Add Media', 'buddyx' ); ?>">
						<span class="dashicons dashicons-insert"></span>
					</a>
				</div>
			</div>

			<div class="buddyx_quote_format_setting">
				<p class="description"><?php esc_html_e( 'Input your quote.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Quote Text', 'buddyx' ); ?></label>
					</div>
					<textarea name="buddyx_post_quote" class="buddyx-input-textare"><?php echo $post_quote; ?></textarea>
				</div>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Quote Author', 'buddyx' ); ?></label>
					</div>
					<input type="text" name="buddyx_post_quote_author" value="<?php echo $post_quote_author; ?>" class="buddyx-input-text"/>
				</div>
			</div>

			<div class="buddyx_link_format_setting">
				<p class="description"><?php esc_html_e( 'Input your link.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Link Title', 'buddyx' ); ?></label>
					</div>
					<input type="text" name="buddyx_post_link_title" value="<?php echo $post_link_title; ?>" class="buddyx-input-text"/>
				</div>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Link URL', 'buddyx' ); ?></label>
					</div>
					<input type="text" name="buddyx_post_link_url" value="<?php echo $post_link_url; ?>" class="buddyx-input-text"/>
				</div>
			</div>

			<div class="buddyx_gallery_format_setting">
				<p class="description"><?php esc_html_e( 'To create a gallery, upload your images and then select "Uploaded to this post" from the dropdown (in the media popup) to see images attached to this post. You can drag to re-order or delete them there.', 'buddyx' ); ?></p>
				<div id="images_gallery_container" class="buddyx_images_gallery_container">
					<ul class="buddyx_images_gallery images_gallery">
					<?php
						$image_gallery = '';
					if ( ! empty( $post_image_gallery ) ) {
						$post_image_gallery = explode( ',', $post_image_gallery );

						foreach ( $post_image_gallery as $image_id ) {
							if ( trim( $image_id ) != '' ) {
								// $image = wp_get_attachment_image_src($image_id, 'thumbnail');

								echo '<li class="image" data-attachment_id="' . $image_id . '">
										<div class="attachment-preview type-image">
											<div class="thumbnail">
												<div class="centered">
												' . wp_get_attachment_image( $image_id, 'thumbnail' ) . '
												</div>
											</div>
										</div>

										<div class="actions">
											<a href="#" id="' . $image_id . '" class="delete" title="' . __( 'Delete image', 'buddyx' ) . '"><i class="dashicons dashicons-no"></i></a>
										</div>
									</li>';
								$image_gallery .= $image_id . ',';
							}
						}
					}

					?>
					</ul>
					<input type="hidden" id="buddyx_image_gallery" name="buddyx_image_gallery" value="<?php echo esc_attr( substr( @$image_gallery, 0, -1 ) ); ?>" />
				</div>
				<div class="clearfix buddyx_image_gallery_description">
					<p class="add_buddyx_images hide-if-no-js">
						<a class="components-button is-primary" href="#"><?php echo __( 'Add images gallery', 'buddyx' ); ?></a>
					</p>
				</div>
			</div>


			<script>
			( function ( $ ) {
				'use strict';
				$('#buddyx_postformat_settings').hide();
				$( document ).ready( function () {

					var post_format = $('input[name=post_format]:checked').val();
					if ( typeof post_format == 'undefined' ) {
						post_format = $('#buddyx_post_format').val();
					}
					if ( post_format == 'video' || post_format == 'audio' || post_format == 'quote' || post_format == 'link' || post_format == 'gallery' ) {
						$('#buddyx_postformat_settings').show();
						$( '.buddyx_video_format_setting').hide();
						$( '.buddyx_audio_format_setting').hide();
						$( '.buddyx_quote_format_setting').hide();
						$( '.buddyx_link_format_setting').hide();
						$( '.buddyx_gallery_format_setting').hide();
						$( '.buddyx_' + post_format + '_format_setting').show();
					}


					$(document).on( "change", 'input[name=post_format], .editor-post-format__content select.components-select-control__input' , function(e){
						var post_format = $( this ).val();
						$( '.buddyx_video_format_setting').hide();
						$( '.buddyx_audio_format_setting').hide();
						$( '.buddyx_quote_format_setting').hide();
						$( '.buddyx_link_format_setting').hide();
						$( '.buddyx_gallery_format_setting').hide();
						if ( post_format == 'video' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_video_format_setting').show();
						} else if ( post_format == 'audio' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_audio_format_setting').show();
						} else if ( post_format == 'quote' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_quote_format_setting').show();
						} else if ( post_format == 'link' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_link_format_setting').show();
						}else if ( post_format == 'gallery' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_gallery_format_setting').show();
						} else {
							$('#buddyx_postformat_settings').hide();
						}
					});

					/* Uploading files */
					var image_gallery_frame;
					var $image_gallery_ids = $('#buddyx_image_gallery');
					var $images_gallery = $('#images_gallery_container ul.images_gallery');
					$('.add_buddyx_images').on( 'click', 'a', function( event ) {
						var $el = $(this);
						var attachment_ids = $image_gallery_ids.val();
						event.preventDefault();
						/* If the media frame already exists, reopen it. */
						if ( image_gallery_frame ) {
							image_gallery_frame.open();
							return;
						}
						/* Create the media frame.  */
						image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
							/* Set the title of the modal.  */
							title: '<?php echo __( 'Add images gallery', 'buddyx' ); ?>',
							button: {
								text: '<?php echo __( 'Add to gallery', 'buddyx' ); ?>',
							},
							multiple: true
						});
						/* When an image is selected, run a callback.  */
						image_gallery_frame.on( 'select', function() {
							var selection = image_gallery_frame.state().get('selection');
							selection.map( function( attachment ) {
								attachment = attachment.toJSON();
								if ( attachment.id ) {
									attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
									$images_gallery.append('\
										<li class="image" data-attachment_id="' + attachment.id + '">\
											<div class="attachment-preview type-image">\
												<div class="thumbnail">\
													<div class="centered">\
														<img src="' + attachment.url + '" />\
													</div>\
												</div>\
											</div>\
											<div class="actions">\
												<a href="#" class="delete" title="<?php echo __( 'Delete image', 'buddyx' ); ?>"><i class="dashicons dashicons-no"></i></a>\
											</div>\
										</li>');
								}
							} );
							$image_gallery_ids.val( attachment_ids );
						});
						/* Finally, open the modal. */
						image_gallery_frame.open();
					});
					/* Image ordering */
					$images_gallery.sortable({
						items: 'li.image',
						cursor: 'move',
						scrollSensitivity:40,
						forcePlaceholderSize: true,
						forceHelperSize: false,
						helper: 'clone',
						opacity: 0.65,
						placeholder: 'wc-metabox-sortable-placeholder',
						start:function(event,ui){
							ui.item.css('background-color','#f6f6f6');
						},
						stop:function(event,ui){
							ui.item.removeAttr('style');
						},
						update: function(event, ui) {
							var attachment_ids = '';
							$('#images_gallery_container ul li.image').css('cursor','default').each(function() {
								var attachment_id = $(this).attr( 'data-attachment_id' );
								attachment_ids = attachment_ids + attachment_id + ',';
							});
							$image_gallery_ids.val( attachment_ids );
						}
					});
					/* Remove images */
					$('#images_gallery_container').on( 'click', 'a.delete', function() {

						$(this).closest('li.image').remove();
						var attachment_ids = '';
						$('#images_gallery_container ul li.image').css('cursor','default').each(function() {
							var attachment_id = $(this).attr( 'data-attachment_id' );
							attachment_ids = attachment_ids + attachment_id + ',';
						});
						$image_gallery_ids.val( attachment_ids );
						return false;
					} );


					$('.buddyx_upload_media').on( 'click',  function( event ) {
						var $el = $(this);
						var media_id = $(this).data( 'id' );
						event.preventDefault();
						/* If the media frame already exists, reopen it. */
						if ( image_gallery_frame ) {
							image_gallery_frame.open();
							return;
						}
						/* Create the media frame.  */
						image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
							/* Set the title of the modal.  */
							title: '<?php echo __( 'Add Media', 'buddyx' ); ?>',
							button: {
								text: '<?php echo __( 'Add to Media', 'buddyx' ); ?>',
							},
							multiple: true
						});
						/* When an image is selected, run a callback.  */
						image_gallery_frame.on( 'select', function() {
							var selection = image_gallery_frame.state().get('selection');
							selection.map( function( attachment ) {
								attachment = attachment.toJSON();
								if ( attachment.id ) {

									$( '#' + media_id ).val(attachment.url);
								}
							} );
						});
						/* Finally, open the modal. */
						image_gallery_frame.open();
					});

				});
			} )( jQuery );
			</script>

		</div>
		<?php
	}
}

add_action( 'save_post', 'buddyx_save_post_meta', 10, 1 );
if ( ! function_exists( 'buddyx_save_post_meta' ) ) {
	function buddyx_save_post_meta( $post_id ) {
		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'post' ) {
			if ( isset( $_POST['buddyx_post_video'] ) ) {
				update_post_meta( $post_id, '_buddyx_post_video', sanitize_text_field( wp_unslash( $_POST['buddyx_post_video'] ) ) );
			}
			if ( isset( $_POST['buddyx_post_audio'] ) ) {
				update_post_meta( $post_id, '_buddyx_post_audio', sanitize_text_field( wp_unslash( $_POST['buddyx_post_audio'] ) ) );
			}
			if ( isset( $_POST['buddyx_post_quote'] ) ) {
				update_post_meta( $post_id, '_buddyx_post_quote', sanitize_text_field( wp_unslash( $_POST['buddyx_post_quote'] ) ) );
				update_post_meta( $post_id, '_buddyx_post_quote_author', sanitize_text_field( wp_unslash( $_POST['buddyx_post_quote_author'] ) ) );
			}
			if ( isset( $_POST['buddyx_post_link_title'] ) ) {
				update_post_meta( $post_id, '_buddyx_post_link_title', sanitize_text_field( wp_unslash( $_POST['buddyx_post_link_title'] ) ) );
				update_post_meta( $post_id, '_buddyx_post_link_url', sanitize_text_field( wp_unslash( $_POST['buddyx_post_link_url'] ) ) );
			}
			if ( isset( $_POST['buddyx_image_gallery'] ) ) {
				update_post_meta( $post_id, '_buddyx_image_gallery', sanitize_text_field( wp_unslash( $_POST['buddyx_image_gallery'] ) ) );
			}
			if ( isset( $_POST['_post_title_overwrite'] ) ) {
				update_post_meta( $post_id, '_post_title_overwrite', $_POST['_post_title_overwrite'] );
			}
			if ( isset( $_POST['_post_title_position'] ) ) {
				update_post_meta( $post_id, '_post_title_position', sanitize_text_field( wp_unslash( $_POST['_post_title_position'] ) ) );
			}
		}
	}
}

/**
 * Function will add feature image for blog post in the activity feed content.
 *
 * @param string $content
 * @param int    $blog_post_id
 *
 * @return string $content
 *
 * @since 4.2.2
 */
function buddyx_add_feature_image_blog_post_as_activity_content_callback( $content, $blog_post_id ) {
	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {
		if ( ! empty( $blog_post_id ) && ! empty( get_post_thumbnail_id( $blog_post_id ) ) ) {
			$content .= sprintf( ' <a class="buddyx-post-img-link" href="%s"><img src="%s" /></a>', esc_url( get_permalink( $blog_post_id ) ), esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( $blog_post_id ), 'full' ) ) );
		}
	}

	return $content;
}

add_filter( 'buddyx_add_feature_image_blog_post_as_activity_content', 'buddyx_add_feature_image_blog_post_as_activity_content_callback', 10, 2 );

add_action( 'bp_before_activity_activity_content', 'buddyx_bp_blogs_activity_content_set_temp_content' );

/**
 * Function which set the temporary content on the blog post activity.
 *
 * @since 4.2.2
 */
function buddyx_bp_blogs_activity_content_set_temp_content() {

	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {

		global $activities_template;

		$activity = $activities_template->activity;
		if ( ( 'blogs' === $activity->component ) && isset( $activity->secondary_item_id ) && 'new_blog_' . get_post_type( $activity->secondary_item_id ) === $activity->type ) {
			$content = get_post( $activity->secondary_item_id );
			// If we converted $content to an object earlier, flip it back to a string.
			if ( is_a( $content, 'WP_Post' ) ) {
				$activities_template->activity->content = '&#8203;';
			}
		} elseif ( 'blogs' === $activity->component && 'new_blog_comment' === $activity->type && $activity->secondary_item_id && $activity->secondary_item_id > 0 ) {
			$activities_template->activity->content = '&#8203;';
		}
	}
}

add_filter( 'bp_get_activity_content_body', 'buddyx_bp_blogs_activity_content_with_read_more', 9999, 2 );

/**
 * Function which set the content on activity blog post.
 *
 * @param $content
 * @param $activity
 *
 * @return string
 *
 * @since 4.2.2
 */
function buddyx_bp_blogs_activity_content_with_read_more( $content, $activity ) {

	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {

		if ( ( 'blogs' === $activity->component ) && isset( $activity->secondary_item_id ) && 'new_blog_' . get_post_type( $activity->secondary_item_id ) === $activity->type ) {
			$blog_post = get_post( $activity->secondary_item_id );
			// If we converted $content to an object earlier, flip it back to a string.
			if ( is_a( $blog_post, 'WP_Post' ) ) {
				$content_img = apply_filters( 'buddyx_add_feature_image_blog_post_as_activity_content', '', $blog_post->ID );
				$post_title  = sprintf( '<a class="buddyx-post-title-link" href="%s"><span class="buddyx-post-title">%s</span></a>', esc_url( get_permalink( $blog_post->ID ) ), esc_html( $blog_post->post_title ) );
				$content     = bp_create_excerpt( bp_strip_script_and_style_tags( html_entity_decode( get_the_excerpt( $blog_post->ID ) ) ) );
				if ( false !== strrpos( $content, __( '&hellip;', 'buddyx' ) ) ) {
					$content = str_replace( ' [&hellip;]', '&hellip;', $content );
					$content = apply_filters_ref_array( 'bp_get_activity_content', array( $content, $activity ) );
					preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $content, $matches );
					if ( isset( $matches ) && array_key_exists( 0, $matches ) && ! empty( $matches[0] ) ) {
						$iframe  = $matches[0];
						$content = strip_tags( preg_replace( '/<iframe.*?\/iframe>/i', '', $content ), '<a>' );

						$content .= $iframe;
					}
					$content = sprintf( '%1$s <div class="buddyx-content-wrp">%2$s %3$s</div>', $content_img, $post_title, wpautop( $content ) );
				} else {
					$content = apply_filters_ref_array( 'bp_get_activity_content', array( $content, $activity ) );
					$content = strip_tags( $content, '<a><iframe><img><span><div>' );
					preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $content, $matches );
					if ( isset( $matches ) && array_key_exists( 0, $matches ) && ! empty( $matches[0] ) ) {
						$content = $content;
					}
					$content = sprintf( '%1$s <div class="buddyx-content-wrp">%2$s %3$s</div>', $content_img, $post_title, wpautop( $content ) );
				}
			}
		} elseif ( 'blogs' === $activity->component && 'new_blog_comment' === $activity->type && $activity->secondary_item_id && $activity->secondary_item_id > 0 ) {
			$comment = get_comment( $activity->secondary_item_id );
			$content = bp_create_excerpt( html_entity_decode( $comment->comment_content ) );
			if ( false !== strrpos( $content, __( '&hellip;', 'buddyx' ) ) ) {
				$content     = str_replace( ' [&hellip;]', '&hellip;', $content );
				$append_text = apply_filters( 'bp_activity_excerpt_append_text', __( ' Read more', 'buddyx' ) );
				$content     = wpautop( sprintf( '%1$s<span class="activity-blog-post-link"><a href="%2$s" rel="nofollow">%3$s</a></span>', $content, get_comment_link( $activity->secondary_item_id ), $append_text ) );
			}
		}
	}

	return $content;
}

if ( ! function_exists( 'buddyx_viewport_meta' ) ) {

	/**
	 * Add a viewport meta
	 */
	function buddyx_viewport_meta() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
	}

	add_action( 'wp_head', 'buddyx_viewport_meta' );
}
