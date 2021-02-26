<?php
/**
 * The `buddyx()` extra.
 *
 * @package buddyx
 */

// Content wrapper
if ( !function_exists( 'buddyx_content_top' ) ) {
	function buddyx_content_top() { ?>
		<div class="site-wrapper">
	<?php }
}

add_action( 'buddyx_before_content', 'buddyx_content_top' );

if ( !function_exists( 'buddyx_content_bottom' ) ) {
	function buddyx_content_bottom() { ?>
		</div>
	<?php }
}

add_action( 'buddyx_after_content', 'buddyx_content_bottom' );

// Site Sub Header
if ( !function_exists( 'buddyx_sub_header' ) ) {
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
			if ( get_post_type() === 'post' || is_single() || is_archive( 'post-type-archive-forum' ) && ( function_exists( 'is_shop' ) && ! is_shop() ) ) {
				get_template_part( 'template-parts/content/page_header' );
				$breadcrumbs = get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					buddyx_the_breadcrumb();
				}
			} elseif ( get_post_type() === 'page' || is_single() ) {
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
<?php }
}

/**
 * BREADCRUMBS
 */
//  to include in functions.php
if ( ! function_exists( 'buddyx_the_breadcrumb' ) ) {
	function buddyx_the_breadcrumb() {

		$wpseo_titles = get_option( 'wpseo_titles' );
		if ( function_exists('yoast_breadcrumb') && isset($wpseo_titles['breadcrumbs-enable']) &&  $wpseo_titles['breadcrumbs-enable'] == 1 ) {

			yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );

		} else {

			$sep = ' » ';

			if ( ! is_front_page() ) {

				// Start the breadcrumb with a link to your homepage
				echo '<div class="buddyx-breadcrumbs">';
				echo '<a href="';
				echo esc_url(home_url());
				echo '">';
				echo esc_html__( 'Home', 'buddyx');
				echo '</a>' . $sep; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
				if ( is_category() || is_single() ){
					the_category(' » ');
				} elseif ( is_archive() || is_single() ){
					if ( is_day() ) {
						printf( esc_html__( '%s', 'buddyx' ), get_the_date() );
					} elseif ( is_month() ) {
						printf( esc_html__( '%s', 'buddyx' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'buddyx' ) ) );
					} elseif ( is_year() ) {
						printf( esc_html__( '%s', 'buddyx' ), get_the_date( _x( 'Y', 'yearly archives date format', 'buddyx' ) ) );
					} elseif (is_author()) {
                                                esc_html_e('Author', 'buddyx');
                                        } elseif( is_shop() ) {
						esc_html_e( 'Shop', 'buddyx' );
					} elseif( is_archive('post-type-archive-forum') ) {
						esc_html_e( 'Forums Archives', 'buddyx' );
					} else {
						esc_html_e( 'Blog Archives', 'buddyx' );
					}
				}

				// If the current page is a single post, show its title with the separator
				if ( is_single() ) {
					echo $sep; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					the_title();
				}

				// If the current page is a static page, show its title.
				if ( is_page() ) {
					the_title();
				}

				// if you have a static page assigned to be you posts list page. It will find the title of the static page.
                if ( is_home() ) {
                    $blog_title = get_option( 'page_for_posts', true );
                    echo esc_html( get_the_title ( $blog_title ) );
                }

				echo '</div>';
			}
		}
	}
}

// Site Loader
if ( !function_exists( 'buddyx_site_loader' ) ) {
	function buddyx_site_loader() {
		$loader	 = get_theme_mod( 'site_loader', buddyx_defaults( 'site-loader' ) );
		if ( $loader == "1" ) {
			echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
		}
	}
}

