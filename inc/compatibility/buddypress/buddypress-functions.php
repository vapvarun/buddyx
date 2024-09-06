<?php
/**
 * Custom functions for BuddyPress
 *
 * @package buddyx
 */

defined( 'ABSPATH' ) || exit;

/*
 * Separate BuddyPress Templates for bb platform
 *
 */
if ( ! function_exists( 'buddyx_bp_get_template_stack' ) ) {

	function buddyx_bp_get_template_stack( $stack ) {
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		}
		if ( 'nouveau' === $theme_package_id ) {
			if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
				$index = array_search( get_stylesheet_directory() . '/buddypress', $stack );
				if ( false !== $index ) {
					$stack[ $index ] = get_stylesheet_directory() . '/bb-buddypress';
				}
				$index = array_search( get_template_directory() . '/buddypress', $stack );
				if ( false !== $index ) {
					$stack[ $index ] = get_template_directory() . '/bb-buddypress';
				}
			}
		}
		return $stack;
	}
}

add_filter( 'bp_get_template_stack', 'buddyx_bp_get_template_stack', 10, 1 );

/*
 * Buddyx_bp_get_activity_css_first_class
 *
 */
if ( ! function_exists( 'buddyx_bp_get_activity_css_first_class' ) ) {
	/**
	 * Get the first CSS class for BuddyPress activity based on the component.
	 *
	 * @return string The CSS class for the activity component.
	 */
	function buddyx_bp_get_activity_css_first_class() {
		global $activities_template;

		// Define the mapping of components to CSS classes.
		$mini_activity_actions = array(
			'xprofile' => __( 'Profile', 'buddyx' ),
			'activity' => __( 'Activity', 'buddyx' ),
			'groups'   => __( 'Groups', 'buddyx' ),
			'bbpress'  => __( 'Forums', 'buddyx' ),
			'friends'  => __( 'Friends', 'buddyx' ),
			'members'  => __( 'Members', 'buddyx' ),
			'blogs'    => __( 'Blogs', 'buddyx' ),
			'business' => __( 'Business', 'buddyx' ),
		);

		// Get the component for the current activity.
		$component = $activities_template->activity->component;

		// Default to 'Activity' if the component is not in the mapping.
		$mini_activity_actions = isset( $mini_activity_actions[ $component ] ) ? $mini_activity_actions[ $component ] : __( 'Activity', 'buddyx' );

		/**
		 * Filter the first CSS class for BuddyPress activity.
		 *
		 * @param string $mini_activity_actions The CSS class for the activity component.
		 * @param string $component   The component of the current activity.
		 */
		return apply_filters( 'buddyx_bp_get_activity_css_first_class', $mini_activity_actions, $component );
	}
}

/*
 * User online function
 */
if ( ! function_exists( 'buddyx_is_user_online' ) ) {
	/**
	 * Check if the current user is online.
	 *
	 * @param int $user_id The ID of the user to check.
	 *
	 * @return bool True if the user is online, false otherwise.
	 */
	function buddyx_is_user_online( $user_id ) {
		if ( ! function_exists( 'bp_get_user_last_activity' ) ) {
			return false;
		}

		// Attempt to get the cached last activity.
		$cache_key     = 'buddyx_last_activity_' . $user_id;
		$last_activity = wp_cache_get( $cache_key );

		if ( false === $last_activity ) {
			// Fetch from the database if not cached.
			$last_activity = strtotime( bp_get_user_last_activity( $user_id ) );
			wp_cache_set( $cache_key, $last_activity, '', 5 * MINUTE_IN_SECONDS );
		}

		if ( empty( $last_activity ) ) {
			return false;
		}

		$activity_timeframe = 5 * MINUTE_IN_SECONDS;
		return time() - $last_activity <= $activity_timeframe;
	}
}

/*
 * BuddyPress user status
 *
 * @param $user_id
 *
 */
if ( ! function_exists( 'buddyx_user_status' ) ) {

	function buddyx_user_status( $user_id ) {
		if ( buddyx_is_user_online( $user_id ) ) {
			echo '<span class="member-status online"></span>';
		}
	}
}

/*
 * showing member cover image on member directory page
 */
if ( ! function_exists( 'buddyx_render_member_cover_image' ) ) {

	if ( ! bp_disable_cover_image_uploads() ) {
		add_action( 'buddyx_before_member_avatar_member_directory', 'buddyx_render_member_cover_image', 10 );
	}

	function buddyx_render_member_cover_image() {
		$cover_img_url = bp_attachments_get_attachment(
			'url',
			$args      = array(
				'object_dir' => 'members',
				'item_id'    => $user_id = bp_get_member_user_id(),
				'type'       => 'cover-image',
			)
		);
		$default_members_cover = '';
		$cover_img_url         = isset( $cover_img_url ) ? $cover_img_url : $default_members_cover;
		echo '<div class="buddyx-mem-cover-wrapper"><div class="buddyx-mem-cover-img"><img src="' . esc_url( $cover_img_url ) . '" /></div></div>';
	}

}

/*
 * Showing group cover image on groups directory page
 */
