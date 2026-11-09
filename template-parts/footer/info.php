<?php
/**
 * Template part for displaying the footer info
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<?php
/**
 * Fires before the copyright row.
 */
do_action( 'buddyx_copyright_before' );
?>

<div class="site-info">
	<div class="container">
		<?php echo buddyx_footer_custom_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- buddyx_footer_custom_text() returns wp_kses_post() markup. ?>
	</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) && buddyx_is_truthy( get_theme_mod( 'site_copyright_privacy_link', 'on' ) ) ) {
		the_privacy_policy_link();
	}
	?>
</div><!-- .site-info -->

<?php
/**
 * Fires after the copyright row.
 */
do_action( 'buddyx_copyright_after' );
?>
