<?php 
// Content wrapper
if ( !function_exists( 'buddy_content_top' ) ) {

	function buddy_content_top() { ?>
		<div class="container">
			<div class="site-wrapper">
	<?php }

}
add_action( 'buddy_before_content', 'buddy_content_top' );

if ( !function_exists( 'buddy_content_bottom' ) ) {

	function buddy_content_bottom() { ?>
		</div></div>
	<?php }

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
