<?php
/**
 * Federated search results for Wbcom plugin content.
 *
 * The WordPress `?s=` search only ever sees `wp_posts` content (pages, posts,
 * public CPTs). On a Wbcom stack the interesting content usually lives in a
 * plugin's own tables with its own search index:
 *
 *   - BuddyNext  - members, spaces, activity posts, hashtags (+ addon types
 *                  such as Career Board jobs / Listora listings)
 *   - Jetonomy   - discussions / Q&A, forum spaces, tags and replies
 *   - Eventonomy - events (`wp_evnm_events`, not a WP CPT)
 *   - WPMediaVerse - media items and Pro documents
 *   - Learnomy   - courses (`wp_lrn_courses`, not a WP CPT)
 *
 * So a member searching a space name, or a document title, gets "No results"
 * from the theme search even though the thing exists. This module keeps the
 * native WordPress results list exactly as it is and appends one compact panel
 * per active provider: the first few matches as linked rows, then a "See all"
 * handoff to that plugin's own search page.
 *
 * Ported from Reign 8.0.7 (`reign-theme/inc/search-federation.php`).
 *
 * Contract:
 *   - Purely additive. If nothing is active, or a provider errors, or there are
 *     no matches, nothing is rendered - the search page is byte-identical to
 *     before.
 *   - Providers run as the current user, so every plugin applies its own
 *     visibility / privacy rules.
 *   - Results are cached per (query, user) for 5 minutes.
 *   - `buddyx_search_federation_excluded_providers` filter (array of ids) to
 *     keep a specific plugin's results off the page;
 *     `buddyx_search_federation_providers` to add/remove providers outright;
 *     `buddyx_search_federation` filter (bool) to switch the whole feature
 *     off.
 *
 * The renderer is wired to the `buddyx_search_after_results` action, fired
 * from `index.php` / `optional/search.php` on `is_search()` after the native
 * results loop.
 *
 * @package buddyx
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'buddyx_search_federation_is_active' ) ) {

	/**
	 * Whether federated panels should render on the current request.
	 *
	 * @since 5.1.8
	 *
	 * @return bool
	 */
	function buddyx_search_federation_is_active() {
		if ( is_admin() || ! is_search() ) {
			return false;
		}

		if ( '' === trim( (string) get_search_query() ) ) {
			return false;
		}

		/**
		 * Filter the federated-search master switch.
		 *
		 * @since 5.1.8
		 *
		 * @param bool $enabled Default true on front-end search pages.
		 */
		return (bool) apply_filters( 'buddyx_search_federation', true );
	}
}

if ( ! function_exists( 'buddyx_search_federation_should_load_assets' ) ) {

	/**
	 * Whether the federation stylesheet should load on the current request.
	 *
	 * True only on a front-end search page with a non-empty query AND at least
	 * one provider plugin active - so a site running the theme without any of
	 * the Wbcom plugins ships zero extra bytes on its search pages.
	 *
	 * @since 5.1.8
	 *
	 * @return bool
	 */
	function buddyx_search_federation_should_load_assets() {
		return buddyx_search_federation_is_active()
			&& ! empty( buddyx_search_federation_providers() );
	}
}

if ( ! function_exists( 'buddyx_search_federation_has_results' ) ) {

	/**
	 * Whether the federated providers have anything to show for the current query.
	 *
	 * Cheap to call before rendering - it reuses the same cached section list
	 * `buddyx_search_federation_render()` uses, so a template can decide
	 * whether to suppress the "Nothing Found" empty state.
	 *
	 * @since 5.1.8
	 *
	 * @return bool
	 */
	function buddyx_search_federation_has_results() {
		if ( ! buddyx_search_federation_is_active() ) {
			return false;
		}

		return ! empty( buddyx_search_federation_get_sections( get_search_query() ) );
	}
}

if ( ! function_exists( 'buddyx_search_federation_providers' ) ) {

	/**
	 * The registered federated-search providers, keyed by id.
	 *
	 * Each provider: array{ label:string, priority:int, callback:callable }.
	 * The callback receives ( string $query, int $limit ) and returns a list of
	 * sections - array{ label:string, total:int, see_all_url:string, rows:array }
	 * where each row is array{ title:string, url:string, subtitle:string, badge:string }.
	 *
	 * @since 5.1.8
	 *
	 * @return array<string, array{label:string, priority:int, callback:callable}>
	 */
	function buddyx_search_federation_providers() {
		$providers = array();

		if ( function_exists( 'buddynext_service' ) && class_exists( '\BuddyNext\Core\PageRouter' ) ) {
			$providers['buddynext'] = array(
				'label'    => esc_html__( 'Community', 'buddyx' ),
				'priority' => 20,
				'callback' => 'buddyx_search_federation_provider_buddynext',
			);
		}

		if ( defined( 'JETONOMY_VERSION' ) && function_exists( 'Jetonomy\\base_url' ) ) {
			$providers['jetonomy'] = array(
				'label'    => esc_html__( 'Discussions', 'buddyx' ),
				'priority' => 25,
				'callback' => 'buddyx_search_federation_provider_jetonomy',
			);
		}

		if ( defined( 'EVENTONOMY_VERSION' ) || has_filter( 'evnm_event_permalink' ) ) {
			$providers['eventonomy'] = array(
				'label'    => esc_html__( 'Events', 'buddyx' ),
				'priority' => 28,
				'callback' => 'buddyx_search_federation_provider_eventonomy',
			);
		}

		if ( class_exists( '\WPMediaVerse\Core\Plugin' ) ) {
			$providers['mediaverse'] = array(
				'label'    => esc_html__( 'Media & Documents', 'buddyx' ),
				'priority' => 30,
				'callback' => 'buddyx_search_federation_provider_mediaverse',
			);
		}

		if ( defined( 'LEARNOMY_VERSION' ) && function_exists( 'Learnomy\\route_url' ) ) {
			$providers['learnomy'] = array(
				'label'    => esc_html__( 'Courses', 'buddyx' ),
				'priority' => 35,
				'callback' => 'buddyx_search_federation_provider_learnomy',
			);
		}

		/**
		 * Filter the federated-search provider list.
		 *
		 * @since 5.1.8
		 *
		 * @param array $providers Providers keyed by id.
		 */
		$providers = (array) apply_filters( 'buddyx_search_federation_providers', $providers );

		/**
		 * Exclude federated-search providers by id.
		 *
		 * A convenience wrapper over `buddyx_search_federation_providers` for
		 * the common case of keeping one plugin's results off the search page:
		 *
		 *     add_filter( 'buddyx_search_federation_excluded_providers', function ( $ids ) {
		 *         $ids[] = 'eventonomy';
		 *         return $ids;
		 *     } );
		 *
		 * Built-in ids: buddynext, jetonomy, eventonomy, mediaverse, learnomy.
		 * Applied last, so an exclusion always wins.
		 *
		 * @since 5.1.8
		 *
		 * @param string[] $excluded Provider ids to drop. Empty by default.
		 */
		$excluded = array_filter( array_map( 'strval', (array) apply_filters( 'buddyx_search_federation_excluded_providers', array() ) ) );
		if ( ! empty( $excluded ) ) {
			$providers = array_diff_key( $providers, array_flip( $excluded ) );
		}

		uasort(
			$providers,
			static function ( $a, $b ) {
				return ( (int) ( $a['priority'] ?? 50 ) ) <=> ( (int) ( $b['priority'] ?? 50 ) );
			}
		);

		return $providers;
	}
}

