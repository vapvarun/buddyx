<?php
/**
 * Template part for displaying the footer info
 *
 * @package buddyx
 */

namespace Brndle\Brndle;

?>

<div class="site-info">
	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'buddyx' ) ); ?>">
		<?php
		/* translators: %s: CMS name, i.e. WordPress. */
		printf( esc_html__( 'Proudly powered by %s', 'buddyx' ), 'WordPress' );
		?>
	</a>
	<span class="sep"> | </span>
	<?php
	/* translators: Theme name. */
	printf( esc_html__( 'Theme: %s by the contributors.', 'buddyx' ), '<a href="' . esc_url( 'https://github.com/wprig/wprig/' ) . '">buddyx</a>' );

	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '<span class="sep"> | </span>' );
	}
	?>
</div><!-- .site-info -->
