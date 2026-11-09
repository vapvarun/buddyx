<?php
/**
 * Course card metadata (LearnDash): category, lessons, price, rating and instructor instead of the blog byline.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

if ( function_exists( 'buddyx_ld_render_course_meta' ) ) {
	buddyx_ld_render_course_meta( get_the_ID(), true );
}
