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
		echo '<div class="loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
}
