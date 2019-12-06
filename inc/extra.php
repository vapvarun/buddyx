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
