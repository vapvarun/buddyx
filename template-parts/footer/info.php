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
			} else {
				 echo 'Copyright &copy; 2020. All rights reserved by, <a href="#">Brndle</a>';
			} ?>
		</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link();
	}
	?>
</div><!-- .site-info -->
