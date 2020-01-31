<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>

<div id="cover-image-container">
    <div id="header-cover-image"></div>
</div><!-- #cover-image-container -->    

<div class="container">
    <div class="item-header-cover-image-wrapper">
        <div id="item-header-cover-image">
            <div id="item-header-avatar">
                <a href="<?php bp_displayed_user_link(); ?>">

                    <?php bp_displayed_user_avatar( 'type=full' ); ?>

                </a>
            </div><!-- #item-header-avatar -->

            <div id="item-header-content">

                <?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
                <h2 class="user-nicename"><?php echo bp_core_get_user_displayname( bp_displayed_user_id() ); ?></h2>
                <?php endif; ?>

                <?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>

                <?php if ( bp_nouveau_member_has_meta() ) : ?>
                    <div class="item-meta">

                        <?php bp_nouveau_member_meta(); ?>

                    </div><!-- #item-meta -->
                <?php endif; ?>

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
</div><!-- .container -->
