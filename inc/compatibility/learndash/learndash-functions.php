<?php
/**
 * LearnDash support for BuddyX: the course meta line (category, lessons, price, rating),
 * the Start / Continue button, and the hooks to add more. Loaded only when LearnDash is active.
 *
 * @package buddyx
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'buddyx_ld_get_course_category_term' ) ) {
	/**
	 * First course category for a course, or null when LearnDash has the taxonomy off or the course has none.
	 *
	 * @param int $course_id Course ID.
	 * @return WP_Term|null
	 */
	function buddyx_ld_get_course_category_term( $course_id ) {
		$term = null;
		if ( taxonomy_exists( 'ld_course_category' ) ) {
			$terms = get_the_terms( $course_id, 'ld_course_category' );
			if ( is_array( $terms ) && $terms ) {
				$term = reset( $terms );
			}
		}
		/**
		 * Filters the category shown for a course. Return a different term, or false to hide it.
		 *
		 * @param WP_Term|null $term      Term.
		 * @param int          $course_id Course ID.
		 */
		$term = apply_filters( 'buddyx_ld_course_category_term', $term, $course_id );
		return $term instanceof WP_Term ? $term : null;
	}
}

if ( ! function_exists( 'buddyx_ld_course_price_label' ) ) {
	/**
	 * Price text for a course the visitor does not own yet: "Free", "£39" or "£12 / month". Empty for open courses.
	 *
	 * @param int $course_id Course ID.
	 * @return string Plain text.
	 */
	function buddyx_ld_course_price_label( $course_id ) {
		$pricing = learndash_get_course_price( $course_id );
		$type    = isset( $pricing['type'] ) ? $pricing['type'] : '';
		if ( 'free' === $type ) {
			return __( 'Free', 'buddyx' );
		}
		if ( empty( $pricing['price'] ) || ! in_array( $type, array( 'paynow', 'subscribe', 'closed' ), true ) ) {
			return '';
		}
		$label = html_entity_decode( wp_strip_all_tags( learndash_get_currency_symbol() ), ENT_QUOTES ) . $pricing['price'];
		if ( 'subscribe' === $type ) {
			$every = (int) learndash_get_setting( $course_id, 'course_price_billing_p3' );
			$unit  = (string) learndash_get_setting( $course_id, 'course_price_billing_t3' );
			$units = array(
				'D' => array(
					__( 'day', 'buddyx' ),
					/* translators: %d: number of days. */
					_n_noop( '%d days', '%d days', 'buddyx' ),
				),
				'W' => array(
					__( 'week', 'buddyx' ),
					/* translators: %d: number of weeks. */
					_n_noop( '%d weeks', '%d weeks', 'buddyx' ),
				),
				'M' => array(
					__( 'month', 'buddyx' ),
					/* translators: %d: number of months. */
					_n_noop( '%d months', '%d months', 'buddyx' ),
				),
				'Y' => array(
					__( 'year', 'buddyx' ),
					/* translators: %d: number of years. */
					_n_noop( '%d years', '%d years', 'buddyx' ),
				),
			);
			if ( isset( $units[ $unit ] ) ) {
				$label .= $every <= 1
					? ' / ' . $units[ $unit ][0]
					: ' / ' . sprintf( translate_nooped_plural( $units[ $unit ][1], $every, 'buddyx' ), $every );
			}
		}
		return $label;
	}
}

