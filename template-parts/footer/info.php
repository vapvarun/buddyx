<?php
/**
 * Template part for displaying the footer info
 *
 * @package buddyx
 */

namespace Brndle\Brndle;
$copyright = get_theme_mod( 'site_copyright_text' );

?>

	<div class="site-info">
		<div class="container">
			

			<?php if ( $copyright ) {
				echo ( $copyright );
			} else {
				 echo 'Copyright © 2019. All rights reserved by, <a href="https://brndle.com/">Brndle</a>';
			} ?>
		</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '<span class="sep"> | </span>' );
	}
	?>
</div><!-- .site-info -->