if ( ! function_exists( 'buddyx_search_federation_get_sections' ) ) {

	/**
	 * Run every provider for a query and return the merged, cached section list.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query Search term.
	 * @param int    $limit Rows per section. Default 5.
	 * @return array<int, array{label:string, total:int, see_all_url:string, rows:array, provider:string, provider_label:string}>
	 */
	function buddyx_search_federation_get_sections( $query, $limit = 5 ) {
		$query = trim( (string) $query );
		$limit = max( 1, min( 10, (int) $limit ) );

		if ( '' === $query ) {
			return array();
		}

		$cache_key = 'buddyx_sfed_' . md5( $query . '|' . get_current_user_id() . '|' . $limit );
		$cached    = get_transient( $cache_key );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		$sections = array();

		foreach ( buddyx_search_federation_providers() as $provider_id => $provider ) {
			if ( empty( $provider['callback'] ) || ! is_callable( $provider['callback'] ) ) {
				continue;
			}

			try {
				$provider_sections = (array) call_user_func( $provider['callback'], $query, $limit );
			} catch ( \Throwable $e ) {
				// A provider must never take down the search page.
				$provider_sections = array();
			}

			foreach ( $provider_sections as $section ) {
				if ( empty( $section['rows'] ) || ! is_array( $section['rows'] ) ) {
					continue;
				}

				$sections[] = array(
					'provider'       => (string) $provider_id,
					'provider_label' => (string) $provider['label'],
					'label'          => (string) ( $section['label'] ?? $provider['label'] ),
					'total'          => (int) ( $section['total'] ?? count( $section['rows'] ) ),
					'see_all_url'    => (string) ( $section['see_all_url'] ?? '' ),
					'rows'           => array_values( $section['rows'] ),
				);
			}
		}

		set_transient( $cache_key, $sections, 5 * MINUTE_IN_SECONDS );

		return $sections;
	}
}

if ( ! function_exists( 'buddyx_search_federation_bn_type_label' ) ) {

	/**
	 * Friendly label for a BuddyNext search object type.
	 *
	 * @since 5.1.8
	 *
	 * @param string $type   Object-type slug (user, space, post, hashtag, ...).
	 * @param bool   $plural Whether to return the plural (section heading) form.
	 * @return string
	 */
	function buddyx_search_federation_bn_type_label( $type, $plural = false ) {
		$type = sanitize_key( (string) $type );

		$map = array(
			'user'     => array( esc_html__( 'Member', 'buddyx' ), esc_html__( 'Members', 'buddyx' ) ),
			'member'   => array( esc_html__( 'Member', 'buddyx' ), esc_html__( 'Members', 'buddyx' ) ),
			'space'    => array( esc_html__( 'Space', 'buddyx' ), esc_html__( 'Spaces', 'buddyx' ) ),
			'group'    => array( esc_html__( 'Group', 'buddyx' ), esc_html__( 'Groups', 'buddyx' ) ),
			'post'     => array( esc_html__( 'Post', 'buddyx' ), esc_html__( 'Posts', 'buddyx' ) ),
			'activity' => array( esc_html__( 'Activity', 'buddyx' ), esc_html__( 'Activity', 'buddyx' ) ),
			'hashtag'  => array( esc_html__( 'Hashtag', 'buddyx' ), esc_html__( 'Hashtags', 'buddyx' ) ),
			'media'    => array( esc_html__( 'Media', 'buddyx' ), esc_html__( 'Media', 'buddyx' ) ),
			'event'    => array( esc_html__( 'Event', 'buddyx' ), esc_html__( 'Events', 'buddyx' ) ),
			'job'      => array( esc_html__( 'Job', 'buddyx' ), esc_html__( 'Jobs', 'buddyx' ) ),
			'listing'  => array( esc_html__( 'Listing', 'buddyx' ), esc_html__( 'Listings', 'buddyx' ) ),
		);

		if ( isset( $map[ $type ] ) ) {
			return $plural ? $map[ $type ][1] : $map[ $type ][0];
		}

		$fallback = ucwords( str_replace( array( '_', '-' ), ' ', $type ) );

		return $plural ? $fallback . 's' : $fallback;
	}
}

