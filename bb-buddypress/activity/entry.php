<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @since 3.0.0
 * @version 3.0.0
 */

bp_nouveau_activity_hook( 'before', 'entry' );

$link_preview_string = '';
$link_url            = '';

$link_preview_data = bp_activity_get_meta( bp_get_activity_id(), '_link_preview_data', true );
if ( ! empty( $link_preview_data ) && count( $link_preview_data ) ) {
	$link_preview_string = wp_json_encode( $link_preview_data );
	$link_url            = ! empty( $link_preview_data['url'] ) ? $link_preview_data['url'] : '';
}

$link_embed = bp_activity_get_meta( bp_get_activity_id(), '_link_embed', true );
if ( ! empty( $link_embed ) ) {
	$link_url = $link_embed;
}

?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>" data-bp-activity-id="<?php bp_activity_id(); ?>" data-bp-timestamp="<?php bp_nouveau_activity_timestamp(); ?>" data-bp-activity="<?php bp_nouveau_edit_activity_data(); ?>" data-link-preview='<?php echo esc_attr( $link_preview_string ); ?>' data-link-url='<?php echo esc_attr( $link_url ); ?>'>

	<?php bb_nouveau_activity_entry_bubble_buttons(); ?>

	<div class="bb-pin-action">
		<span class="bb-pin-action_button" data-balloon-pos="up" data-balloon="<?php esc_attr_e( 'Pinned Post', 'buddyx' ); ?>">
			<i class="bb-icon-f bb-icon-thumbtack"></i>
		</span>
	</div>

	<div class="activity-card-head">
		<h6 class="card-head-content-type">
			<?php echo buddyx_bp_get_activity_css_first_class(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</h6>
	</div>

	<div class="activity-item-head">

		<div class="activity-avatar item-avatar">

		<a href="<?php bp_activity_user_link(); ?>">

			<?php bp_activity_avatar( array( 'type' => 'full' ) ); ?>

		</a>

		</div>

		<div class="activity-header">

			<?php bp_activity_action(); ?>

			<p class="activity-date">
				<a href="<?php echo esc_url( bp_activity_get_permalink( bp_get_activity_id() ) ); ?>"><?php echo bp_core_time_since( bp_get_activity_date_recorded() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php
				if ( function_exists( 'bp_nouveau_activity_is_edited' ) ) {
					bp_nouveau_activity_is_edited();
				}
				?>
			</p>

			<?php bp_nouveau_activity_privacy(); ?>

		</div>

	</div>

	<div class="activity-content">

		<?php bp_nouveau_activity_hook( 'before', 'activity_content' ); ?>

		<?php if ( bp_nouveau_activity_has_content() ) : ?>

			<div class="activity-inner"><?php bp_nouveau_activity_content(); ?></div>

		<?php endif; ?>

		<?php bp_nouveau_activity_hook( 'after', 'activity_content' ); ?>

		<?php bp_nouveau_activity_state(); ?>

		<?php bp_nouveau_activity_entry_buttons(); ?>

	</div>

	<?php bp_nouveau_activity_hook( 'before', 'entry_comments' ); ?>

	<?php if ( bp_activity_get_comment_count() || ( is_user_logged_in() && ( bp_activity_can_comment() || bp_is_single_activity() ) ) ) : ?>

		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php bp_nouveau_activity_comment_form(); ?>

		</div>

	<?php endif; ?>

	<?php bp_nouveau_activity_hook( 'after', 'entry_comments' ); ?>

</li>

<?php
bp_nouveau_activity_hook( 'after', 'entry' );
