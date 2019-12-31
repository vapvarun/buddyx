<?php 
// Content wrapper
if ( !function_exists( 'buddy_content_top' ) ) {
	function buddy_content_top() { ?>
	<?php if ( ! bp_is_user() && ! bp_is_group_single() ) : ?>
		<div class="container">
		<div class="site-wrapper">
	<?php endif; ?>
	<?php }
}

add_action( 'buddy_before_content', 'buddy_content_top' );

if ( !function_exists( 'buddy_content_bottom' ) ) {
	if ( ! bp_is_user() && ! bp_is_group_single() ) {
		function buddy_content_bottom() { ?>
		<?php if ( ! bp_is_user() && ! bp_is_group_single() && ! bp_is_group_create() ) : ?>
			</div></div>
		<?php endif; ?>
		<?php }
	}
}

add_action( 'buddy_after_content', 'buddy_content_bottom' );

// Site Loader 
function site_loader() {
	$loader	 = get_theme_mod( 'site_loader');
	if ( !empty( $loader ) )
		echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
}

// Site Search and Woo icon
function site_menu_icon () {
	// menu icons
	$searchicon = (int) get_theme_mod( 'site_search' );
	$carticon = (int) get_theme_mod( 'site_cart' );
	if( !empty($searchicon) || !empty($carticon) ) : ?>
		<div class="menu-icons-wrapper"><?php
			if( !empty($searchicon) ): ?>
				<div class="search">
					<a href="javascript:void(0)" id="overlay-search" class="search-icon"> <span class="fa fa-search"> </span> </a>
					<div class="top-menu-search-container">
						<?php get_search_form(); ?>
					</div>
				</div>
				<?php
			endif;
			if( !empty($carticon) && function_exists("is_woocommerce")) : ?>
				<div class="cart">
					<a href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html_e( 'View Shopping Cart', 'buddyx' ); ?>">
						<span class="fa fa-shopping-cart"> </span><?php
						$count = WC()->cart->cart_contents_count;
						if( $count > 0 ) : ?>
							<sup><?php echo "{$count}";?></sup><?php
						endif;?>
					</a>
				</div><?php
			endif; ?>
		</div><?php
	endif;
}

// bp_get_activity_css_first_class
function bp_get_activity_css_first_class() {
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
	return apply_filters( 'bp_get_activity_css_first_class', $activities_template->activity->component );
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