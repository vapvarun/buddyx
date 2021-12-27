<?php
/**
 * BuddyPress - Groups Header
 *
 * @since 3.0.0
 * @version 7.0.0
 */

$group_link   = bp_get_group_permalink();
$admin_link   = trailingslashit( $group_link . 'admin' );
$group_avatar = trailingslashit( $admin_link . 'group-avatar' );
?>

<div class="item-header-cover-image-wrapper hide-header-cover-image">
    <div id="item-header-cover-image">
        <?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
            <div id="item-header-avatar">
                    <?php if ( bp_is_item_admin() ) { ?>
                            <a href="<?php echo $group_avatar; ?>" class="link-change-profile-image bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Change Group Photo', 'buddyx' ); ?>">
                                    <?php 
                                        if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) { ?>
                                            <i class="bb-icon-edit-thin"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-edit"></i>
                                        <?php }
                                    ?>
                            </a>
                    <?php } ?>
                    <?php bp_group_avatar(); ?>
            </div><!-- #item-header-avatar -->
        <?php endif; ?>

    <?php if ( ! bp_nouveau_groups_front_page_description() ) : ?>
        <div id="item-header-content">

            <h2 class="bp-group-title"><?php echo esc_html( bp_get_group_name() ); ?></h2> 
            <?php if ( function_exists( 'bp_nouveau_the_group_meta' ) ) { ?>   
                <p class="highlight group-status"><strong><?php echo esc_html( bp_nouveau_the_group_meta( array( 'keys' => 'status' ) ) ); ?></strong></p>
			<?php } else { ?>
                <p class="highlight group-status"><strong><?php echo wp_kses( bp_nouveau_group_meta()->status, array( 'span' => array( 'class' => array() ) ) ); ?></strong></p>
            <?php } ?>
            
            <p class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>">
                <?php
                /* translators: %s = last activity timestamp (e.g. "active 1 hour ago") */
                printf( __( 'active %s', 'buddyx' ), bp_get_group_last_active() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            </p>

            <?php if ( function_exists( 'bp_nouveau_the_group_meta' ) ) { ?>
                <?php echo isset( bp_nouveau_the_group_meta()->group_type_list ) ? bp_nouveau_the_group_meta()->group_type_list : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php }else { ?>
                <?php echo isset( bp_nouveau_group_meta()->group_type_list ) ? bp_nouveau_group_meta()->group_type_list : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php } ?>

            <?php
			if ( function_exists( 'bp_group_type_list' ) ) {
				bp_group_type_list(
					bp_get_group_id(),
					array(
						'label'        => array(
							'plural'   => __( 'Group Types', 'buddyx' ),
							'singular' => __( 'Group Type', 'buddyx' ),
						),
						'list_element' => 'span',
					)
				);
			}
			?>

            <?php bp_nouveau_group_hook( 'before', 'header_meta' ); ?>

            <?php if ( bp_nouveau_group_has_meta_extra() ) : ?>
                <div class="item-meta">
                    <?php if ( function_exists( 'bp_nouveau_the_group_meta' ) ) { ?>   
                        <?php echo bp_nouveau_the_group_meta( array( 'keys' => 'extra' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php } else { ?>
                        <?php echo bp_nouveau_group_meta()->extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php } ?>
                </div><!-- .item-meta -->
            <?php endif; ?>

            <?php bp_nouveau_group_header_buttons(); ?>

        </div><!-- #item-header-content -->
    <?php endif; ?>

        <?php bp_get_template_part( 'groups/single/parts/header-item-actions' ); ?>

    </div><!-- #item-header-cover-image -->
</div><!-- .item-header-cover-image-wrapper -->

<?php if ( ! bp_nouveau_groups_front_page_description() && bp_nouveau_group_has_meta( 'description' ) ) : ?>
	<div class="desc-wrap">
		<div class="group-description">
			<?php bp_group_description(); ?>
		</div><!-- //.group_description -->
	</div>
<?php endif; ?>