if ( ! function_exists( 'buddyx_search_federation_provider_buddynext' ) ) {

	/**
	 * BuddyNext provider - members, spaces, posts, hashtags and any addon type.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query Search term.
	 * @param int    $limit Rows per section.
	 * @return array List of sections.
	 */
	function buddyx_search_federation_provider_buddynext( $query, $limit ) {
		$service = buddynext_service( 'search' );

		if ( ! is_object( $service ) || ! method_exists( $service, 'grouped_search' ) ) {
			return array();
		}

		$grouped = $service->grouped_search( $query, get_current_user_id(), $limit );
		$groups  = isset( $grouped['types'] ) && is_array( $grouped['types'] ) ? $grouped['types'] : array();

		$see_all_base = method_exists( '\BuddyNext\Core\PageRouter', 'search_url' )
			? \BuddyNext\Core\PageRouter::search_url()
			: '';
		$see_all_url  = '' !== $see_all_base ? add_query_arg( 'q', rawurlencode( $query ), $see_all_base ) : '';

		/**
		 * Object types whose per-row link from `grouped_search()` is trusted.
		 *
		 * BuddyNext resolves member / space / post / media through dedicated
		 * PageRouter helpers, but every other indexed type (events, hashtags and
		 * addon types like jobs / listings) falls through a
		 * `get_permalink( $object_id )` default that assumes the id is a
		 * `wp_posts` id - for content kept in the plugin's own tables that lands
		 * on an unrelated page. Those rows link to the BuddyNext search page
		 * (where the on-page pipeline resolves the real URL) instead.
		 *
		 * @since 5.1.8
		 *
		 * @param array $types Trusted object-type slugs.
		 */
		$direct_link_types = (array) apply_filters(
			'buddyx_search_federation_buddynext_direct_link_types',
			array( 'user', 'member', 'space', 'post', 'media' )
		);

		// Types a more authoritative provider already covers - drop them here so
		// the same result is not listed twice (e.g. Eventonomy owns `event`).
		$dupe_of = array_keys( buddyx_search_federation_providers() );
		$skip    = array();
		if ( in_array( 'eventonomy', $dupe_of, true ) ) {
			$skip[] = 'event';
		}

		$sections = array();

		foreach ( $groups as $group ) {
			$type = isset( $group['type'] ) ? (string) $group['type'] : '';

			if ( in_array( $type, $skip, true ) ) {
				continue;
			}

			$trusts = in_array( $type, $direct_link_types, true );
			$rows   = array();

			foreach ( (array) ( $group['results'] ?? array() ) as $item ) {
				$title   = trim( (string) ( $item['title'] ?? '' ) );
				$content = trim( wp_strip_all_tags( (string) ( $item['content'] ?? '' ) ) );

				// Activity posts carry no title - BuddyNext's grouped_search()
				// returns the literal placeholder "Untitled" for them. Show the
				// post text (the part the search actually matched) instead so the
				// row is meaningful. Compared as a raw sentinel from the other
				// plugin, not a string this theme translates.
				if ( ( '' === $title || 'Untitled' === $title ) && '' !== $content ) {
					$title = wp_trim_words( $content, 14, '&hellip;' );
				}

				if ( '' === $title ) {
					continue;
				}

				// Career Board jobs / companies, Listora listings etc. are real
				// public CPTs, so the native WordPress results list above already
				// shows them as proper cards - drop the duplicate here.
				$oid = (int) ( $item['object_id'] ?? 0 );
				if ( ! $trusts && $oid > 0 && buddyx_search_federation_is_native_post( $oid ) ) {
					continue;
				}

				// Trusted types use BuddyNext's own resolver. Untrusted types
				// (events, hashtags, addon content in plugin tables) are handed
				// to buddyx_search_federation_bn_row_url(), which resolves the
				// URL from the owning plugin where it can (e.g. Eventonomy for
				// events) and otherwise returns '' so the row renders as static
				// text with the section "See all" as the way through.
				$url = $trusts
					? (string) ( $item['url'] ?? '' )
					: buddyx_search_federation_bn_row_url( $type, $item );

				$rows[] = array(
					'title'    => $title,
					'url'      => $url,
					'subtitle' => (string) ( $item['subtitle'] ?? '' ),
					'badge'    => buddyx_search_federation_bn_type_label( $type ),
				);
			}

			if ( empty( $rows ) ) {
				continue;
			}

			$sections[] = array(
				'label'       => buddyx_search_federation_bn_type_label( $type, true ),
				'total'       => (int) ( $group['total'] ?? count( $rows ) ),
				'see_all_url' => $see_all_url,
				'rows'        => $rows,
			);
		}

		// Hashtags. `grouped_search()` does not index hashtag entities, but
		// BuddyNext's own search page has a Hashtags section - mirror it via the
		// HashtagService autocomplete (slug prefix match), badged "Hashtag" and
		// linking to the hashtag feed. Matches the QA-requested behaviour.
		if ( function_exists( 'buddynext_service' ) && method_exists( '\BuddyNext\Core\PageRouter', 'hashtag_feed_url' ) ) {
			$hservice = buddynext_service( 'hashtags' );

			if ( is_object( $hservice ) && method_exists( $hservice, 'autocomplete' ) ) {
				$tags     = (array) $hservice->autocomplete( $query, $limit );
				$tag_rows = array();

				foreach ( $tags as $tag ) {
					$tag  = (array) $tag;
					$slug = trim( (string) ( $tag['slug'] ?? '' ) );
					$name = trim( (string) ( $tag['name'] ?? $slug ) );

					if ( '' === $slug ) {
						continue;
					}

					$count = (int) ( $tag['post_count'] ?? 0 );

					$tag_rows[] = array(
						'title'    => '#' . $name,
						'url'      => (string) \BuddyNext\Core\PageRouter::hashtag_feed_url( $slug ),
						'subtitle' => $count > 0
							/* translators: %s: number of posts using the hashtag. */
							? sprintf( _n( '%s post', '%s posts', $count, 'buddyx' ), number_format_i18n( $count ) )
							: '',
						'badge'    => esc_html__( 'Hashtag', 'buddyx' ),
					);
				}

				if ( ! empty( $tag_rows ) ) {
					$sections[] = array(
						'label'       => esc_html__( 'Hashtags', 'buddyx' ),
						'total'       => count( $tag_rows ),
						'see_all_url' => $see_all_url,
						'rows'        => $tag_rows,
					);
				}
			}
		}

		return $sections;
	}
}

