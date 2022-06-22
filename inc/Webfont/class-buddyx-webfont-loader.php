<?php
/**
 * Download webfonts locally.
 *
 * @package WPTT/webfont-loader
 * @license https://opensource.org/licenses/MIT
 *
 * @since 4.3.9
 */

/**
 * Download webfonts locally.
 */
class Buddyx_WebFont_Loader {

	/**
	 * The font-format.
	 *
	 * Use "woff" or "woff2".
	 * This will change the user-agent user to make the request.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $font_format = 'woff2';

	/**
	 * The remote URL.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $remote_url;

	/**
	 * Base path.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $base_path;

	/**
	 * Base URL.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $base_url;

	/**
	 * Subfolder name.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $subfolder_name;

	/**
	 * The fonts folder.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $fonts_folder;

	/**
	 * The local stylesheet's path.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $local_stylesheet_path;

	/**
	 * The local stylesheet's URL.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $local_stylesheet_url;

	/**
	 * The remote CSS.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $remote_styles;

	/**
	 * The final CSS.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @var string
	 */
	protected $css;

	/**
	 * Cleanup routine frequency.
	 */
	const CLEANUP_FREQUENCY = 'monthly';

	/**
	 * Constructor.
	 *
	 * Get a new instance of the object for a new URL.
	 *
	 * @access public
	 * @since 4.3.9
	 * @param string $url The remote URL.
	 */
	public function __construct( $url = '' ) {
		$this->remote_url = $url;

		// Add a cleanup routine.
		$this->schedule_cleanup();
		add_action( 'buddyx_delete_fonts_folder', array( $this, 'buddyx_delete_fonts_folder' ) );
	}

	/**
	 * Get the local URL which contains the styles.
	 *
	 * Fallback to the remote URL if we were unable to write the file locally.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_url() {

		// Check if the local stylesheet exists.
		if ( $this->local_file_exists() ) {

			// Attempt to update the stylesheet. Return the local URL on success.
			if ( $this->write_stylesheet() ) {
				return $this->get_local_stylesheet_url();
			}
		}

		$google_font_url = file_exists( $this->get_local_stylesheet_path() ) ? $this->get_local_stylesheet_url() : $this->remote_url;

		// If the local file exists, return its URL, with a fallback to the remote URL.
		update_option( 'buddyx_font_url', wp_json_encode( $google_font_url ) );

		return $google_font_url;
	}

	/**
	 * Get the local stylesheet URL.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_local_stylesheet_url() {
		if ( ! $this->local_stylesheet_url ) {
			$this->local_stylesheet_url = str_replace(
				$this->get_base_path(),
				$this->get_base_url(),
				$this->get_local_stylesheet_path()
			);
		}
		return $this->local_stylesheet_url;
	}

	/**
	 * Get styles with fonts downloaded locally.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_styles() {

		// If we already have the local file, return its contents.
		$local_stylesheet_contents = $this->get_local_stylesheet_contents();
		if ( $local_stylesheet_contents ) {
			return $local_stylesheet_contents;
		}

		// Get the remote URL contents.
		$this->remote_styles = $this->get_remote_url_contents();

		// Get an array of locally-hosted files.
		$files = $this->get_local_files_from_css();

		// Convert paths to URLs.
		foreach ( $files as $remote => $local ) {
			$files[ $remote ] = str_replace(
				$this->get_base_path(),
				$this->get_base_url(),
				$local
			);
		}

		$this->css = str_replace(
			array_keys( $files ),
			array_values( $files ),
			$this->remote_styles
		);

		$this->write_stylesheet();

		return $this->css;
	}

	/**
	 * Get local stylesheet contents.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string|false Returns the remote URL contents.
	 */
	public function get_local_stylesheet_contents() {
		$local_path = $this->get_local_stylesheet_path();

		// Check if the local stylesheet exists.
		if ( $this->local_file_exists() ) {

			// Attempt to update the stylesheet. Return false on fail.
			if ( ! $this->write_stylesheet() ) {
				return false;
			}
		}

		ob_start();
		include $local_path; // PHPCS:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		return ob_get_clean();
	}

	/**
	 * Get remote file contents.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string Returns the remote URL contents.
	 */
	public function get_remote_url_contents() {

		/**
		 * The user-agent we want to use.
		 *
		 * The default user-agent is the only one compatible with woff (not woff2)
		 * which also supports unicode ranges.
		 */
		$user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8';

		// Switch to a user-agent supporting woff2 if we don't need to support IE.
		if ( 'woff2' === $this->font_format ) {
			$user_agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0';
		}

		// Get the response.
		$response = wp_remote_get( $this->remote_url, array( 'user-agent' => $user_agent ) );

		// Early exit if there was an error.
		if ( is_wp_error( $response ) ) {
			return '';
		}

		// Get the CSS from our response.
		$contents = wp_remote_retrieve_body( $response );

		return $contents;
	}