if ( ! function_exists( 'buddyx_render_group_cover_image' ) ) {
	if ( ! bp_disable_group_cover_image_uploads() ) {
		add_action( 'buddyx_before_group_avatar_group_directory', 'buddyx_render_group_cover_image', 10 );
	}

	function buddyx_render_group_cover_image() {
		$cover_img_url = bp_attachments_get_attachment(
			'url',
			$args      = array(
				'object_dir' => 'groups',
				'item_id'    => $group_id = bp_get_group_id(),
				'type'       => 'cover-image',
			)
		);
		$default_groups_cover = '';
		$cover_img_url        = isset( $cover_img_url ) ? $cover_img_url : $default_groups_cover;
		echo '<div class="buddyx-grp-cover-wrapper"><div class="buddyx-grp-cover-img"><img src="' . esc_url( $cover_img_url ) . '" /></div></div>';
	}

}

/*
 * Output badges on profile
 */
if ( ! function_exists( 'buddyx_profile_achievements' ) ) {
	function buddyx_profile_achievements() {
		if ( class_exists( 'BadgeOS' ) ) {
			global $blog_id, $post;
			$type       = 'all';
			$limit      = apply_filters( 'buddyx_user_badges_limit', 10 );
			$offset     = 0;
			$count      = 0;
			$filter     = 'completed';
			$search     = false;
			$orderby    = 'menu_order';
			$order      = 'ASC';
			$wpms       = false;
			$include    = array();
			$exclude    = array();
			$meta_key   = '';
			$meta_value = '';
			$old_post   = $post;
			$user_id    = bp_displayed_user_id();
			// Convert $type to properly support multiple achievement types.
			if ( 'all' == $type ) {
				$type = badgeos_get_achievement_types_slugs();
				// Drop steps from our list of "all" achievements.
				$step_key = array_search( 'step', $type );
				if ( $step_key ) {
					unset( $type[ $step_key ] );
				}
			} else {
				$type = explode( ',', $type );
			}
			// Build $include array.
			if ( ! is_array( $include ) ) {
				$include = explode( ',', $include );
			}
			// Build $exclude array.
			if ( ! is_array( $exclude ) ) {
				$exclude = explode( ',', $exclude );
			}
			// Initialize our output and counters.
			$achievements      = '';
			$achievement_count = 0;
			$query_count       = 0;
			// Grab our hidden badges (used to filter the query).
			$hidden = badgeos_get_hidden_achievement_ids( $type );
			// If we're polling all sites, grab an array of site IDs
			if ( $wpms && $wpms != 'false' ) {
				$sites = badgeos_get_network_site_ids();
			} else {
				// Otherwise, use only the current site.
				$sites = array( $blog_id );
			}
			// Loop through each site (default is current site only).
			foreach ( $sites as $site_blog_id ) {
				// If we're not polling the current site, switch to the site we're polling.
				if ( $blog_id != $site_blog_id ) {
					switch_to_blog( $site_blog_id );
				}
				// Grab our earned badges (used to filter the query)
				$earned_ids = badgeos_get_user_earned_achievement_ids( $user_id, $type );
				// Query Achievements.
				$args = array(
					'post_type'      => $type,
					'orderby'        => $orderby,
					'order'          => $order,
					'posts_per_page' => $limit,
					'offset'         => $offset,
					'post_status'    => 'publish',
					'post__not_in'   => array_diff( $hidden, $earned_ids ),
				);
				// Filter - query completed or non completed achievements.
				if ( $filter == 'completed' ) {
					$args['post__in'] = array_merge( array( 0 ), $earned_ids );
				} elseif ( $filter == 'not-completed' ) {
					$args['post__not_in'] = array_merge( $hidden, $earned_ids );
				}
				if ( '' !== $meta_key && '' !== $meta_value ) {
					$args['meta_key']   = $meta_key;
					$args['meta_value'] = $meta_value;
				}
				// Include certain achievements.
				if ( ! empty( $include ) ) {
					$args['post__not_in'] = array_diff( $args['post__not_in'], $include );
					$args['post__in']     = array_merge( array( 0 ), array_diff( $include, $args['post__in'] ) );
				}
				// Exclude certain achievements.
				if ( ! empty( $exclude ) ) {
					$args['post__not_in'] = array_merge( $args['post__not_in'], $exclude );
				}
				// Search
				if ( $search ) {
					$args['s'] = $search;
				}
				// Loop Achievements.
				$achievement_posts = new WP_Query( $args );
				$query_count      += $achievement_posts->found_posts;
				while ( $achievement_posts->have_posts() ) :
					$achievement_posts->the_post();
					// If we were given an ID, get the post.
					if ( is_numeric( get_the_ID() ) ) {
						$achievement = get_post( get_the_ID() );
					}
					$achievements .= '<div class="ps-badgeos__item ps-badgeos__item--focus" >';
					$achievements .= '<a href="' . get_permalink( $achievement->ID ) . '">' . badgeos_get_achievement_post_thumbnail( $achievement->ID ) . '</a>';
					$achievements .= '</div>';
					++$achievement_count;
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