if ( ! function_exists( 'buddyx_search_federation_is_native_post' ) ) {

	/**
	 * Whether a post id is one the native WordPress `?s=` results would return.
	 *
	 * True for a published post whose type is public and not excluded from
	 * search - i.e. it already appears as a native result card, so a federated
	 * provider should not repeat it.
	 *
	 * @since 5.1.8
	 *
	 * @param int $post_id Candidate post id.
	 * @return bool
	 */
	function buddyx_search_federation_is_native_post( $post_id ) {
		$post = get_post( (int) $post_id );

		if ( ! $post instanceof WP_Post || 'publish' !== $post->post_status ) {
			return false;
		}

		$obj = get_post_type_object( $post->post_type );

		return $obj && ! empty( $obj->public ) && empty( $obj->exclude_from_search );
	}
}

if ( ! function_exists( 'buddyx_search_federation_bn_row_url' ) ) {

	/**
	 * Resolve the real front-end URL for an untrusted BuddyNext search row.
	 *
	 * BuddyNext indexes content owned by sibling plugins (Eventonomy events,
	 * Career Board jobs, Listora listings) by that plugin's own primary key, but
	 * only knows how to link `user`/`member`/`space`/`post`/`media`. Everything
	 * else falls through a `get_permalink( $object_id )` default that treats the
	 * sibling's row id as a `wp_posts` id and lands on an unrelated page. This
	 * asks the owning plugin instead.
	 *
	 * @since 5.1.8
	 *
	 * @param string $type BuddyNext object-type slug.
	 * @param array  $item Raw grouped_search() row (carries `object_id`).
	 * @return string Resolved URL, or '' when none is available (row stays static).
	 */
	function buddyx_search_federation_bn_row_url( $type, array $item ) {
		$type      = sanitize_key( (string) $type );
		$object_id = (int) ( $item['object_id'] ?? 0 );

		$url = '';

		// Eventonomy owns `event` rows (custom table); its shared filter builds
		// the pretty /{base}/{slug}/ single-event URL (and does the slug lookup).
		if ( 'event' === $type && $object_id > 0 && has_filter( 'evnm_event_permalink' ) ) {
			$url = (string) apply_filters( 'evnm_event_permalink', '', $object_id, array() );
		}

		/**
		 * Indexed types whose `object_id` is a real `wp_posts` id (Career Board
		 * jobs / companies / resumes, Listora listings, ...). For these a
		 * verified `get_permalink()` is safe. NOT `event` - that id is an
		 * Eventonomy table pk and collides with an unrelated page.
		 *
		 * @since 5.1.8
		 *
		 * @param array $types CPT-backed object-type slugs.
		 */
		$cpt_backed = (array) apply_filters(
			'buddyx_search_federation_buddynext_cpt_types',
			array( 'job', 'listing', 'company', 'resume', 'employer', 'candidate' )
		);

		if ( '' === $url && $object_id > 0 && in_array( $type, $cpt_backed, true ) ) {
			$post = get_post( $object_id );
			if ( $post instanceof WP_Post && 'publish' === $post->post_status ) {
				$obj = get_post_type_object( $post->post_type );
				if ( $obj && ! empty( $obj->public ) ) {
					$permalink = get_permalink( $post );
					if ( is_string( $permalink ) && '' !== $permalink ) {
						$url = $permalink;
					}
				}
			}
		}

		/**
		 * Filter the resolved front-end URL for a BuddyNext federated-search row.
		 *
		 * Lets a site wire up any other indexed type to its owning plugin.
		 * Return '' to keep the row static.
		 *
		 * @since 5.1.8
		 *
		 * @param string $url  Resolved URL so far.
		 * @param string $type Object-type slug.
		 * @param array  $item Raw grouped_search() row.
		 */
		$url = (string) apply_filters( 'buddyx_search_federation_buddynext_row_url', $url, $type, $item );

		return '' !== $url ? $url : '';
	}
}

if ( ! function_exists( 'buddyx_search_federation_rest' ) ) {

	/**
	 * Dispatch an internal GET REST request as the current user.
	 *
	 * @since 5.1.8
	 *
	 * @param string $route  REST route, e.g. `/mvs/v1/media`.
	 * @param array  $params Query parameters.
	 * @return array|null Response data on success, null on any error.
	 */
	function buddyx_search_federation_rest( $route, array $params ) {
		if ( ! function_exists( 'rest_do_request' ) ) {
			return null;
		}

		$request = new WP_REST_Request( 'GET', $route );
		foreach ( $params as $key => $value ) {
			$request->set_param( $key, $value );
		}

		$response = rest_do_request( $request );

		if ( ! $response instanceof WP_REST_Response || $response->is_error() ) {
			return null;
		}

		$data = $response->get_data();

		return is_array( $data ) ? $data : null;
	}
}

