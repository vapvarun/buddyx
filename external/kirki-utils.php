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
        
        // site breadcrumbs
        $defaults[ 'site-breadcrumbs' ] = 'on';

	// blog layout option
	$defaults['blog-layout-option'] = 'default-layout';
         
        // single blog layout option
        $defaults[ 'single-post-layout' ] = '1';

        // single blog breadcrumbs
        $defaults[ 'single-post-breadcrumbs' ] = 'on';

        // single blog meta
        $defaults[ 'single-post-meta' ] = 'on';

        // single blog categories
        $defaults[ 'single-post-categories' ] = 'on';

	// post per view
	$defaults['post-per-row'] = '3';

	if ( ! empty( $key ) && array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}

	return '';
}
