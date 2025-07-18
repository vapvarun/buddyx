<?php
/**
 * The template for BuddyBoss - Activity Feed (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * This template can be overridden by copying it to yourtheme/bb-buddypress/activity/entry.php.
 *
 * @since   BuddyPress 3.0.0
 * @version 1.0.0
 * @package BuddyBoss
 */

bp_nouveau_activity_hook( 'before', 'entry' );

$activity_id    = bp_get_activity_id();
$activity_metas = bb_activity_get_metadata( $activity_id );

$link_preview_string = '';
$link_url            = '';

$link_preview_data = ! empty( $activity_metas['_link_preview_data'][0] ) ? maybe_unserialize( $activity_metas['_link_preview_data'][0] ) : array();
if ( ! empty( $link_preview_data ) && count( $link_preview_data ) ) {
	$link_preview_string = wp_json_encode( $link_preview_data );
	$link_url            = ! empty( $link_preview_data['url'] ) ? $link_preview_data['url'] : '';
}

$link_embed = $activity_metas['_link_embed'][0] ?? '';
if ( ! empty( $link_embed ) ) {
	$link_url = $link_embed;
}

// translators: %s: User display name.
$activity_popup_title = sprintf( esc_html__( '%s\'s post', 'buddyx' ), bp_core_get_user_displayname( bp_get_activity_user_id() ) );