if ( ! function_exists( 'buddyx_search_federation_provider_jetonomy' ) ) {

	/**
	 * Jetonomy provider - discussions / Q&A, forum spaces and tags.
	 *
	 * Jetonomy stores its content in its own `jt_*` tables and serves it on
	 * virtual `/{base}/s/{space}/t/{topic}/` routes, so WordPress `?s=` never
	 * sees it. Its `/jetonomy/v1/search?type=all` endpoint returns the same
	 * grouped shape its own search page renders, and every row's URL is
	 * buildable from `space_slug` + `slug`, so these rows link straight to the
	 * item.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query Search term.
	 * @param int    $limit Rows per section.
	 * @return array List of sections.
	 */
	function buddyx_search_federation_provider_jetonomy( $query, $limit ) {
		$data = buddyx_search_federation_rest(
			'/jetonomy/v1/search',
			array(
				'q'        => $query,
				'type'     => 'all',
				'per_page' => $limit,
			)
		);

		$groups = isset( $data['data'] ) && is_array( $data['data'] ) ? $data['data'] : array();
		if ( empty( $groups ) ) {
			return array();
		}

		$base    = rtrim( (string) \Jetonomy\base_url(), '/' );
		$totals  = isset( $data['meta']['totals'] ) && is_array( $data['meta']['totals'] ) ? $data['meta']['totals'] : array();
		$see_all = '' !== $base ? $base . '/search/?q=' . rawurlencode( $query ) : '';

		$space_word = function_exists( 'Jetonomy\\space_label' )
			? (string) \Jetonomy\space_label( true )
			: esc_html__( 'Spaces', 'buddyx' );

		$sections = array();

		// Discussions / topics / questions.
		$post_rows = array();
		foreach ( (array) ( $groups['posts'] ?? array() ) as $item ) {
			$item  = (array) $item;
			$title = trim( (string) ( $item['title'] ?? '' ) );
			$sslug = (string) ( $item['space_slug'] ?? '' );
			$pslug = (string) ( $item['slug'] ?? '' );

			if ( '' === $title || '' === $sslug || '' === $pslug || '' === $base ) {
				continue;
			}

			$post_rows[] = array(
				'title'    => $title,
				'url'      => $base . '/s/' . rawurlencode( $sslug ) . '/t/' . rawurlencode( $pslug ) . '/',
				'subtitle' => wp_strip_all_tags( (string) ( $item['content_plain'] ?? ( $item['space_title'] ?? '' ) ) ),
				'badge'    => buddyx_search_federation_jetonomy_badge( (string) ( $item['type'] ?? 'topic' ) ),
			);
		}
		if ( ! empty( $post_rows ) ) {
			$sections[] = array(
				'label'       => esc_html__( 'Discussions', 'buddyx' ),
				'total'       => (int) ( $totals['posts'] ?? count( $post_rows ) ),
				'see_all_url' => $see_all,
				'rows'        => $post_rows,
			);
		}

		// Forum / discussion spaces.
		$space_rows = array();
		foreach ( (array) ( $groups['spaces'] ?? array() ) as $item ) {
			$item  = (array) $item;
			$title = trim( (string) ( $item['title'] ?? '' ) );
			$slug  = (string) ( $item['slug'] ?? '' );

			if ( '' === $title || '' === $slug || '' === $base ) {
				continue;
			}

			$space_rows[] = array(
				'title'    => $title,
				'url'      => $base . '/s/' . rawurlencode( $slug ) . '/',
				'subtitle' => wp_strip_all_tags( (string) ( $item['description'] ?? '' ) ),
				'badge'    => $space_word,
			);
		}
		if ( ! empty( $space_rows ) ) {
			$sections[] = array(
				'label'       => $space_word,
				'total'       => (int) ( $totals['spaces'] ?? count( $space_rows ) ),
				'see_all_url' => $see_all,
				'rows'        => $space_rows,
			);
		}

		// Tags.
		$tag_rows = array();
		foreach ( (array) ( $groups['tags'] ?? array() ) as $item ) {
			$item  = (array) $item;
			$title = trim( (string) ( $item['name'] ?? ( $item['slug'] ?? '' ) ) );
			$slug  = (string) ( $item['slug'] ?? '' );

			if ( '' === $title || '' === $slug || '' === $base ) {
				continue;
			}

			$tag_rows[] = array(
				'title'    => $title,
				'url'      => $base . '/tag/' . rawurlencode( $slug ) . '/',
				'subtitle' => '',
				'badge'    => esc_html__( 'Tag', 'buddyx' ),
			);
		}
		if ( ! empty( $tag_rows ) ) {
			$sections[] = array(
				'label'       => esc_html__( 'Tags', 'buddyx' ),
				'total'       => (int) ( $totals['tags'] ?? count( $tag_rows ) ),
				'see_all_url' => $see_all,
				'rows'        => $tag_rows,
			);
		}

		// Replies. A separate call - `type=all` groups posts / spaces / tags
		// only. A reply has no title, so its content snippet is the row label;
		// the deep link (`/s/{space}/t/{topic}/#reply-{id}`) is resolved by
		// Jetonomy's own notification_deep_link() from the reply id alone.
		if ( function_exists( 'Jetonomy\\notification_deep_link' ) ) {
			$reply_data = buddyx_search_federation_rest(
				'/jetonomy/v1/search',
				array(
					'q'        => $query,
					'type'     => 'reply',
					'per_page' => $limit,
				)
			);

			$reply_items = ( is_array( $reply_data ) && isset( $reply_data['data'] ) ) ? (array) $reply_data['data'] : array();
			$reply_rows  = array();

			foreach ( $reply_items as $item ) {
				$item = (array) $item;
				$rid  = (int) ( $item['id'] ?? 0 );
				$snip = wp_strip_all_tags( (string) ( $item['content_plain'] ?? ( $item['content'] ?? '' ) ) );

				if ( $rid <= 0 || '' === trim( $snip ) ) {
					continue;
				}

				$url = (string) \Jetonomy\notification_deep_link( 'reply', $rid );
				if ( '' === $url ) {
					continue;
				}

				$reply_rows[] = array(
					'title'    => wp_trim_words( $snip, 12, '&hellip;' ),
					'url'      => $url,
					'subtitle' => '',
					'badge'    => esc_html__( 'Reply', 'buddyx' ),
				);
			}

			if ( ! empty( $reply_rows ) ) {
				$sections[] = array(
					'label'       => esc_html__( 'Replies', 'buddyx' ),
					'total'       => (int) ( $reply_data['meta']['total'] ?? count( $reply_rows ) ),
					'see_all_url' => '' !== $base ? $base . '/search/?q=' . rawurlencode( $query ) . '&type=reply' : '',
					'rows'        => $reply_rows,
				);
			}
		}

		return $sections;
	}
}

