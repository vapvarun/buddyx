<?php
/**
 * Login
 *
 * @package buddyx
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

/**
 * Check login page
 */
function buddyx_is_login_page() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

/**
 * Change login logo url
 */
function buddyx_login_logo_url() {
	$custom_login_logo_url = get_theme_mod( 'custom_login_logo_url' );
	if ( isset( $custom_login_logo_url ) && ! empty( $custom_login_logo_url ) ) {
		return $custom_login_logo_url;
	} else {
		return home_url();
	}
}
add_filter( 'login_headerurl', 'buddyx_login_logo_url' );

/**
 * Change login title
 */
function buddyx_login_title() {
	$custom_login_logo_title = get_theme_mod( 'custom_login_logo_title' );
	if ( isset( $custom_login_logo_title ) && ! empty( $custom_login_logo_title ) ) {
		return $custom_login_logo_title;
	} else {
		return get_option( 'blogname' );
	}
}
add_filter( 'login_headertext', 'buddyx_login_title' );

/**
 * Change login page title
 *
 * @param string $title The original page title.
 */
function buddyx_login_page_title( $title ) {
	$custom_login_page_title = get_theme_mod( 'custom_login_page_title' );
	if ( isset( $custom_login_page_title ) && ! empty( $custom_login_page_title ) ) {
		return $custom_login_page_title;
	} else {
		return $title;
	}
}
add_filter( 'login_title', 'buddyx_login_page_title' );

/**
 * Login load init
 */
function buddyx_theme_login_load() {
	$enable_custom_login = get_theme_mod( 'enable_custom_login' );

	if ( $enable_custom_login ) {
		add_action( 'login_head', 'login_custom_head', 150 );
	}
}
add_action( 'init', 'buddyx_theme_login_load' );

/**
 * Login page - custom styling
 */
if ( ! function_exists( 'login_custom_head' ) ) {

	/**
	 * Login custom head
	 */
	function login_custom_head() {
		$enable_custom_login_logo       = get_theme_mod( 'enable_custom_login_logo' );
		$custom_login_logo_image        = get_theme_mod( 'custom_login_logo_image' );
		$custom_login_logo_image_width  = get_theme_mod( 'custom_login_logo_image_width' );
		$custom_login_logo_image_height = get_theme_mod( 'custom_login_logo_image_height' );
		$custom_login_logo_space        = get_theme_mod( 'custom_login_logo_space' );

		echo '<style>';

		// Logo.
		if ( ! empty( $enable_custom_login_logo ) && true === $enable_custom_login_logo ) {
			?>
			.login h1 {
				display: none !important;
			}
			<?php
		}

		if ( isset( $custom_login_logo_image ) && ! empty( $custom_login_logo_image ) ) {
			?>
			.login h1 a {
				background-image: url( <?php echo esc_url( $custom_login_logo_image ); ?> );
				background-size: contain;
				<?php
				if ( isset( $custom_login_logo_image_width ) && ! empty( $custom_login_logo_image_width ) ) {
					echo 'width:' . esc_attr( $custom_login_logo_image_width ) . ';';
				}
				if ( isset( $custom_login_logo_image_height ) && ! empty( $custom_login_logo_image_height ) ) {
					echo 'height:' . esc_attr( $custom_login_logo_image_height ) . ';';
				}
				if ( isset( $custom_login_logo_space ) && ! empty( $custom_login_logo_space ) ) {
					echo 'margin-bottom:' . esc_attr( $custom_login_logo_space ) . ';';
				}
				?>
			}
			<?php
		}

		echo '</style>';
	}
}
