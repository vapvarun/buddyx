<?php
/**
 * BuddyPress - Groups Home
 *
 * @since 3.0.0
 * @version 3.0.0
 */

$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance' );

if ( bp_is_group_subgroups() ) {
	ob_start();
	bp_nouveau_group_template_part();
	$template_content = ob_get_contents();
	ob_end_clean();
}

if ( bp_has_groups() ) :
	while ( bp_groups() ) :
		bp_the_group();
		?>

		<?php bp_nouveau_group_hook( 'before', 'home_content' ); ?>

		<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups" class="groups-header single-headers">

			<?php bp_nouveau_group_header_template_part(); ?>

		</div><!-- #item-header -->

		<div class="site-wrapper group-home">
			<?php
			if ( ( ! isset( $bp_nouveau_appearance['group_nav_display'] ) || ! $bp_nouveau_appearance['group_nav_display'] ) && is_active_sidebar( 'single_group_activity' ) && bp_is_group_activity() ) {
				?>
				<aside id="secondary" class="primary-sidebar widget-area">
					<div class="sticky-sidebar">
						<?php dynamic_sidebar( 'single_group_activity' ); ?>
					</div>
				</aside>
			<?php } ?>
			<div class="bp-wrap">

				<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

					<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>

				<?php endif; ?>

				<div id="item-body" class="item-body">
					<?php
					if ( bp_is_group_subgroups() ) {
						echo $template_content; // phpcs:ignore
					} else {
						bp_nouveau_group_template_part();
					}
					?>
				</div><!-- #item-body -->

			</div><!-- // .bp-wrap -->
			<?php if ( is_active_sidebar( 'single_group' ) && bp_is_group() ) : ?>
				<aside id="secondary" class="primary-sidebar widget-area">
					<div class="sticky-sidebar">
						<?php dynamic_sidebar( 'single_group' ); ?>
					</div>
				</aside>
			<?php endif; ?>
		</div><!-- .site-wrapper -->

		<?php bp_nouveau_group_hook( 'after', 'home_content' ); ?>

	<?php endwhile; ?>

	<?php
endif;
