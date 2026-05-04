<?php
/**
 * Block style variations for the BuddyX premium pattern library.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Base_Support;

if ( ! function_exists( __NAMESPACE__ . '\\register_block_styles' ) ) {
	function register_block_styles() {
		register_block_style( 'core/button', array(
			'name'  => 'outline-accent',
			'label' => __( 'Outline Accent', 'buddyx' ),
		) );
		register_block_style( 'core/button', array(
			'name'  => 'link-arrow',
			'label' => __( 'Link with Arrow', 'buddyx' ),
		) );
		register_block_style( 'core/separator', array(
			'name'  => 'gradient',
			'label' => __( 'Gradient', 'buddyx' ),
		) );
		register_block_style( 'core/separator', array(
			'name'  => 'dotted',
			'label' => __( 'Dotted', 'buddyx' ),
		) );
		register_block_style( 'core/group', array(
			'name'  => 'card',
			'label' => __( 'Card', 'buddyx' ),
		) );
		register_block_style( 'core/group', array(
			'name'  => 'bordered',
			'label' => __( 'Bordered', 'buddyx' ),
		) );
		register_block_style( 'core/quote', array(
			'name'  => 'editorial',
			'label' => __( 'Editorial', 'buddyx' ),
		) );
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_block_styles' );
