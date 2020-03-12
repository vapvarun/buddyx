<?php

function buddyx_defaults( $key = '' ) {
	$defaults = array();

	# site layout
    $defaults[ 'site-layout' ] = 'wide';
    
    # site loader
    $defaults[ 'site-loader' ] = '2';

    # site search
    $defaults[ 'site-search' ] = '1';

    # site cart
    $defaults[ 'site-cart' ] = '1';

    # sticky sidebar
    $defaults[ 'sticky-sidebar' ] = '1';


	if ( !empty( $key ) && array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}

	return '';
}
