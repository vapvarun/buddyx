<?php
/**
 * Buddyx Profile Completion Widget.
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Profile Completion widget for the logged-in user
 *
 * @subpackage Widgets
 */
class BP_Buddyx_Profile_Completion_Widget extends WP_Widget {

	function __construct() {

		/* Set up optional widget args. */
		$widget_ops = array(
			'classname'   => 'widget_bp_profile_completion_widget widget buddypress',
			'description' => __( 'Show Logged in user Profile Completion Progress.', 'buddyx' ),
		);

		/* Set up the widget. */
		parent::__construct(
			false,
			__( '(BuddyPress) Profile Completion', 'buddyx' ),
			$widget_ops
		);
	}

	/**
	 * Displays the widget.
	 */
	function widget( $args, $instance ) {
		/*
		 * do not do anything if user isn't logged in OR IF user is viewing other members profile.
		 */
		if ( ! is_user_logged_in() || ( bp_is_user() && ! bp_is_my_profile() ) ) {
			return;
		}

		$profile_groups_selected      = isset( $instance['profile_groups_enabled'] ) ? $instance['profile_groups_enabled'] : array();
		$profile_phototype_selected   = ! empty( $instance['profile_photos_enabled'] ) ? $instance['profile_photos_enabled'] : array();
		$profile_hide_widget_selected = ! empty( $instance['profile_hide_widget'] ) ? $instance['profile_hide_widget'] : array();
		$user_progress                = $this->get_user_profile_progress_data( $profile_groups_selected, $profile_phototype_selected );

		// IF nothing selected then return and nothing to display.
		if ( empty( $profile_groups_selected ) && empty( $profile_phototype_selected ) ) {
			return;
		}

		// Hide the widget if "Hide widget once progress hits 100%" selected and progress is 100%
		if ( 100 === (int) $user_progress['completion_percentage'] && ! empty( $instance['profile_hide_widget'] ) ) {
			return;
		}

		/* Widget Template */

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Widget Title
		echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Widget Content

		$progress_label = sprintf( esc_html__( '%s Complete', 'buddyx' ), $user_progress['completion_percentage'] . '%' );
		?>
		<div class="wb-bp-profile-completion-wrap">

			<div class="wb-bp-progress-wrap">
				<div class="wb-bp-progress-text">
					<div class="wb-bp-user-avatar-wrap">
						<div class="wb-bp-user-avatar">
							<?php
							$current_user = wp_get_current_user();
							if ( ( $current_user instanceof WP_User ) ) {
								$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
								echo '<a class="wb-bp-user-link" href="' . esc_url( $user_link ) . '">';
								?>
								<?php
								echo get_avatar( $current_user->user_email, 200 );
								echo '</a>';
							}
							?>
						</div>
						<div class="wb-bp-progress-label">
							<span class="wb-bp-completion"><?php echo esc_html( $user_progress['completion_percentage'] ) . '%'; ?></span>
							<span><?php echo esc_html__( 'Complete', 'buddyx' ); ?></span>
						</div>
					</div>
				</div>
				<div class="wb-bp-progress-container">
					<div class="wb-bp-progress" style="width: <?php echo esc_attr( $user_progress['completion_percentage'] ); ?>%;"></div>
				</div>
			</div>

			<div class="wb-bp-detailed-progress-container">

		<ul class="wb-bp-detailed-progress">

		<?php
		foreach ( $user_progress['groups'] as $single_section_details ) :

			$user_progress_status = ( 0 === $single_section_details['completed'] && $single_section_details['total'] > 0 ) ? 'progress_not_started' : '';
			?>

				<li class="wb-bp-single-section-wrap
				<?php
				echo ( isset( $single_section_details['is_group_completed'] ) && $single_section_details['is_group_completed'] ) ? esc_attr( 'completed ' ) : esc_attr( 'incomplete ' );
				echo esc_attr( $user_progress_status );
				?>
					">
					<span class="wb-bp-section-number">
				<?php echo esc_html( $single_section_details['number'] ); ?>
					</span>
					<span class="wb-bp-section-name">
						<a href="<?php echo esc_url( $single_section_details['link'] ); ?>" class="group-link"><?php echo esc_html( $single_section_details['label'] ); ?></a>
					</span>
					<span class="wb-bp-progress">
						<span class="wb-bp-completed-staus">
							<span class="wb-bp-completed-steps"><?php echo absint( $single_section_details['completed'] ); ?></span>/<span class="wb-bp-total-steps"><?php echo absint( $single_section_details['total'] ); ?></span>
						</span>
					</span>
				</li>
			<?php
		endforeach;
		?>
		</ul>
			</div>

		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Function returns user progress data by checking if data already exists in transient first. IF NO then follow checking the progress logic.
	 *
	 * @param type $profile_groups
	 * @param type $profile_phototype
	 *
	 * @return type
	 */
	function get_user_profile_progress_data( $profile_groups, $profile_phototype ) {

		$user_progress_formmatted = array();
		$user_progress_arr        = $this->get_user_profile_progress( $profile_groups, $profile_phototype );
		$user_progress_formmatted = $this->get_user_profile_progress_formatted( $user_progress_arr );

		return $user_progress_formmatted;
	}

	/**
	 * Function returns logged in user progress based on options selected in the widget form.
	 *
	 * @param type $group_ids
	 * @param type $photo_types
	 *
	 * @return int
	 */
	function get_user_profile_progress( $group_ids, $photo_types ) {

		/* User Progress specific VARS. */
		$user_id                = get_current_user_id();
		$progress_details       = array();
		$grand_total_fields     = 0;
		$grand_completed_fields = 0;
		$total_fields           = 0;
		$completed_fields       = 0;

		/*
		 * Profile Photo
		 */
		$is_profile_photo_disabled = bp_disable_avatar_uploads();
		if ( ! $is_profile_photo_disabled && in_array( 'profile_photo', $photo_types ) ) {

			++$grand_total_fields;

			$is_profile_photo_uploaded = ( bp_get_user_has_avatar( $user_id ) ) ? 1 : 0;

			if ( $is_profile_photo_uploaded ) {
				++$grand_completed_fields;
			}

			$progress_details['photo_type']['profile_photo'] = array(
				'is_uploaded' => $is_profile_photo_uploaded,
				'name'        => __( 'Profile Photo', 'buddyx' ),
			);
		}

		/*
		 * Cover Photo
		 */
		$is_cover_photo_disabled = bp_disable_cover_image_uploads();
		if ( ! $is_cover_photo_disabled && in_array( 'cover_photo', $photo_types ) ) {

			++$grand_total_fields;

			$is_cover_photo_uploaded = ( bp_attachments_get_user_has_cover_image( $user_id ) ) ? 1 : 0;

			if ( $is_cover_photo_uploaded ) {
				++$grand_completed_fields;
			}

			$progress_details['photo_type']['cover_photo'] = array(
				'is_uploaded' => $is_cover_photo_uploaded,
				'name'        => __( 'Cover Photo', 'buddyx' ),
			);
		}

		/*
		 * Groups Fields
		 */
		$profile_groups = bp_xprofile_get_groups(
			array(
				'fetch_fields'     => true,
				'fetch_field_data' => true,
				'user_id'          => $user_id,
			)
		);

		foreach ( $profile_groups as $single_group_details ) {

			if ( empty( $single_group_details->fields ) ) {
				continue;
			}

			/* Single Group Specific VARS */
			$group_id              = $single_group_details->id;
			$single_group_progress = array();

			/*
			 * Consider only selected Groups ids from the widget form settings, skip all others.
			 */

			if ( ! in_array( $group_id, $group_ids ) ) {
				continue;
			}

			// Check if Current Group is repeater if YES then get number of fields inside current group.
			$is_group_repeater_str = bp_xprofile_get_meta( $group_id, 'group', 'is_repeater_enabled', true );
			$is_group_repeater     = ( 'on' === $is_group_repeater_str ) ? true : false;

			/* Loop through all the fields and check if fields completed or not. */
			$group_total_fields     = 0;
			$group_completed_fields = 0;
			foreach ( $single_group_details->fields as $group_single_field ) {

				/*
				 * If current group is repeater then only consider first set of fields.
				 */
				if ( $is_group_repeater ) {

					/*
					 * If field not a "clone number 1" then stop. That means proceed with the first set of fields and restrict others.
					 */
					$field_id     = $group_single_field->id;
					$clone_number = bp_xprofile_get_meta( $field_id, 'field', '_clone_number', true );
					if ( $clone_number > 1 ) {
						continue;
					}
				}

				$field_data_value = maybe_unserialize( $group_single_field->data->value );

				if ( ! empty( $field_data_value ) ) {
					++$group_completed_fields;
				}

				++$group_total_fields;
			}

			/*
			 * Prepare array to return group specific progress details
			 */
			$single_group_progress['group_name']             = $single_group_details->name;
			$single_group_progress['group_total_fields']     = $group_total_fields;
			$single_group_progress['group_completed_fields'] = $group_completed_fields;

			$grand_total_fields     += $group_total_fields;
			$grand_completed_fields += $group_completed_fields;

			$total_fields     += $group_total_fields;
			$completed_fields += $group_completed_fields;

		}

		$progress_details['groups'][] = array(
			'group_name'             => __( 'Profile Fields', 'buddyx' ),
			'group_total_fields'     => $total_fields,
			'group_completed_fields' => $completed_fields,
		);

		/*
		 * Total Fields vs completed fields to calculate progress percentage.
		 */
		$progress_details['total_fields']     = $grand_total_fields;
		$progress_details['completed_fields'] = $grand_completed_fields;

		return apply_filters( 'bp_buddyx_user_progress', $progress_details );
	}

	/**
	 * Function formats user progress to pass on to templates.
	 *
	 * @param type $user_progress_arr
	 *
	 * @return int
	 */
	function get_user_profile_progress_formatted( $user_progress_arr ) {

		/* Groups */

		$loggedin_user_domain = bp_loggedin_user_domain();
		$profile_slug         = bp_get_profile_slug();

		/*
		 * Calculate Total Progress percentage.
		 */
		$profile_completion_percentage = round( ( $user_progress_arr['completed_fields'] * 100 ) / $user_progress_arr['total_fields'] );
		$user_prgress_formatted        = array(
			'completion_percentage' => $profile_completion_percentage,
		);

		/*
		 * Group specific details
		 */
		$listing_number = 1;
		foreach ( $user_progress_arr['groups'] as $group_id => $group_details ) {

			$group_link = trailingslashit( $loggedin_user_domain . $profile_slug . '/edit/group/' . $group_id );

			$user_prgress_formatted['groups'][] = array(
				'number'             => $listing_number,
				'label'              => $group_details['group_name'],
				'link'               => $group_link,
				'is_group_completed' => ( $group_details['group_total_fields'] === $group_details['group_completed_fields'] ) ? true : false,
				'total'              => $group_details['group_total_fields'],
				'completed'          => $group_details['group_completed_fields'],
			);

			$listing_number ++;
		}

		/* Profile Photo */

		if ( isset( $user_progress_arr['photo_type']['profile_photo'] ) ) {

			$change_avatar_link  = trailingslashit( $loggedin_user_domain . $profile_slug . '/change-avatar' );
			$is_profile_uploaded = ( 1 === $user_progress_arr['photo_type']['profile_photo']['is_uploaded'] );

			$user_prgress_formatted['groups'][] = array(
				'number'             => $listing_number,
				'label'              => $user_progress_arr['photo_type']['profile_photo']['name'],
				'link'               => $change_avatar_link,
				'is_group_completed' => ( $is_profile_uploaded ) ? true : false,
				'total'              => 1,
				'completed'          => ( $is_profile_uploaded ) ? 1 : 0,
			);

			$listing_number ++;
		}

		/* Cover Photo */

		if ( isset( $user_progress_arr['photo_type']['cover_photo'] ) ) {

			$change_cover_link = trailingslashit( $loggedin_user_domain . $profile_slug . '/change-cover-image' );
			$is_cover_uploaded = ( 1 === $user_progress_arr['photo_type']['cover_photo']['is_uploaded'] );

			$user_prgress_formatted['groups'][] = array(
				'number'             => $listing_number,
				'label'              => $user_progress_arr['photo_type']['cover_photo']['name'],
				'link'               => $change_cover_link,
				'is_group_completed' => ( $is_cover_uploaded ) ? true : false,
				'total'              => 1,
				'completed'          => ( $is_cover_uploaded ) ? 1 : 0,
			);

			$listing_number ++;
		}

		/**
		 * Filter returns User Progress array in the template friendly format.
		 */
		return apply_filters( 'bp_buddyx_user_progress_formatted', $user_prgress_formatted );
	}

	/**
	 * Widget settings form.
	 */
	function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'                  => __( 'Complete Your Profile', 'buddyx' ),
				'profile_groups_enabled' => array( 0 => 1 ),
				'profile_photos_enabled' => array(
					0 => 'profile_photo',
					1 => 'cover_photo',
				),
			)
		);

		/*
		 * Profile Groups and Profile Cover Photo.
		 */
		if ( function_exists( 'bp_xprofile_get_groups' ) ) {
			$profile_groups = bp_xprofile_get_groups();
		}

		$photos_enabled_arr        = array();
		$is_profile_photo_disabled = bp_disable_avatar_uploads();
		$is_cover_photo_disabled   = bp_disable_cover_image_uploads();

		/*
		 * Show Options only when Profile Photo and Cover option enabled in the Profile Settings.
		 */

		if ( ! $is_profile_photo_disabled ) {
			$photos_enabled_arr['profile_photo'] = __( 'Profile Photo', 'buddyx' );
		}
		if ( ! $is_cover_photo_disabled ) {
			$photos_enabled_arr['cover_photo'] = __( 'Cover Photo', 'buddyx' );
		}

		/* Widget Form HTML */
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'buddyx' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>        <p>
			<label><?php esc_html_e( 'Profile field sets:', 'buddyx' ); ?></label>

		<?php if ( function_exists( 'bp_xprofile_get_groups' ) ) : ?>
		<ul>
			<?php

			foreach ( $profile_groups as $single_group_details ) :
				$is_checked = ( ! empty( $instance['profile_groups_enabled'] ) && in_array( $single_group_details->id, $instance['profile_groups_enabled'] ) );
				?>
				<li>
					<label>
						<input class="widefat" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'profile_groups_enabled' ) ); ?>[]" value="<?php echo esc_attr( $single_group_details->id ); ?>"
				<?php checked( $is_checked ); ?>
							   />
				<?php echo esc_html( $single_group_details->name ); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

		</p>

		<?php if ( ! empty( $photos_enabled_arr ) ) : ?>
			<p>
				<label><?php esc_html_e( 'Profile photos:', 'buddyx' ); ?></label>

			<ul>
				<?php foreach ( $photos_enabled_arr as $photos_value => $photos_label ) : ?>
					<li>
						<label>
							<input class="widefat" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'profile_photos_enabled' ) ); ?>[]" value="<?php echo esc_attr( $photos_value ); ?>" <?php checked( ( ! empty( $instance['profile_photos_enabled'] ) && in_array( $photos_value, $instance['profile_photos_enabled'] ) ) ); ?>/>
									<?php echo esc_html( $photos_label ); ?>
						</label>
					</li>

			<?php endforeach; ?>
			</ul>

			</p>
					<?php endif; ?>
		<p>
			<label>
				<input class="widefat" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'profile_hide_widget' ) ); ?>" value="hide_widget" <?php checked( ( ! empty( $instance['profile_hide_widget'] ) && 'hide_widget' == $instance['profile_hide_widget'] ) ); ?>/>
			<?php esc_html_e( 'Hide widget once progress hits 100%', 'buddyx' ); ?>
			</label>

		</p>
		<p>
			<small>
		<?php esc_html_e( 'Note: This widget is only displayed if a member is logged in.', 'buddyx' ); ?>
			</small>
		</p>

		<?php
	}

}

add_action(
	'widgets_init',
	function () {
		if ( class_exists( 'BuddyPress' ) ) {
			register_widget( 'BP_Buddyx_Profile_Completion_Widget' );
		}
	}
);
