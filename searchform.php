<?php
/**
 * The template for displaying search forms.
 *
 * @package buddyx
 */
?>

<form id="searchform" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">	
	<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'buddyx' ); ?></span>
	<input type="search" class="search-field-top" placeholder="<?php echo esc_attr( apply_filters( 'search_placeholder', __( 'Enter Keyword', 'buddyx' ) ) ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<input name="submit" type="submit"  value="<?php esc_attr_e( 'Go', 'buddyx' ); ?>" />
</form>