if ( ! function_exists( 'buddyx_search_federation_jetonomy_badge' ) ) {

	/**
	 * Friendly badge for a Jetonomy post type (topic / question / idea / ...).
	 *
	 * @since 5.1.8
	 *
	 * @param string $type Jetonomy post `type` value.
	 * @return string
	 */
	function buddyx_search_federation_jetonomy_badge( $type ) {
		$type = sanitize_key( (string) $type );

		$map = array(
			'topic'        => esc_html__( 'Discussion', 'buddyx' ),
			'question'     => esc_html__( 'Question', 'buddyx' ),
			'idea'         => esc_html__( 'Idea', 'buddyx' ),
			'announcement' => esc_html__( 'Announcement', 'buddyx' ),
			'poll'         => esc_html__( 'Poll', 'buddyx' ),
		);

		return $map[ $type ] ?? esc_html__( 'Discussion', 'buddyx' );
	}
}

if ( ! function_exists( 'buddyx_search_federation_provider_eventonomy' ) ) {

	/**
	 * Eventonomy provider - events from `wp_evnm_events` (not a WP CPT).
	 *
	 * `/eventonomy/v1/events?search=` returns rows whose `url` is already the
	 * canonical `/event/{slug}/` permalink (built by `evnm_event_permalink`),
	 * so rows link straight to the event.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query Search term.
	 * @param int    $limit Rows.
	 * @return array List of sections.
	 */
	function buddyx_search_federation_provider_eventonomy( $query, $limit ) {
		$data = buddyx_search_federation_rest(
			'/eventonomy/v1/events',
			array(
				'search'   => $query,
				'per_page' => $limit,
			)
		);

		$items = ( is_array( $data ) && isset( $data['items'] ) ) ? (array) $data['items'] : array();
		$rows  = array();

		foreach ( $items as $item ) {
			$item  = (array) $item;
			$title = trim( (string) ( $item['title'] ?? '' ) );
			$url   = (string) ( $item['url'] ?? ( $item['permalink'] ?? '' ) );

			if ( '' === $title || '' === $url ) {
				continue;
			}

			$sub = '';
			if ( ! empty( $item['start_label'] ) ) {
				$sub = (string) $item['start_label'];
			} elseif ( ! empty( $item['excerpt'] ) ) {
				$sub = wp_strip_all_tags( (string) $item['excerpt'] );
			} elseif ( ! empty( $item['venue']['name'] ) ) {
				$sub = (string) $item['venue']['name'];
			}

			$rows[] = array(
				'title'    => $title,
				'url'      => $url,
				'subtitle' => $sub,
				'badge'    => esc_html__( 'Event', 'buddyx' ),
			);
		}

		if ( empty( $rows ) ) {
			return array();
		}

		$base_id = (int) get_option( 'evnm_page_calendar', 0 );
		$see_all = $base_id > 0 ? (string) get_permalink( $base_id ) : '';

		return array(
			array(
				'label'       => esc_html__( 'Events', 'buddyx' ),
				'total'       => (int) ( $data['total'] ?? count( $rows ) ),
				'see_all_url' => $see_all,
				'rows'        => $rows,
			),
		);
	}
}

if ( ! function_exists( 'buddyx_search_federation_provider_learnomy' ) ) {

	/**
	 * Learnomy provider - courses from `wp_lrn_courses` (not a WP CPT).
	 *
	 * `/learnomy/v1/courses?search=` returns `{ data: [...], meta: { total } }`;
	 * URLs are built with `\Learnomy\route_url( 'course', [ 'slug' => ... ] )`,
	 * which honours the site's configurable course base slug.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query Search term.
	 * @param int    $limit Rows.
	 * @return array List of sections.
	 */
	function buddyx_search_federation_provider_learnomy( $query, $limit ) {
		$data = buddyx_search_federation_rest(
			'/learnomy/v1/courses',
			array(
				'search'   => $query,
				'per_page' => $limit,
			)
		);

		$items = array();
		if ( is_array( $data ) ) {
			$items = isset( $data['data'] ) ? (array) $data['data']
				: ( isset( $data['items'] ) ? (array) $data['items'] : array() );
		}

		$rows = array();
		foreach ( $items as $item ) {
			$item  = (array) $item;
			$title = trim( (string) ( $item['title'] ?? ( $item['name'] ?? '' ) ) );
			$slug  = (string) ( $item['slug'] ?? '' );

			if ( '' === $title || '' === $slug ) {
				continue;
			}

			$url = (string) ( $item['permalink'] ?? ( $item['url'] ?? '' ) );
			if ( '' === $url ) {
				$url = (string) \Learnomy\route_url( 'course', array( 'slug' => $slug ) );
			}

			if ( '' === $url ) {
				continue;
			}

			$sub = '';
			if ( ! empty( $item['excerpt'] ) ) {
				$sub = wp_strip_all_tags( (string) $item['excerpt'] );
			} elseif ( ! empty( $item['summary'] ) ) {
				$sub = wp_strip_all_tags( (string) $item['summary'] );
			} elseif ( ! empty( $item['category'] ) && is_string( $item['category'] ) ) {
				$sub = (string) $item['category'];
			}

			$rows[] = array(
				'title'    => $title,
				'url'      => $url,
				'subtitle' => $sub,
				'badge'    => esc_html__( 'Course', 'buddyx' ),
			);
		}

		if ( empty( $rows ) ) {
			return array();
		}

		$total   = is_array( $data ) ? (int) ( $data['meta']['total'] ?? ( $data['total'] ?? count( $rows ) ) ) : count( $rows );
		$see_all = function_exists( 'Learnomy\\route_url' ) ? (string) \Learnomy\route_url( 'catalog' ) : '';

		return array(
			array(
				'label'       => esc_html__( 'Courses', 'buddyx' ),
				'total'       => $total,
				'see_all_url' => $see_all,
				'rows'        => $rows,
			),
		);
	}
}

