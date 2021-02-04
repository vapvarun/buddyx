<?php

function buddyx_defaults( $key = '' ) {
	$defaults = array();

	// site layout
	$defaults['site-layout'] = 'wide';

	// site loader
	$defaults['site-loader'] = '2';

	// site search
	$defaults['site-search'] = '1';

	// site cart
	$defaults['site-cart'] = '1';

	// site sidebar
	$defaults['sidebar-option']             = 'right';
	$defaults['buddypress-sidebar-option']  = 'both';
	$defaults['bbpress-sidebar-option']     = 'right';
	$defaults['woocommerce-sidebar-option'] = 'right';

	// sticky sidebar
	$defaults['sticky-sidebar'] = '1';

	 // blog layout option
	 $defaults['blog-layout-option'] = 'default-layout';

	 // post per view
	 $defaults['post-per-row'] = '3';

	if ( ! empty( $key ) && array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}

	return '';
}
