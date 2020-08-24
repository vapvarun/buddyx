<?php
/**
 * Template part for displaying the footer info
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;
$copyright = get_theme_mod( 'site_copyright_text' );

?>

	<div class="site-info">
		<div class="container">
			

			<?php if ( $copyright ) {
				echo ( $copyright );
			} else { ?>
				<?php esc_html_e( 'Copyright &copy; 2020. All rights reserved by, ', 'wp-rig' ); ?><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'BuddyX Theme', 'buddyx' ); ?></a><?php
			} ?>
		</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link();
	}
	?>
</div><!-- .site-info -->