if ( ! function_exists( 'buddyx_search_federation_provider_mediaverse' ) ) {

	/**
	 * MediaVerse provider - media items and (Pro) documents.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query Search term.
	 * @param int    $limit Rows per section.
	 * @return array List of sections.
	 */
	function buddyx_search_federation_provider_mediaverse( $query, $limit ) {
		$sections = array();

		// Pro documents (full-text index) - this is what finds "Seeded ...".
		// Documents have no public per-item permalink (they live inside the
		// Drive UI), so every row links to the Documents page pre-filtered to
		// the search term - the "top N + handoff" model.
		if ( class_exists( '\WPMediaVersePro\Documents\SearchService' ) ) {
			$docs     = buddyx_search_federation_rest(
				'/mvs-pro/v1/documents/search',
				array(
					'q'        => $query,
					'per_page' => $limit,
				)
			);
			$docs_url = buddyx_search_federation_mediaverse_url( $query, true );

			$doc_rows = buddyx_search_federation_map_media_rows(
				isset( $docs['items'] ) ? (array) $docs['items'] : array(),
				esc_html__( 'Document', 'buddyx' ),
				$docs_url
			);

			if ( ! empty( $doc_rows ) ) {
				$sections[] = array(
					'label'       => esc_html__( 'Documents', 'buddyx' ),
					'total'       => (int) ( $docs['total'] ?? count( $doc_rows ) ),
					'see_all_url' => $docs_url,
					'rows'        => $doc_rows,
				);
			}
		}

		// General media (images / video / audio). These DO have permalinks.
		$media     = buddyx_search_federation_rest(
			'/mvs/v1/media',
			array(
				's'        => $query,
				'per_page' => $limit,
			)
		);
		$media_url = buddyx_search_federation_mediaverse_url( $query );

		$media_items = array();
		if ( is_array( $media ) ) {
			// The collection endpoint may return a bare list or { items: [...] }.
			$media_items = isset( $media['items'] ) ? (array) $media['items'] : $media;
		}

		$media_rows = buddyx_search_federation_map_media_rows( $media_items, esc_html__( 'Media', 'buddyx' ), $media_url );

		if ( ! empty( $media_rows ) ) {
			$sections[] = array(
				'label'       => esc_html__( 'Media', 'buddyx' ),
				'total'       => count( $media_rows ),
				'see_all_url' => $media_url,
				'rows'        => $media_rows,
			);
		}

		return $sections;
	}
}

if ( ! function_exists( 'buddyx_search_federation_map_media_rows' ) ) {

	/**
	 * Normalise MediaVerse REST items to federation rows.
	 *
	 * @since 5.1.8
	 *
	 * @param array  $items        REST items.
	 * @param string $badge        Badge label for this set.
	 * @param string $fallback_url URL to use when an item has no public permalink.
	 * @return array Rows.
	 */
	function buddyx_search_federation_map_media_rows( array $items, $badge, $fallback_url = '' ) {
		$rows = array();

		foreach ( $items as $item ) {
			$item = (array) $item;

			$title = trim( (string) ( $item['title'] ?? ( $item['name'] ?? '' ) ) );
			$url   = (string) ( $item['link'] ?? ( $item['permalink'] ?? ( $item['url'] ?? '' ) ) );

			if ( '' === $url ) {
				$url = (string) $fallback_url;
			}

			if ( '' === $title || '' === $url ) {
				continue;
			}

			$subtitle = '';
			if ( ! empty( $item['snippet'] ) ) {
				$subtitle = wp_strip_all_tags( (string) $item['snippet'] );
			} elseif ( ! empty( $item['excerpt'] ) ) {
				$subtitle = wp_strip_all_tags( (string) $item['excerpt'] );
			} elseif ( ! empty( $item['description'] ) ) {
				$subtitle = wp_strip_all_tags( (string) $item['description'] );
			} elseif ( ! empty( $item['media_type'] ) ) {
				$subtitle = ucfirst( (string) $item['media_type'] );
			} elseif ( ! empty( $item['doc_type'] ) ) {
				$subtitle = ucfirst( (string) $item['doc_type'] );
			}

			$rows[] = array(
				'title'    => $title,
				'url'      => $url,
				'subtitle' => $subtitle,
				'badge'    => (string) $badge,
			);
		}

		return $rows;
	}
}

if ( ! function_exists( 'buddyx_search_federation_mediaverse_url' ) ) {

	/**
	 * URL for MediaVerse's own Explore page.
	 *
	 * The Documents Drive (`mvs_page_explore_documents`) takes a `doc_s` query
	 * param that pre-fills its search box. The media Explore page's search is
	 * JS-driven and a bare `?s=` is claimed by WordPress core search (404s), so
	 * for media we hand off to the page itself without a term.
	 *
	 * @since 5.1.8
	 *
	 * @param string $query     Search term (used for Documents only).
	 * @param bool   $documents Target the dedicated Documents Drive page.
	 * @return string
	 */
	function buddyx_search_federation_mediaverse_url( $query, $documents = false ) {
		if ( $documents ) {
			$page_id = (int) get_option( 'mvs_page_explore_documents', 0 );
			if ( $page_id > 0 ) {
				$base = (string) get_permalink( $page_id );
				return '' !== $base ? add_query_arg( 'doc_s', rawurlencode( $query ), $base ) : '';
			}
		}

		$page_id = (int) get_option( 'mvs_page_explore', 0 );
		if ( $page_id <= 0 ) {
			return '';
		}

		return (string) get_permalink( $page_id );
	}
}