if ( ! function_exists( 'buddyx_ld_course_rating_html' ) ) {
	/**
	 * Star rating from LearnDash's own course reviews. Empty unless reviews are on for the course and it has a score.
	 *
	 * @param int $course_id Course ID.
	 * @return string Escaped HTML.
	 */
	function buddyx_ld_course_rating_html( $course_id ) {
		if ( ! function_exists( 'learndash_course_reviews_is_review_enabled' ) || ! learndash_course_reviews_is_review_enabled( $course_id ) ) {
			return '';
		}
		$average = learndash_course_reviews_get_average_review_score( $course_id );
		if ( false === $average || null === $average || '' === $average ) {
			return '';
		}
		$count = (int) get_comments(
			array(
				'post_id' => $course_id,
				'type'    => 'ld_review',
				'status'  => 'approve',
				'count'   => true,
			)
		);
		$stars = (int) round( (float) $average );
		$label = sprintf(
			/* translators: 1: average rating, 2: number of reviews. */
			_n( '%1$s out of 5, %2$d review', '%1$s out of 5, %2$d reviews', max( 1, $count ), 'buddyx' ),
			number_format_i18n( (float) $average, 1 ),
			$count
		);
		return '<span class="course-rating" role="img" aria-label="' . esc_attr( $label ) . '"><span aria-hidden="true">' . esc_html( str_repeat( '★', $stars ) . str_repeat( '☆', 5 - $stars ) ) . '</span> <small aria-hidden="true">' . esc_html( number_format_i18n( (float) $average, 1 ) ) . '</small></span>';
	}
}

if ( ! function_exists( 'buddyx_ld_render_course_meta' ) ) {
	/**
	 * Meta line for a course card or the single course: rating, category, lesson count, price, instructor. Each item only when it has data.
	 *
	 * @param int  $course_id Course ID.
	 * @param bool $card      True on the archive card, false on the single course.
	 * @return void
	 */
	function buddyx_ld_render_course_meta( $course_id, $card = true ) {
		$items = array();

		$rating = buddyx_ld_course_rating_html( $course_id );
		if ( $rating ) {
			$items['rating'] = $rating;
		}

		$term = buddyx_ld_get_course_category_term( $course_id );
		if ( $term ) {
			$items['category'] = '<a class="course-category" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
		}

		// Counts come from LearnDash's cached course steps, so a card does not run a query per lesson.
		$lessons = count( learndash_course_get_steps_by_type( $course_id, 'sfwd-lessons' ) );
		if ( $lessons > 0 ) {
			/* translators: %d: number of lessons. */
			$items['lessons'] = '<span class="course-lessons">' . esc_html( sprintf( _n( '%d lesson', '%d lessons', $lessons, 'buddyx' ), $lessons ) ) . '</span>';
		}

		// A member who already has access does not need the price.
		if ( ! sfwd_lms_has_access( $course_id, get_current_user_id() ) ) {
			$price = buddyx_ld_course_price_label( $course_id );
			if ( $price ) {
				$items['price'] = '<span class="course-price">' . esc_html( $price ) . '</span>';
			}
		}

		if ( $card ) {
			$author = get_userdata( (int) get_post_field( 'post_author', $course_id ) );
			if ( $author ) {
				/* translators: %s: course instructor name. */
				$items['instructor'] = '<span class="course-instructor">' . esc_html( sprintf( __( 'By %s', 'buddyx' ), $author->display_name ) ) . '</span>';
			}
		}

		/**
		 * Filters the meta items on a course card or single course. Add, reorder or remove items.
		 *
		 * @param array $items     Item key => escaped HTML.
		 * @param int   $course_id Course ID.
		 * @param bool  $card      True on the archive card, false on the single course.
		 */
		$items = apply_filters( 'buddyx_ld_course_meta', $items, $course_id, $card );
		if ( $items ) {
			echo '<div class="entry-meta course-meta"><div class="entry-meta__content">' . wp_kses_post( implode( '', $items ) ) . '</div></div>';
		}

		/**
		 * Fires after the course meta line.
		 *
		 * @param int  $course_id Course ID.
		 * @param bool $card      True on the archive card, false on the single course.
		 */
		do_action( 'buddyx_ld_after_course_meta', $course_id, $card );

		// Members see where they are on the card: a bar and a percent for an enrolled course.
		$user_id = get_current_user_id();
		if ( $card && $user_id && sfwd_lms_has_access( $course_id, $user_id ) ) {
			$progress = learndash_course_progress(
				array(
					'user_id'   => $user_id,
					'course_id' => $course_id,
					'array'     => true,
				)
			);
			$percent  = empty( $progress['percentage'] ) ? 0 : (int) $progress['percentage'];
			if ( 100 === $percent ) {
				$state = __( 'Completed', 'buddyx' );
			} elseif ( $percent > 0 ) {
				/* translators: %d: course progress percent. */
				$state = sprintf( __( 'In progress, %d%%', 'buddyx' ), $percent );
			} else {
				$state = __( 'Enrolled', 'buddyx' );
			}
			echo '<div class="course-progress"><progress class="course-progress__bar" max="100" value="' . (int) $percent . '" aria-hidden="true"></progress><span class="course-progress__label">' . esc_html( $state ) . '</span></div>';
		}
	}
}