?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php echo esc_attr( $activity_id ); ?>" data-bp-activity-id="<?php echo esc_attr( $activity_id ); ?>" data-bp-timestamp="<?php bp_nouveau_activity_timestamp(); ?>" data-bb-updated-timestamp="<?php echo function_exists( 'bb_nouveau_activity_updated_timestamp' ) ? esc_attr( bb_nouveau_activity_updated_timestamp() ) : ''; ?>" data-bp-activity="<?php ( function_exists( 'bp_nouveau_edit_activity_data' ) ) ? bp_nouveau_edit_activity_data() : ''; ?>" data-link-preview='<?php echo esc_html( $link_preview_string ); ?>' data-link-url='<?php echo empty( $link_url ) ? '' : esc_url( $link_url ); ?>' data-activity-popup-title='<?php echo empty( $activity_popup_title ) ? '' : esc_html( $activity_popup_title ); ?>'>

	<?php bb_nouveau_activity_entry_bubble_buttons(); ?>

	<div class="bb-pin-action">
		<span class="bb-pin-action_button" data-balloon-pos="up" data-balloon="<?php esc_attr_e( 'Pinned Post', 'buddyx' ); ?>">
			<i class="bb-icon-f bb-icon-thumbtack"></i>
		</span>
		<?php
		$notification_type = bb_activity_enabled_notification( 'bb_activity_comment', bp_loggedin_user_id() );
		if ( ! empty( $notification_type ) && ! empty( array_filter( $notification_type ) ) ) {
			?>
			<span class="bb-mute-action_button" data-balloon-pos="up" data-balloon="<?php esc_attr_e( 'Turned off notifications', 'buddyx' ); ?>">
				<i class="bb-icon-f bb-icon-bell-slash"></i>
			</span>
			<?php
		}
		?>
	</div>

	<div class="activity-card-head">
		<h6 class="card-head-content-type">
			<?php echo buddyx_bp_get_activity_css_first_class(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</h6>
	</div>

	<div class="activity-item-head">

		<?php
		global $activities_template;

		$user_link       = bp_get_activity_user_link();
		$user_id         = bp_get_activity_user_id();
		$hp_profile_attr = ! empty( $user_id ) ? 'data-bb-hp-profile="' . esc_attr( $user_id ) . '"' : '';

		if ( bp_is_active( 'groups' ) && ! bp_is_group() && buddypress()->groups->id === bp_get_activity_object_name() ) :

			// If group activity.
			$group_id        = (int) $activities_template->activity->item_id;
			$group           = groups_get_group( $group_id );
			$group_name      = bp_get_group_name( $group );
			$group_name      = ! empty( $group_name ) ? esc_html( $group_name ) : '';
			$group_permalink = bp_get_group_permalink( $group );
			$activity_link   = bp_activity_get_permalink( $activities_template->activity->id, $activities_template->activity );
			$activity_link   = ! empty( $activity_link ) ? esc_url( $activity_link ) : '';
			$hp_group_attr   = ! empty( $group_id ) ? 'data-bb-hp-group="' . esc_attr( $group_id ) . '"' : '';
			?>
			<div class="bp-activity-head-group">
				<div class="activity-group-avatar">
					<div class="group-avatar">
						<a class="group-avatar-wrap mobile-center" href="<?php echo esc_url( $group_permalink ); ?>" <?php echo wp_kses_post( $hp_group_attr ); ?>>
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- bp_core_fetch_avatar() returns HTML-escaped output
							echo bp_core_fetch_avatar(
								array(
									'item_id'    => $group->id,
									'avatar_dir' => 'group-avatars',
									'type'       => 'thumb',
									'object'     => 'group',
									'width'      => 100,
									'height'     => 100,
									'class'      => 'avatar bb-hp-group-avatar',
								)
							);
							?>
						</a>
					</div>
					<div class="author-avatar">
						<a href="<?php echo esc_url( $user_link ); ?>" <?php echo wp_kses_post( $hp_profile_attr ); ?>>
							<?php
							bp_activity_avatar(
								array(
									'type'  => 'thumb',
									'class' => 'avatar bb-hp-profile-avatar',
								)
							);
							?>
						</a>
					</div>
				</div>

				<div class="activity-header activity-header--group">
					<div class="activity-group-heading">
						<a href="<?php echo esc_url( $group_permalink ); ?>" <?php echo wp_kses_post( $hp_group_attr ); ?>>
							<?php echo esc_html( $group_name ); ?>
						</a>
					</div>
					<div class="activity-group-post-meta">
						<span class="activity-post-author">
							<?php
							$activity_type   = bp_get_activity_type();
							$activity_object = bp_get_activity_object_name();

							if ( 'groups' === $activity_object && 'activity_update' === $activity_type ) {
								// Show only user link and display name.
								?>
								<a href="<?php echo esc_url( $user_link ); ?>" <?php echo wp_kses_post( $hp_profile_attr ); ?>>
									<?php echo esc_html( bp_core_get_user_displayname( $activities_template->activity->user_id ) ); ?>
								</a>
								<?php
							} else {
								// Show the default activity action.
								bp_activity_action();
							}
							?>
						</span>
						<a href="<?php echo esc_url( $activity_link ); ?>">
							<?php
							$activity_date_recorded = bp_get_activity_date_recorded();
							printf(
								'<span class="time-since" data-livestamp="%1$s">%2$s</span>',
								esc_attr( bp_core_get_iso8601_date( $activity_date_recorded ) ),
								esc_html( bp_core_time_since( $activity_date_recorded ) )
							);
							?>
						</a>
						<?php
						if ( function_exists( 'bp_nouveau_activity_is_edited' ) ) {
							bp_nouveau_activity_is_edited();
						}
						if ( function_exists( 'bp_nouveau_activity_privacy' ) ) {
							bp_nouveau_activity_privacy();
						}
						if (
							function_exists( 'bb_is_enabled_group_activity_topics' ) &&
							bb_is_enabled_group_activity_topics()
						) {
							?>
							<p class="activity-topic 3">
								<?php
								if (
									function_exists( 'bb_activity_topics_manager_instance' ) &&
									method_exists( bb_activity_topics_manager_instance(), 'bb_get_activity_topic_url' )
								) {
									echo wp_kses_post(
										bb_activity_topics_manager_instance()->bb_get_activity_topic_url(
											array(
												'activity_id' => bp_get_activity_id(),
												'html'        => true,
											)
										)
									);
								}
								?>
							</p>
							<?php
						}
						?>
					</div>
				</div>
			</div>

		<?php else : ?>

			<div class="activity-avatar item-avatar">
				<a href="<?php echo esc_url( $user_link ); ?>" <?php echo wp_kses_post( $hp_profile_attr ); ?>>
					<?php
						bp_activity_avatar(
							array(
								'type'  => 'full',
								'class' => 'avatar bb-hp-profile-avatar',
							)
						);
					?>
				</a>
			</div>

			<div class="activity-header">

				<?php bp_activity_action(); ?>

				<p class="activity-date">
					<a href="<?php echo esc_url( bp_activity_get_permalink( $activity_id ) ); ?>">
						<?php
						$activity_date_recorded = bp_get_activity_date_recorded();
						printf(
							'<span class="time-since" data-livestamp="%1$s">%2$s</span>',
							bp_core_get_iso8601_date( $activity_date_recorded ),
							bp_core_time_since( $activity_date_recorded )
						);
						?>
					</a>
					<?php
					if ( function_exists( 'bp_nouveau_activity_is_edited' ) ) {
						bp_nouveau_activity_is_edited();
					}
					?>
				</p>
				<?php
				if ( function_exists( 'bp_nouveau_activity_privacy' ) ) {
					bp_nouveau_activity_privacy();
				}
				if (
					(
						'groups' === $activities_template->activity->component &&
						function_exists( 'bb_is_enabled_group_activity_topics' ) &&
						bb_is_enabled_group_activity_topics()
					) ||
					(
						'groups' !== $activities_template->activity->component &&
						function_exists( 'bb_is_enabled_activity_topics' ) &&
						bb_is_enabled_activity_topics()
					)
				) {
					?>
					<p class="activity-topic 4">
						<?php
						if (
							function_exists( 'bb_activity_topics_manager_instance' ) &&
							method_exists( bb_activity_topics_manager_instance(), 'bb_get_activity_topic_url' )
						) {
							echo wp_kses_post(
								bb_activity_topics_manager_instance()->bb_get_activity_topic_url(
									array(
										'activity_id' => bp_get_activity_id(),
										'html'        => true,
									)
								)
							);
						}
						?>
					</p>
					<?php
				}
				?>
			</div>
			<?php
		endif;
		?>

	</div>

	<div class="activity-content <?php bp_activity_entry_css_class(); ?>">

		<?php
		bp_nouveau_activity_hook( 'before', 'activity_content' );

		if ( bp_nouveau_activity_has_content() ) :
			?>
			<div class="activity-inner"><?php bp_nouveau_activity_content(); ?></div>
			<?php
		endif;

		bp_nouveau_activity_hook( 'after', 'activity_content' );
		bp_nouveau_activity_state();
		bb_activity_load_progress_bar_state();
		bp_nouveau_activity_entry_buttons();
		?>

	</div>

	<?php
	bp_nouveau_activity_hook( 'before', 'entry_comments' );

	$closed_notice = bb_get_close_activity_comments_notice( $activity_id );
	if ( ! empty( $closed_notice ) ) {
		?>

		<div class='bb-activity-closed-comments-notice'><?php echo esc_html( $closed_notice ); ?></div>
		<?php
	}

	if ( bp_activity_can_comment() ) {
		$class = 'activity-comments';
		if ( 'blogs' === bp_get_activity_object_name() ) {
			$class .= get_option( 'thread_comments' ) ? ' threaded-comments threaded-level-' . get_option( 'thread_comments_depth' ) : '';
		} else {
			$class .= bb_is_activity_comment_threading_enabled() ? ' threaded-comments threaded-level-' . bb_get_activity_comment_threading_depth() : '';
		}
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php
			if ( bp_activity_get_comment_count() ) {
				bp_activity_comments();
			}

			if ( is_user_logged_in() ) {
				bp_nouveau_activity_comment_form();
			}
			?>

		</div>

		<?php
	}
	bp_nouveau_activity_hook( 'after', 'entry_comments' );
	?>

</li>

<?php
bp_nouveau_activity_hook( 'after', 'entry' );