if ( ! function_exists( 'buddyx_search_federation_render' ) ) {

	/**
	 * Echo the federated-search panels for the current query.
	 *
	 * Safe to call unconditionally - it self-suppresses off a search page, on an
	 * empty query, or when there is nothing to show. Wired to the
	 * `buddyx_search_after_results` action.
	 *
	 * @since 5.1.8
	 *
	 * @return void
	 */
	function buddyx_search_federation_render() {
		if ( ! buddyx_search_federation_is_active() ) {
			return;
		}

		$query    = get_search_query();
		$sections = buddyx_search_federation_get_sections( $query );

		if ( empty( $sections ) ) {
			return;
		}

		// Show the provider name once above its first section, but only when more
		// than one provider contributed - otherwise the single name is noise.
		$provider_count = count( array_unique( wp_list_pluck( $sections, 'provider' ) ) );
		$last_provider  = null;
		?>
		<div class="buddyx-search-federation">
			<?php foreach ( $sections as $section ) : ?>
				<?php
				$more    = max( 0, (int) $section['total'] - count( $section['rows'] ) );
				$see_all = (string) $section['see_all_url'];

				if ( $provider_count > 1 && $section['provider'] !== $last_provider ) :
					$last_provider = $section['provider'];
					?>
					<p class="buddyx-search-federation__provider"><?php echo esc_html( (string) $section['provider_label'] ); ?></p>
				<?php endif; ?>
				<section class="buddyx-search-federation__group buddyx-search-federation__group--<?php echo esc_attr( sanitize_html_class( $section['provider'] ) ); ?>">
					<header class="buddyx-search-federation__heading">
						<h2 class="buddyx-search-federation__title">
							<?php echo esc_html( $section['label'] ); ?>
							<span class="buddyx-search-federation__count"><?php echo esc_html( number_format_i18n( (int) $section['total'] ) ); ?></span>
						</h2>
						<?php if ( '' !== $see_all ) : ?>
							<a class="buddyx-search-federation__seeall" href="<?php echo esc_url( $see_all ); ?>">
								<?php esc_html_e( 'See all', 'buddyx' ); ?>
								<span aria-hidden="true">&rarr;</span>
							</a>
						<?php endif; ?>
					</header>

					<ul class="buddyx-search-federation__list" role="list">
						<?php
						foreach ( $section['rows'] as $row ) :
							$row_url  = (string) ( $row['url'] ?? '' );
							$row_tag  = '' !== $row_url ? 'a' : 'span';
							$row_attr = '' !== $row_url ? ' href="' . esc_url( $row_url ) . '"' : '';
							?>
							<li class="buddyx-search-federation__row">
								<<?php echo esc_html( $row_tag ); ?> class="buddyx-search-federation__link<?php echo '' === $row_url ? ' buddyx-search-federation__link--static' : ''; ?>"<?php echo $row_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_url above. ?>>
									<?php if ( ! empty( $row['badge'] ) ) : ?>
										<span class="buddyx-search-federation__badge"><?php echo esc_html( (string) $row['badge'] ); ?></span>
									<?php endif; ?>
									<span class="buddyx-search-federation__row-title"><?php echo esc_html( (string) $row['title'] ); ?></span>
									<?php if ( ! empty( $row['subtitle'] ) ) : ?>
										<span class="buddyx-search-federation__row-sub"><?php echo esc_html( wp_trim_words( (string) $row['subtitle'], 20, '&hellip;' ) ); ?></span>
									<?php endif; ?>
								</<?php echo esc_html( $row_tag ); ?>>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php if ( $more > 0 && '' !== $see_all ) : ?>
						<a class="buddyx-search-federation__more" href="<?php echo esc_url( $see_all ); ?>">
							<?php
							printf(
								/* translators: %s: number of additional results. */
								esc_html( _n( '%s more result', '%s more results', $more, 'buddyx' ) ),
								esc_html( number_format_i18n( $more ) )
							);
							?>
						</a>
					<?php endif; ?>
				</section>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

add_action( 'buddyx_search_after_results', 'buddyx_search_federation_render' );

if ( ! function_exists( 'buddyx_search_federation_register_style' ) ) {

	/**
	 * Declare the federation stylesheet in the theme's CSS manifest.
	 *
	 * Keeps the asset discoverable to the manifest / audits and dequeuable by
	 * handle. Actual enqueue happens in
	 * buddyx_search_federation_enqueue_style() so it is scoped to search
	 * pages regardless of the site's preloading strategy.
	 *
	 * @since 5.1.8
	 *
	 * @param array $files The `buddyx_css_files` manifest.
	 * @return array
	 */
	function buddyx_search_federation_register_style( $files ) {
		if ( ! is_array( $files ) ) {
			return $files;
		}

		$files['buddyx-search-federation'] = array(
			'file'             => 'search-federation.min.css',
			'preload_callback' => 'buddyx_search_federation_should_load_assets',
		);

		return $files;
	}
	add_filter( 'buddyx_css_files', 'buddyx_search_federation_register_style' );
}

if ( ! function_exists( 'buddyx_search_federation_enqueue_style' ) ) {

	/**
	 * Enqueue the federation stylesheet on front-end search pages.
	 *
	 * Runs after the theme's manifest registration (priority 10) so the handle
	 * is already registered; falls back to a direct registration if not.
	 *
	 * @since 5.1.8
	 *
	 * @return void
	 */
	function buddyx_search_federation_enqueue_style() {
		if ( ! buddyx_search_federation_should_load_assets() ) {
			return;
		}

		if ( wp_style_is( 'buddyx-search-federation', 'registered' ) ) {
			wp_enqueue_style( 'buddyx-search-federation' );
			return;
		}

		$rel_path = '/assets/css/search-federation.min.css';
		wp_enqueue_style(
			'buddyx-search-federation',
			get_theme_file_uri( $rel_path ),
			array(),
			\BuddyX\Buddyx\buddyx()->get_asset_version( get_theme_file_path( $rel_path ) )
		);
	}
	add_action( 'wp_enqueue_scripts', 'buddyx_search_federation_enqueue_style', 20 );
}
