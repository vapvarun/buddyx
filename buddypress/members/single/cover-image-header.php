<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since 3.0.0
 * @version 3.0.0
 */

$has_cover_image          = '';
$has_cover_image_position = '';
$displayed_user           = bp_get_displayed_user();
$cover_image_url          = bp_attachments_get_attachment(
	'url',
	array(
		'object_dir' => 'members',
		'item_id'    => $displayed_user->id,
	)
);

if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) {
    if ( ! empty( $cover_image_url ) ) {
        $cover_image_position = bp_get_user_meta( bp_displayed_user_id(), 'bp_cover_position', true );
        $has_cover_image      = ' has-cover-image';
        if ( '' !== $cover_image_position ) {
            $has_cover_image_position = 'has-position';
        }
    }
}
?>

<div id="cover-image-container">
    <div id="header-cover-image" class="<?php echo esc_attr( $has_cover_image_position . $has_cover_image ); ?>">
        <?php
            if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) {
                if ( ! empty( $cover_image_url ) ) {
                    echo '<img class="header-cover-img" src="' . esc_url( $cover_image_url ) . '"' . ( '' !== $cover_image_position ? ' data-top="' . esc_attr( $cover_image_position ) . '"' : '' ) . ( '' !== $cover_image_position ? ' style="top: ' . esc_attr( $cover_image_position ) . 'px"' : '' ) . ' alt="" />';
                }
            }
            ?>

            <?php if ( bp_is_my_profile() ) { ?>
                        <a href="<?php echo bp_get_members_component_link( 'profile', 'change-cover-image' ); ?>" class="link-change-cover-image bp-tooltip" data-bp-tooltip-pos="right" data-bp-tooltip="<?php esc_attr_e( 'Change Cover Photo', 'buddyx' ); ?>">
                                <?php 
                                    if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) { ?>
                                        <i class="bb-icon-edit-thin"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-edit"></i>
                                    <?php }
                                ?>
                        </a>
            <?php } ?>

            <?php 
            if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) {
                if ( ! empty( $cover_image_url ) ) { ?>
                    <a href="#" class="position-change-cover-image bp-tooltip" data-bp-tooltip-pos="right" data-bp-tooltip="<?php esc_attr_e( 'Reposition Cover Photo', 'buddyx' ); ?>">
                        <i class="bb-icon-move"></i>
                    </a>
                    <div class="header-cover-reposition-wrap">
                        <a href="#" class="button small cover-image-cancel"><?php _e( 'Cancel', 'buddyx' ); ?></a>
                        <a href="#" class="button small cover-image-save"><?php _e( 'Save Changes', 'buddyx' ); ?></a>
                        <span class="drag-element-helper"><i class="bb-icon-menu"></i><?php _e( 'Drag to move cover photo', 'buddyx' ); ?></span>
                        <img src="<?php echo esc_url( $cover_image_url ); ?>" alt="<?php _e( 'Cover photo', 'buddyx' ); ?>" />
                    </div>
                <?php } 
            }
        ?>
    </div>
</div><!-- #cover-image-container -->    

<div class="item-header-cover-image-wrapper">
    <div id="item-header-cover-image">
        <div id="item-header-avatar">
                <?php if ( bp_is_my_profile() && ! bp_disable_avatar_uploads() ) { ?>
                        <a href="<?php bp_members_component_link( 'profile', 'change-avatar' ); ?>" class="link-change-profile-image bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Change Profile Photo', 'buddyx' ); ?>">
                            <?php 
                                if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) { ?>
                                    <i class="bb-icon-edit-thin"></i>
                                <?php } else { ?>
                                    <i class="fa fa-edit"></i>
                                <?php }
                            ?>
                        </a>
                <?php } ?>
                <?php bp_displayed_user_avatar( 'type=full' ); ?>
        </div><!-- #item-header-avatar -->

        <div id="item-header-content">

            <?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
                <h2 class="user-nicename">@<?php bp_displayed_user_mentionname(); ?></h2>
            <?php endif; ?>

            <?php if ( is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ) {
                if ( true === bp_member_type_enable_disable() && true === bp_member_type_display_on_profile() ) {
                    echo bp_get_user_member_type( bp_displayed_user_id() );
                }
            } ?>

            <?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>

            <?php if ( bp_nouveau_member_has_meta() ) : ?>
                <div class="item-meta">

                    <?php bp_nouveau_member_meta(); ?>

                </div><!-- #item-meta -->
                <div class="buddyx-badge">
                    <?php
                    if ( function_exists( 'buddyx_profile_achievements' ) ):
                        buddyx_profile_achievements();
                    endif;
                    ?>
                </div><!-- .buddyx-badge -->
            <?php endif; ?>

            <?php
            if ( function_exists( 'bp_member_type_list' ) ) :
                bp_member_type_list(
                    bp_displayed_user_id(),
                    array(
                        'label'        => array(
                            'plural'   => __( 'Member Types', 'buddyx' ),
                            'singular' => __( 'Member Type', 'buddyx' ),
                        ),
                        'list_element' => 'span',
                    )
                );
            endif;
			?>

            <?php
            if ( function_exists( 'bp_get_user_social_networks_urls' ) ):
                echo bp_get_user_social_networks_urls();
            endif;
            ?>

            <?php
            bp_nouveau_member_header_buttons(
                array(
                    'container'         => 'ul',
                    'button_element'    => 'button',
                    'container_classes' => array( 'member-header-actions' ),
                )
            );
            ?>

        </div><!-- #item-header-content -->

    </div><!-- #item-header-cover-image -->
</div><!-- .item-header-cover-image-wrapper -->
