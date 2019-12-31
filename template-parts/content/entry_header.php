<?php
/**
 * Template part for displaying a post's header
 *
 * @package buddyx
 */

namespace Brndle\Brndle;

?>
<?php if ( ! bp_is_user() && ! bp_is_group_single() && ! bp_is_group_create() ) : ?>
<header class="entry-header">
	<?php
	get_template_part( 'template-parts/content/entry_title', get_post_type() );

	get_template_part( 'template-parts/content/entry_meta', get_post_type() );

	if ( ! is_search() ) {
		get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() );
	}
	?>
</header><!-- .entry-header -->
<?php endif; ?>
