<?php
/**
 * Block render template.
 *
 * @package BuddyX\Buddyx
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<p><?php esc_html_e( 'BLOCKTITLE — Edit me!', 'buddyx' ); ?></p>
</div>
