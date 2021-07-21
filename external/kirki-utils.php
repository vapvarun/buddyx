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
	$defaults['sidebar-option']                    = 'right';
	$defaults['buddypress-sidebar-option']         = 'both';
	$defaults['buddypress-members-sidebar-option'] = 'right';
	$defaults['buddypress-groups-sidebar-option']  = 'right';
	$defaults['bbpress-sidebar-option']            = 'right';
	$defaults['woocommerce-sidebar-option']        = 'right';

	// sticky sidebar
	$defaults['sticky-sidebar'] = '1';

	// site breadcrumbs
	$defaults['site-breadcrumbs'] = 'on';

	// blog layout option
	$defaults['blog-layout-option'] = 'default-layout';

	// blog image position
	$defaults['blog-image-position'] = 'thumb-left';

	// blog grid columns
	$defaults['blog-grid-columns'] = 'one-column';

	// single post blog layout option
	$defaults['single-post-sidebar-option'] = 'none';

	// blog masonry view
	$defaults['blog-masonry-view'] = 'without-masonry';

	// post per view
	$defaults['post-per-row'] = 'buddyx-masonry-2';

	// single post content width
	$defaults['single-post-content-width'] = 'small';

	// single post title layout
	$defaults['single-post-title-layout'] = 'buddyx-section-title-above';

	if ( ! empty( $key ) && array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}

	return '';
}
