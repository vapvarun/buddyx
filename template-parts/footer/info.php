<?php
/**
 * Template part for displaying the footer info
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>

<?php do_action( 'buddyx_copyright_before' ); ?>

<div class="site-info">
	<div class="container">	
		<?php echo buddyx_footer_custom_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link();
	}
	?>
</div><!-- .site-info -->

<?php do_action( 'buddyx_copyright_after' ); ?>
