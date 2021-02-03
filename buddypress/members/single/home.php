<?php
/**
 * BuddyPress - Members Home
 *
 * @since   1.0.0
 * @version 3.0.0
 */
?>

	<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>

	<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

		<?php bp_nouveau_member_header_template_part(); ?>

	</div><!-- #item-header -->
	
	<div class="site-wrapper member-home">
		<div class="bp-wrap">
			<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

				<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>

			<?php endif; ?>

			<div id="item-body" class="item-body">

				<?php bp_nouveau_member_template_part(); ?>

			</div><!-- #item-body -->
		</div><!-- // .bp-wrap -->
		<?php if ( get_theme_mod( 'buddypress_sidebar_option' ) == 'right' || get_theme_mod( 'buddypress_sidebar_option' ) == 'both' ) : ?>
			<?php get_sidebar( 'buddypress' ); ?> 
		<?php endif; ?>
	</div><!-- .site-wrapper -->

	<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>
