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

	/**
	 * Swap the theme's buddypress/ template folder for bb-buddypress/ on BuddyBoss Platform.
	 *
	 * @param array $stack Template stack paths.
	 * @return array Filtered template stack.
	 */
	function buddyx_bp_get_template_stack( $stack ) {
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		}
		if ( 'nouveau' === $theme_package_id ) {
			if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
				$index = array_search( get_stylesheet_directory() . '/buddypress', $stack, true );
				if ( false !== $index ) {
					$stack[ $index ] = get_stylesheet_directory() . '/bb-buddypress';
				}
				$index = array_search( get_template_directory() . '/buddypress', $stack, true );
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

		// Get the component for the current activity, guarding against a missing template or property.
		$component = isset( $activities_template->activity->component ) ? (string) $activities_template->activity->component : '';

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

	/**
	 * Print the online status dot for a member.
	 *
	 * @param int $user_id Member ID.
	 * @return void
	 */
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

	/**
	 * Print the member cover image on the members directory card.
	 *
	 * @return void
	 */
	function buddyx_render_member_cover_image() {
		$cover_img_url = bp_attachments_get_attachment(
			'url',
			$args      = array(
				'object_dir' => 'members',
				'item_id'    => $user_id = bp_get_member_user_id(),
				'type'       => 'cover-image',
			)
		);
		// Only render the <img> when a cover exists - an empty src is invalid
		// HTML and makes browsers re-request the page URL as an image. The
		// wrapper stays so the CSS gradient fallback still paints the card.
		$cover_img = $cover_img_url ? '<img src="' . esc_url( $cover_img_url ) . '" alt="" loading="lazy" />' : '';
		echo '<div class="buddyx-mem-cover-wrapper"><div class="buddyx-mem-cover-img">' . $cover_img . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $cover_img is built from esc_url() above.
	}

}

/*
 * Showing group cover image on groups directory page
 */
if ( ! function_exists( 'buddyx_render_group_cover_image' ) ) {
	if ( ! bp_disable_group_cover_image_uploads() ) {
		add_action( 'buddyx_before_group_avatar_group_directory', 'buddyx_render_group_cover_image', 10 );
	}

	/**
	 * Print the group cover image on the groups directory card.
	 *
	 * @return void
	 */
	function buddyx_render_group_cover_image() {
		$cover_img_url = bp_attachments_get_attachment(
			'url',
			$args      = array(
				'object_dir' => 'groups',
				'item_id'    => $group_id = bp_get_group_id(),
				'type'       => 'cover-image',
			)
		);
		// Only render the <img> when a cover exists - an empty src is invalid
		// HTML and makes browsers re-request the page URL as an image. The
		// wrapper stays so the CSS gradient fallback still paints the card.
		$cover_img = $cover_img_url ? '<img src="' . esc_url( $cover_img_url ) . '" alt="" loading="lazy" />' : '';
		echo '<div class="buddyx-grp-cover-wrapper"><div class="buddyx-grp-cover-img">' . $cover_img . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $cover_img is built from esc_url() above.
	}

}

/*
 * Output badges on profile
 */
if ( ! function_exists( 'buddyx_profile_achievements' ) ) {
	/**
	 * Print the BadgeOS badges the displayed member has earned.
	 *
	 * @return void
	 */
	function buddyx_profile_achievements() {
		if ( ! class_exists( 'BadgeOS' ) ) {
			return;
		}

		$user_id = bp_displayed_user_id();

		// Every achievement type except steps, which are parts of a badge rather than badges.
		$types      = array_values( array_diff( badgeos_get_achievement_types_slugs(), array( 'step' ) ) );
		$hidden     = badgeos_get_hidden_achievement_ids( $types );
		$earned_ids = badgeos_get_user_earned_achievement_ids( $user_id, $types );

		$achievement_posts = new WP_Query(
			array(
				'post_type'              => $types,
				'orderby'                => 'menu_order',
				'order'                  => 'ASC',
				/**
				 * Filters the maximum number of badges shown for a member.
				 *
				 * @param int $limit Limit.
				 */
				'posts_per_page'         => apply_filters( 'buddyx_user_badges_limit', 10 ),
				'post_status'            => 'publish',
				'post__in'               => array_merge( array( 0 ), $earned_ids ),
				'post__not_in'           => array_diff( $hidden, $earned_ids ),
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
			)
		);

		// The thumbnail is plugin-generated <img> markup. Escape it with the post allowlist,
		// extended so responsive-image attributes are not stripped.
		$thumbnail_kses        = wp_kses_allowed_html( 'post' );
		$thumbnail_kses['img'] = array_merge(
			isset( $thumbnail_kses['img'] ) ? $thumbnail_kses['img'] : array(),
			array(
				'srcset'        => true,
				'sizes'         => true,
				'loading'       => true,
				'decoding'      => true,
				'fetchpriority' => true,
			)
		);

		$achievements      = '';
		$achievement_count = 0;
		while ( $achievement_posts->have_posts() ) :
			$achievement_posts->the_post();
			$achievement_id = get_the_ID();
			$achievements  .= '<div class="ps-badgeos__item ps-badgeos__item--focus" >';
			$achievements  .= '<a href="' . esc_url( get_permalink( $achievement_id ) ) . '">' . wp_kses( badgeos_get_achievement_post_thumbnail( $achievement_id ), $thumbnail_kses ) . '</a>';
			$achievements  .= '</div>';
			++$achievement_count;
		endwhile;
		wp_reset_postdata();

		echo '<div class="ps-badgeos__list-wrapper">';
		echo '<div class="ps-badgeos__list-title">' . esc_html( _n( 'Recently earned badge', 'Recently earned badges', $achievement_count, 'buddyx' ) ) . '</div>';
		echo '<div class="ps-badgeos__list">' . $achievements . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Each part is escaped when built above.
		echo '</div>';
	}
}
