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
		register_block_style( 'core/list', array(
			'name'         => 'stacked-links',
			'label'        => __( 'Stacked Links (no bullets)', 'buddyx' ),
			'inline_style' => '.wp-block-list.is-style-stacked-links{list-style:none;padding-left:0;margin-left:0}.wp-block-list.is-style-stacked-links li{padding-left:0;margin-left:0}.wp-block-list.is-style-stacked-links a{text-decoration:none;opacity:0.85}.wp-block-list.is-style-stacked-links a:hover{opacity:1;text-decoration:underline}',
		) );
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_block_styles' );
