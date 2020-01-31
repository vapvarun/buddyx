<?php
/**
 * Template part for displaying a pagination
 *
 * @package buddyx
 */

namespace Brndle\Brndle;

the_posts_pagination(
	[
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'buddyx' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'buddyx' ),
		'screen_reader_text' => __( 'Page navigation', 'buddyx' ),
	]
);