if ( ! function_exists( 'buddyx_ld_render_category_filter' ) ) {
	/**
	 * Category links above the course catalogue. Only the categories that have courses, and only when there are two or more.
	 *
	 * @param WP_Query|string $query The query starting its loop (loop_start), or empty (buddyx_before_archive_loop).
	 * @return void
	 */
	function buddyx_ld_render_category_filter( $query = '' ) {
		static $done = false;
		$query       = $query instanceof WP_Query ? $query : $GLOBALS['wp_query'];
		if ( $done || ! $query->is_main_query() || is_admin() || ! ( is_post_type_archive( 'sfwd-courses' ) || is_tax( 'ld_course_category' ) ) ) {
			return;
		}
		$terms = taxonomy_exists( 'ld_course_category' ) ? get_terms(
			array(
				'taxonomy'   => 'ld_course_category',
				'hide_empty' => true,
				'number'     => 30, // A catalogue with more categories than this needs search, not a link row.
			)
		) : array();
		if ( is_wp_error( $terms ) || count( $terms ) < 2 ) {
			return;
		}
		$done    = true;
		$current = is_tax( 'ld_course_category' ) ? get_queried_object_id() : 0;
		echo '<nav class="course-filter" aria-label="' . esc_attr__( 'Course categories', 'buddyx' ) . '"><ul>';
		echo '<li><a href="' . esc_url( get_post_type_archive_link( 'sfwd-courses' ) ) . '"' . ( $current ? '' : ' aria-current="page"' ) . '>' . esc_html__( 'All', 'buddyx' ) . '</a></li>';
		foreach ( $terms as $term ) {
			echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '"' . ( $current === (int) $term->term_id ? ' aria-current="page"' : '' ) . '>' . esc_html( $term->name ) . '</a></li>';
		}
		echo '</ul></nav>';
	}
}
// Above the posts wrapper, so it never takes a grid cell or a masonry slot.
add_action( 'buddyx_before_archive_loop', 'buddyx_ld_render_category_filter' );
// Fallback for child-theme archive templates that do not fire buddyx_before_archive_loop.
add_action( 'loop_start', 'buddyx_ld_render_category_filter' );

if ( ! function_exists( 'buddyx_ld_render_single_course_start' ) ) {
	/**
	 * Single course: meta line, and a Start / Continue button for members who have access.
	 *
	 * @return void
	 */
	function buddyx_ld_render_single_course_start() {
		if ( ! is_singular( 'sfwd-courses' ) || ! in_the_loop() ) {
			return;
		}
		$course_id = get_the_ID();
		buddyx_ld_render_course_meta( $course_id, false );

		$user_id = get_current_user_id();
		if ( ! $user_id || ! sfwd_lms_has_access( $course_id, $user_id ) ) {
			return;
		}
		$progress = learndash_course_progress(
			array(
				'user_id'   => $user_id,
				'course_id' => $course_id,
				'array'     => true,
			)
		);
		$percent  = empty( $progress['percentage'] ) ? 0 : (int) $progress['percentage'];
		if ( 100 === $percent ) {
			return;
		}
		/* translators: %d: course progress percent. */
		$label = 0 === $percent ? __( 'Start course', 'buddyx' ) : sprintf( __( 'Continue, %d%%', 'buddyx' ), $percent );
		echo '<div class="course-resume">' . do_shortcode( '[ld_course_resume course_id="' . (int) $course_id . '" user_id="' . (int) $user_id . '" label="' . esc_attr( $label ) . '"]' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output is LearnDash's own escaped markup.
	}
}
add_action( 'buddyx_entry_content_before', 'buddyx_ld_render_single_course_start' );