	/**
	 * Download files mentioned in our CSS locally.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return array Returns an array of remote URLs and their local counterparts.
	 */
	public function get_local_files_from_css() {
		$font_files = $this->get_remote_files_from_css();
		$stored     = get_site_option( 'ast_downloaded_font_files', array() );
		$change     = false; // If in the end this is true, we need to update the cache option.

		if ( ! defined( 'FS_CHMOD_DIR' ) ) {
			define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
		}

		// If the fonts folder don't exist, create it.
		if ( ! file_exists( $this->get_fonts_folder() ) ) {
			$this->get_filesystem()->mkdir( $this->get_fonts_folder(), FS_CHMOD_DIR );
		}

		foreach ( $font_files as $font_family => $files ) {

			// The folder path for this font-family.
			$folder_path = $this->get_fonts_folder() . '/' . $font_family;

			// If the folder doesn't exist, create it.
			if ( ! file_exists( $folder_path ) ) {
				$this->get_filesystem()->mkdir( $folder_path, FS_CHMOD_DIR );
			}

			foreach ( $files as $url ) {

				// Get the filename.
				$filename  = basename( wp_parse_url( $url, PHP_URL_PATH ) );
				$font_path = $folder_path . '/' . $filename;

				// Check if the file already exists.
				if ( file_exists( $font_path ) ) {

					// Skip if already cached.
					if ( isset( $stored[ $url ] ) ) {
						continue;
					}

					// Add file to the cache and change the $changed var to indicate we need to update the option.
					$stored[ $url ] = $font_path;
					$change         = true;

					// Since the file exists we don't need to proceed with downloading it.
					continue;
				}

				/**
				 * If we got this far, we need to download the file.
				 */
				// require file.php if the download_url function doesn't exist.
				if ( ! function_exists( 'download_url' ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' ); // PHPCS:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				}

				// Download file to temporary location.
				$tmp_path = download_url( $url );

				// Make sure there were no errors.
				if ( is_wp_error( $tmp_path ) ) {
					continue;
				}

				// Move temp file to final destination.
				$success = $this->get_filesystem()->move( $tmp_path, $font_path, true );
				if ( $success ) {
					$stored[ $url ] = $font_path;
					$change         = true;
				}
			}
		}

		// If there were changes, update the option.
		if ( $change ) {

			// Cleanup the option and then save it.
			foreach ( $stored as $url => $path ) {
				if ( ! file_exists( $path ) ) {
					unset( $stored[ $url ] );
				}
			}

			update_site_option( 'ast_downloaded_font_files', $stored );
		}

		return $stored;
	}

	/**
	 * Get the font files and preload them.
	 *
	 * @access public
	 */
	public function preload_local_fonts() {
		// Make sure variables are set.
		// Get the remote URL contents.
		$styles = $this->get_styles();

		// Get an array of locally-hosted files.
		$local_font = array();
		$font_files = $this->get_remote_files_from_css( $styles );

		foreach ( $font_files as $font_family => $files ) {
			if ( is_array( $files ) ) {
				$local_font[] = end( $files );
			}
		}

		// Caching this for further optimization.
		update_site_option( 'buddyx_local_font_files', $local_font );

		foreach ( $local_font as $key => $local_font ) {
			if ( $local_font ) {
				echo '<link rel="preload" href="' . esc_url( $local_font ) . '" as="font" type="font/' . esc_attr( $this->font_format ) . '" crossorigin>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Get font files from the CSS.
	 *
	 * @access public
	 * @since 4.3.9
	 * @param string $remote_styles Remote stylesheet data.
	 *
	 * @return array Returns an array of font-families and the font-files used.
	 */
	public function get_remote_files_from_css( $remote_styles = '' ) {

		if ( '' === $remote_styles ) {
			$remote_styles = $this->remote_styles;
		}

		$font_faces = explode( '@font-face', $remote_styles );

		$result = array();

		// Loop all our font-face declarations.
		foreach ( $font_faces as $font_face ) {

			// Make sure we only process styles inside this declaration.
			$style = explode( '}', $font_face );
			$style = isset( $style[0] ) ? $style[0] : '';

			// Sanity check.
			if ( false === strpos( $style, 'font-family' ) ) {
				continue;
			}

			// Get an array of our font-families.
			preg_match_all( '/font-family.*?\;/', $style, $matched_font_families );

			// Get an array of our font-files.
			preg_match_all( '/url\(.*?\)/i', $style, $matched_font_files );

			// Get the font-family name.
			$font_family = 'unknown';
			if ( isset( $matched_font_families[0] ) && isset( $matched_font_families[0][0] ) ) {
				$font_family = rtrim( ltrim( $matched_font_families[0][0], 'font-family:' ), ';' );
				$font_family = trim( str_replace( array( "'", ';' ), '', $font_family ) );
				$font_family = sanitize_key( strtolower( str_replace( ' ', '-', $font_family ) ) );
			}

			// Make sure the font-family is set in our array.
			if ( ! isset( $result[ $font_family ] ) ) {
				$result[ $font_family ] = array();
			}

			// Get files for this font-family and add them to the array.
			foreach ( $matched_font_files as $match ) {

				// Sanity check.
				if ( ! isset( $match[0] ) ) {
					continue;
				}

				// Add the file URL.
				$result[ $font_family ][] = rtrim( ltrim( $match[0], 'url(' ), ')' );
			}

			// Make sure we have unique items.
			// We're using array_flip here instead of array_unique for improved performance.
			$result[ $font_family ] = array_flip( array_flip( $result[ $font_family ] ) );
		}

		return $result;
	}

	/**
	 * Write the CSS to the filesystem.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @return string|false Returns the absolute path of the file on success, or false on fail.
	 */
	protected function write_stylesheet() {
		$file_path  = $this->get_local_stylesheet_path();
		$filesystem = $this->get_filesystem();

		if ( ! defined( 'FS_CHMOD_DIR' ) ) {
			define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
		}

		// If the folder doesn't exist, create it.
		if ( ! file_exists( $this->get_fonts_folder() ) ) {
			$this->get_filesystem()->mkdir( $this->get_fonts_folder(), FS_CHMOD_DIR );
		}

		// If the file doesn't exist, create it. Return false if it can not be created.
		if ( ! $filesystem->exists( $file_path ) && ! $filesystem->touch( $file_path ) ) {
			return false;
		}

		// If we got this far, we need to write the file.
		// Get the CSS.
		if ( ! $this->css ) {
			$this->get_styles();
		}

		// Put the contents in the file. Return false if that fails.
		if ( ! $filesystem->put_contents( $file_path, $this->css ) ) {
			return false;
		}

		return $file_path;
	}

	/**
	 * Get the stylesheet path.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_local_stylesheet_path() {
		if ( ! $this->local_stylesheet_path ) {
			$this->local_stylesheet_path = $this->get_fonts_folder() . '/' . $this->get_local_stylesheet_filename() . '.css';
		}
		return $this->local_stylesheet_path;
	}

	/**
	 * Get the local stylesheet filename.
	 *
	 * This is a hash, generated from the site-URL, the wp-content path and the URL.
	 * This way we can avoid issues with sites changing their URL, or the wp-content path etc.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_local_stylesheet_filename() {
		return apply_filters( 'buddyx_local_font_file_name', 'buddyx-local-fonts' );
	}

	/**
	 * Set the font-format to be used.
	 *
	 * @access public
	 * @since 4.3.9
	 * @param string $format The format to be used. Use "woff" or "woff2".
	 * @return void
	 */
	public function set_font_format( $format = 'woff2' ) {
		$this->font_format = apply_filters( 'buddyx_local_google_fonts_format', $format );
	}

	/**
	 * Check if the local stylesheet exists.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return bool
	 */
	public function local_file_exists() {
		return ( ! file_exists( $this->get_local_stylesheet_path() ) );
	}

	/**
	 * Get the base path.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_base_path() {
		if ( ! $this->base_path ) {
			$this->base_path = apply_filters( 'buddyx_local_fonts_base_path', $this->get_filesystem()->wp_content_dir() . '/uploads/' );
		}
		return $this->base_path;
	}

	/**
	 * Get the base URL.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_base_url() {
		if ( ! $this->base_url ) {
			$this->base_url = apply_filters( 'buddyx_local_fonts_base_url', content_url() .'/uploads' );
		}
		return $this->base_url;
	}

	/**
	 * Get the subfolder name.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return string
	 */
	public function get_subfolder_name() {
		if ( ! $this->subfolder_name ) {
			$this->subfolder_name = apply_filters( 'buddyx_local_fonts_directory_name', 'buddyx-local-fonts' );
		}
		return $this->subfolder_name;
	}

	/**
	 * Get the folder for fonts.
	 *
	 * @access public
	 * @return string
	 */
	public function get_fonts_folder() {
		if ( ! $this->fonts_folder ) {
			$this->fonts_folder = $this->get_base_path();
			if ( $this->get_subfolder_name() ) {
				$this->fonts_folder .= '/' . $this->get_subfolder_name();
			}
		}
		return $this->fonts_folder;
	}

	/**
	 * Schedule a cleanup.
	 *
	 * Deletes the fonts files on a regular basis.
	 * This way font files will get updated regularly,
	 * and we avoid edge cases where unused files remain in the server.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return void
	 */
	public function schedule_cleanup() {
		if ( ! is_multisite() || ( is_multisite() && is_main_site() ) ) {
			if ( ! wp_next_scheduled( 'buddyx_delete_fonts_folder' ) && ! wp_installing() ) {
				wp_schedule_event( time(), self::CLEANUP_FREQUENCY, 'buddyx_delete_fonts_folder' );  // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.cron_functionality_wp_schedule_event
			}
		}
	}

	/**
	 * Delete the fonts folder.
	 *
	 * This runs as part of a cleanup routine.
	 *
	 * @access public
	 * @since 4.3.9
	 * @return bool
	 */
	public function buddyx_delete_fonts_folder() {
		// Delete previously created supportive options.
		delete_option( 'buddyx_font_url' );
		delete_site_option( 'buddyx_local_font_files' );
		return $this->get_filesystem()->delete( $this->get_fonts_folder(), true );
	}

	/**
	 * Get the filesystem.
	 *
	 * @access protected
	 * @since 4.3.9
	 * @return \WP_Filesystem_Base
	 */
	protected function get_filesystem() {
		global $wp_filesystem;

		// If the filesystem has not been instantiated yet, do it here.
		if ( ! $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );  // PHPCS:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
			WP_Filesystem();
		}
		return $wp_filesystem;
	}
}

/**
 * Create instance of Buddyx_WebFont_Loader class.
 *
 * @param string $font_url Google font URL to set data.
 * @return object
 * @since 4.3.9
 */
function buddyx_webfont_loader_instance( $font_url = '' ) {
	return new Buddyx_WebFont_Loader( $font_url );
}




/**
 * Get the file preloads.
 *
 * @param string $url    The URL of the remote webfont.
 * @param string $format The font-format. If you need to support IE, change this to "woff".
 */
function buddyx_load_preload_local_fonts( $url, $format = 'woff2' ) {

	// Check if cached font files data preset present or not. Basically avoiding 'Buddyx_WebFont_Loader' class rendering.
	$buddyx_local_font_files = get_site_option( 'buddyx_local_font_files', false );

	if ( is_array( $buddyx_local_font_files ) && ! empty( $buddyx_local_font_files ) ) {
		$font_format = apply_filters( 'buddyx_local_google_fonts_format', $format );
		foreach ( $buddyx_local_font_files as $key => $local_font ) {
			if ( $local_font ) {
				echo '<link rel="preload" href="' . esc_url( $local_font ) . '" as="font" type="font/' . esc_attr( $font_format ) . '" crossorigin>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		return;
	}

	// Now preload font data after processing it, as we didn't get stored data.
	$font = buddyx_webfont_loader_instance( $url );
	$font->set_font_format( $format );
	$font->preload_local_fonts();
}


/**
 * Get a stylesheet URL for a webfont.
 *
 * @since 4.3.9
 *
 * @param string $url    The URL of the remote webfont.
 * @param string $format The font-format. If you need to support IE, change this to "woff".
 *
 * @return string Returns the CSS.
 */
function buddyx_get_webfont_url( $url, $format = 'woff2' ) {

	// Check if already Google font URL present or not. Basically avoiding 'Buddyx_WebFont_Loader' class rendering.
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$buddyx_font_url = get_option( 'buddyx_font_url', false );
	if ( $buddyx_font_url ) {
		return json_decode( $buddyx_font_url );
	}

	// Now create font URL if its not present.
	$font = buddyx_webfont_loader_instance( $url );
	$font->set_font_format( $format );
	return $font->get_url();
}

/**
 * Reset font folder
 *
 * @access public
 * @return void
 *
 * @since 4.3.9
 */
function buddyx_regenerate_fonts_folder() {
	
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( 'invalid_permissions' );
	}

	if ( get_option( 'buddyx_font_url' ) ) {
		$local_font_loader = buddyx_webfont_loader_instance( '' );
		$flushed           = $local_font_loader->buddyx_delete_fonts_folder();

		if ( ! $flushed ) {
			wp_send_json_error( 'failed_to_flush' );
		}
		wp_send_json_success();
	}

	wp_send_json_error( 'no_font_loader' );
}
add_action( 'wp_ajax_buddyx_regenerate_fonts_folder','buddyx_regenerate_fonts_folder' );



/**
 *  Delete the cached partial configs.
 */
function buddyx_delete_cached_partials() {	

	// Delete previously stored local fonts data, if exists.
	if ( get_option( 'buddyx_font_url' ) ) {
		$local_webfont_loader = buddyx_webfont_loader_instance( '' );
		$local_webfont_loader->buddyx_delete_fonts_folder();
	}
}
add_action( 'customize_save_after', 'buddyx_delete_cached_partials'  );