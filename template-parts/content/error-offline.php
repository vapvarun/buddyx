<?php
/**
 * Template part for displaying the page content when an offline error has occurred
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

?>
<section class="error">
	<header class="page-header">
		<h1 class="page-title">
			<?php esc_html_e( 'Oops! It looks like you&#8217;re offline.', 'buddyx' ); ?>
		</h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
			wp_service_worker_error_message_placeholder();
		}
		?>
	</div><!-- .page-content -->
</section><!-- .error -->
