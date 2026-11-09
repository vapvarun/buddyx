<?php
/**
 * The template for displaying search forms.
 *
 * @package buddyx
 */

?>

<?php
// The header search and a page's own search form can share a page: the first
// form (the header search, which header CSS styles by #searchform) keeps the
// classic id; later forms get a unique one and are styled by .search-form.
$buddyx_search_form_id = did_action( 'buddyx_search_form' ) ? 'searchform-' . ( did_action( 'buddyx_search_form' ) + 1 ) : 'searchform';
/**
 * Fires before each theme search form is printed.
 *
 * The theme counts these calls to give every form after the first a unique id.
 *
 * @since 5.2.0
 */
do_action( 'buddyx_search_form' );

$buddyx_search_placeholder = __( 'Enter Keyword', 'buddyx' );
if ( has_filter( 'search_placeholder' ) ) {
	// Unprefixed name used up to 5.1.9; still applied, with a deprecation notice.
	$buddyx_search_placeholder = apply_filters_deprecated( 'search_placeholder', array( $buddyx_search_placeholder ), '5.2.0', 'buddyx_search_placeholder' );
}

/**
 * Filters the placeholder text of the theme search form.
 *
 * @since 5.2.0
 *
 * @param string $placeholder Placeholder text. Default 'Enter Keyword'.
 */
$buddyx_search_placeholder = apply_filters( 'buddyx_search_placeholder', $buddyx_search_placeholder );
?>
<form id="<?php echo esc_attr( $buddyx_search_form_id ); ?>" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">	
	<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'buddyx' ); ?></span>
	<input type="search" class="search-field-top" placeholder="<?php echo esc_attr( $buddyx_search_placeholder ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<input name="submit" type="submit" value="<?php esc_attr_e( 'Go', 'buddyx' ); ?>" />
</form>