// Site Search and Woo icon
if ( !function_exists( 'buddyx_site_menu_icon' ) ) {
	function buddyx_site_menu_icon () {
		// menu icons
		$searchicon = (int) get_theme_mod( 'site_search', buddyx_defaults( 'site-search' ) );
		$carticon = (int) get_theme_mod( 'site_cart', buddyx_defaults( 'site-cart' ) );
		if( !empty( $searchicon ) || !empty( $carticon ) ) : ?>
			<div class="menu-icons-wrapper"><?php
				if( !empty( $searchicon ) ): ?>
					<div class="search" <?php echo apply_filters( 'buddyx_search_slide_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<a href="#" id="overlay-search" class="search-icon"> <span class="fa fa-search"> </span> </a>
						<div class="top-menu-search-container" <?php echo apply_filters( 'buddyx_search_field_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php get_search_form(); ?>
						</div>
					</div>
					<?php
				endif;
				if( !empty( $carticon ) && function_exists( "is_woocommerce" ) ) : ?>
					<div class="cart">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View Shopping Cart', 'buddyx' ); ?>">
							<span class="fa fa-shopping-cart"> </span><?php
							$count = WC()->cart->cart_contents_count;
							if( $count > 0 ) : ?>
								<sup><?php echo esc_html( $count );?></sup><?php
							endif;?>
						</a>
					</div><?php
				endif; ?>
			</div><?php
		endif;
	}
}

// buddyx_bp_get_activity_css_first_class
if ( !function_exists( 'buddyx_bp_get_activity_css_first_class' ) ) {
	function buddyx_bp_get_activity_css_first_class() {
		global $activities_template;
		/**
		 * Filters the available mini activity actions available as CSS classes.
		 *
		 * @since 1.2.0
		 *
		 * @param array $value Array of classes used to determine classes applied to HTML element.
		 */
		$mini_activity_actions = apply_filters( 'bp_activity_mini_activity_types', array(
			'friendship_accepted',
			'friendship_created',
			'new_blog',
			'joined_group',
			'created_group',
			'new_member'
		) );
		return apply_filters( 'buddyx_bp_get_activity_css_first_class', $activities_template->activity->component );
	}
}

/**
 * Is the current user online
 *
 * @param $user_id
 *
 * @return bool
 */
if ( !function_exists( 'buddyx_is_user_online' ) ) {

	function buddyx_is_user_online( $user_id ) {

		if( !function_exists( 'bp_get_user_last_activity' ) ) {
			return;
		}

		$last_activity = strtotime( bp_get_user_last_activity( $user_id ) );

		if ( empty( $last_activity ) ) {
			return false;
		}

		// the activity timeframe is 5 minutes
		$activity_timeframe = 5 * MINUTE_IN_SECONDS;
		return ( time() - $last_activity <= $activity_timeframe );
	}

}

/**
 * BuddyPress user status
 *
 * @param $user_id
 *
 */
if ( !function_exists( 'buddyx_user_status' ) ) {

	function buddyx_user_status( $user_id ) {
		if( buddyx_is_user_online( $user_id ) ) {
			echo '<span class="member-status online"></span>';
		}
	}
}

/**
 * woocommerce_cart_collaterals
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_form', 'woocommerce_cross_sell_display', 10 );


/* Ensure cart contents update when products are added to the cart via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', 'buddyx_header_add_to_cart_fragment' );

if ( !function_exists( 'buddyx_header_add_to_cart_fragment' ) ) {
	function buddyx_header_add_to_cart_fragment( $fragments ) {
		$count = WC()->cart->get_cart_contents_count();
		ob_start();
		?>
		<a class="menu-icons-wrapper cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'buddyx' ); ?>">
			<span class="fa fa-shopping-cart"></span>
			<sup><?php echo esc_html( $count ); ?></sup>
		</a>
		<?php
		$fragments[ '.menu-icons-wrapper .cart a' ] = ob_get_clean();
		return $fragments;
	}
}

/**
 * buddyx_disable_woo_commerce_sidebar
 */
if ( !function_exists( 'buddyx_disable_woo_commerce_sidebar' ) ) {
	function buddyx_disable_woo_commerce_sidebar() {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	}
}
add_action('init', 'buddyx_disable_woo_commerce_sidebar');

/*
 * Output badges on profile
 */
if ( !function_exists( 'buddyx_profile_achievements' ) ) {
	function buddyx_profile_achievements() {
        if ( class_exists( 'BadgeOS' ) ) {
            global $blog_id, $post;
            $type = "all";
            $limit = apply_filters( 'buddyx_user_badges_limit', 10 );
            $offset = 0;
            $count = 0;
            $filter = "completed";
            $search = false;
            $orderby = "menu_order";
            $order = "ASC";
            $wpms = false;
            $include = array();
            $exclude = array();
            $meta_key = '';
            $meta_value = '';
            $old_post = $post;
            $user_id = bp_displayed_user_id();
            // Convert $type to properly support multiple achievement types
            if ( 'all' == $type ) {
                $type = badgeos_get_achievement_types_slugs();
                // Drop steps from our list of "all" achievements
                $step_key = array_search( 'step', $type );
                if  ($step_key )
                    unset($type[$step_key]);
            } else {
                $type = explode( ',', $type );
            }
            // Build $include array
            if ( ! is_array( $include ) ) {
                $include = explode( ',', $include );
            }
            // Build $exclude array
            if ( ! is_array( $exclude ) ) {
                $exclude = explode( ',', $exclude );
            }
            // Initialize our output and counters
            $achievements = '';
            $achievement_count = 0;
            $query_count = 0;
            // Grab our hidden badges (used to filter the query)
            $hidden = badgeos_get_hidden_achievement_ids( $type );
            // If we're polling all sites, grab an array of site IDs
            if ( $wpms && $wpms != 'false' ) {
                $sites = badgeos_get_network_site_ids();
            } else {
                // Otherwise, use only the current site
                $sites = array( $blog_id );
            }
            // Loop through each site (default is current site only)
            foreach ( $sites as $site_blog_id ) {
                // If we're not polling the current site, switch to the site we're polling
                if ( $blog_id != $site_blog_id ) {
                    switch_to_blog( $site_blog_id );
                }
                // Grab our earned badges (used to filter the query)
                $earned_ids = badgeos_get_user_earned_achievement_ids( $user_id, $type );
                // Query Achievements
                $args = array(
                    'post_type' => $type,
                    'orderby' => $orderby,
                    'order' => $order,
                    'posts_per_page' => $limit,
                    'offset' => $offset,
                    'post_status' => 'publish',
                    'post__not_in' => array_diff( $hidden, $earned_ids )
                );
                // Filter - query completed or non completed achievements
                if ( $filter == 'completed' ) {
                    $args['post__in'] = array_merge( array(0), $earned_ids );
                } elseif ($filter == 'not-completed') {
                    $args['post__not_in'] = array_merge( $hidden, $earned_ids );
                }
                if ( '' !== $meta_key && '' !== $meta_value ) {
                    $args['meta_key'] = $meta_key;
                    $args['meta_value'] = $meta_value;
                }
                // Include certain achievements
                if ( ! empty( $include ) ) {
                    $args['post__not_in'] = array_diff( $args['post__not_in'], $include );
                    $args['post__in'] = array_merge( array(0), array_diff( $include, $args['post__in'] ) );
                }
                // Exclude certain achievements
                if ( ! empty( $exclude ) ) {
                    $args['post__not_in'] = array_merge( $args['post__not_in'], $exclude );
                }
                // Search
                if ( $search ) {
                    $args['s'] = $search;
                }
                // Loop Achievements
                $achievement_posts = new WP_Query( $args );
                $query_count += $achievement_posts->found_posts;
                while ( $achievement_posts->have_posts() ) : $achievement_posts->the_post();
                    // If we were given an ID, get the post
                    if ( is_numeric( get_the_ID() ) ) {
                        $achievement = get_post( get_the_ID() );
                    }
                    $achievements .= '<div class="ps-badgeos__item ps-badgeos__item--focus" >';
                    $achievements .= '<a href="' . get_permalink( $achievement->ID ) . '">' . badgeos_get_achievement_post_thumbnail( $achievement->ID ) . '</a>';
                    $achievements .= '</div>';
                    $achievement_count++;
                endwhile;
                wp_reset_query();
                $post = $old_post;
            }
            echo '<div class="ps-badgeos__list-wrapper">';
            echo '<div class="ps-badgeos__list-title">' . _n( 'Recently earned badge', 'Recently earned badges', $achievement_count, 'buddyx' ) . '</div>';
            echo '<div class="ps-badgeos__list">' . $achievements . '</div>';
            echo '</div>';
        }
    }
}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'buddyx_footer_custom_text' ) ) {
    /**
     * Function Footer Custom Text
     *
     * @since 1.0.14
     * @param string $option Custom text option name.
     * @return mixed         Markup of custom text option.
     */
    function buddyx_footer_custom_text() {
        $copyright = esc_html( get_theme_mod( 'site_copyright_text' ) );
        $output = $copyright;
        if ( '' != $output ) {
            $output = str_replace( '[current_year]', date_i18n( 'Y' ), $output );
            $output = str_replace( '[site_title]', '<span class="buddyx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $output );
            $theme_author = apply_filters(
                    'buddyx_theme_author',
                    array(
                            'theme_name'       => __( 'BuddyX WordPress Theme', 'buddyx' ),
                            'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/buddyx-theme/' ),
                    )
            );
            $output = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $output );
        }
        return apply_filters( 'buddyx_footer_copyright_text', $output, $copyright );
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
            echo '<span class="entry-cat-links">' . get_the_category_list(', ') . '</span>';
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
 * Managing Login and Register URL in Frontend
 * 
 */

if ( ! function_exists( 'buddyx_alter_login_url_at_frontend' ) ) {

    add_filter( 'login_url', 'buddyx_alter_login_url_at_frontend', 10, 3 );

    function buddyx_alter_login_url_at_frontend( $login_url, $redirect, $force_reauth ) {
        if ( is_admin() ) {
            return $login_url;
        }

        $buddyx_login_page_id = get_theme_mod( 'buddyx_login_page', '0' );
        if ($buddyx_login_page_id) {
            $buddyx_login_page_url = get_permalink( $buddyx_login_page_id );
            if ( $buddyx_login_page_url ) {
                $login_url = $buddyx_login_page_url;
            }
        }
        return $login_url;
    }
}

if ( ! function_exists( 'buddyx_alter_register_url_at_frontend' ) ) {

    add_filter( 'register_url', 'buddyx_alter_register_url_at_frontend', 10, 1 );

    function buddyx_alter_register_url_at_frontend( $register_url ) {
        if ( is_admin() ) {
            return $register_url;
        }

        $buddyx_registration_page_id = get_theme_mod( 'buddyx_registration_page', '0' );
        if ( $buddyx_registration_page_id ) {
            $buddyx_registration_page_url = get_permalink( $buddyx_registration_page_id );
            if ( $buddyx_registration_page_url ) {
                $register_url = $buddyx_registration_page_url;
            }
        }
        return $register_url;
    }

}

/**
 * Redirect to selected login page from options.
 */
if ( ! function_exists( 'buddyx_redirect_login_page' ) ) {
    function buddyx_redirect_login_page() {

        /* removing conflict with logout url */
        if ( isset($_GET['action']) && ( $_GET['action'] == 'logout' ) ) {
            return;
        }

        global $wbtm_buddyx_settings;
        $login_page_id = $wbtm_buddyx_settings['buddyx_pages']['buddyx_login_page'];
        $register_page_id = $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'];

        $login_page = get_permalink($login_page_id);
        $register_page = get_permalink($register_page_id);
        $page_viewed_url = basename($_SERVER['REQUEST_URI']);
        $exploded_Url = wp_parse_url($page_viewed_url);

        if ( ! isset( $exploded_Url['path'] ) ) {
            return;
        }

        // For register page
        if ( $register_page && 'wp-login.php' == $exploded_Url['path'] && 'action=register' == $exploded_Url['query'] && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            wp_redirect( $register_page );
            exit;
        }

        // For login page
        if ( $login_page && $exploded_Url['path'] == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            wp_redirect( $login_page );
            exit;
        }
    }
}

/**
 * Add 404 page redirect
 */
if ( ! function_exists( 'buddyx_404_redirect' ) ) {
    function buddyx_404_redirect() {

        // media popup fix
        if ( strpos( $_SERVER['REQUEST_URI'], "media" ) !== false ) {
            return;
        }

        // media upload fix
        if ( strpos( $_SERVER['REQUEST_URI'], "upload" ) !== false ) {
            return;
        }

        if ( ! is_404() ) {
            return;
        }

        $buddyx_404_page_id = get_theme_mod( 'buddyx_404_page', '0' );

        if ( $buddyx_404_page_id ) {
            $buddyx_404_page_url = get_permalink( $buddyx_404_page_id );
            wp_redirect( $buddyx_404_page_url );
            exit;
        }

    }
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
 * @return   void
 */
if ( ! function_exists( 'buddyx_llms_theme_support' ) ) {
    function buddyx_llms_theme_support(){

        add_theme_support( 'lifterlms-sidebars' );

    }
    add_action( 'after_setup_theme', 'buddyx_llms_theme_support' );
}

/**
 * Example usage for learndash-focus-header-usermenu-after action.
 */
add_action( 'learndash-focus-header-usermenu-after', function( $course_id, $user_id ) { ?>
		<a href="#" id="buddyx-toggle-track">
			<span class="learndash-dark-mode"><i class="fa fa-moon-o"></i></span>
			<span class="learndash-light-mode"><i class="fa fa-sun-o"></i></span>
		</a>
    <?php },
    10,
    2
);