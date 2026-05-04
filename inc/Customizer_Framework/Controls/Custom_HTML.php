<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Custom_HTML
 *
 * Renders raw HTML from the setting's `default` value with a strict
 * whitelist via wp_kses. Mirrors Kirki's Custom field which had no
 * value semantics — just a way to drop info text or buttons into
 * the customizer UI.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Custom_HTML
 */
class Custom_HTML extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-custom-html';

	/**
	 * Render the control content.
	 *
	 * The HTML body comes from $this->setting->default (Kirki convention).
	 * Strictly kses-filtered to a small whitelist that covers the patterns
	 * BuddyX uses today (info text + occasional buttons + links).
	 */
	public function render_content() {
		if ( ! empty( $this->label ) ) {
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
		}
		if ( ! empty( $this->description ) ) {
			echo '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>';
		}
		$html = $this->setting ? (string) $this->setting->default : '';
		echo wp_kses(
			$html,
			array(
				'a'      => array(
					'href'   => true,
					'target' => true,
					'rel'    => true,
					'class'  => true,
					'style'  => true,
				),
				'p'      => array( 'class' => true, 'style' => true ),
				'strong' => array(),
				'em'     => array(),
				'br'     => array(),
				'hr'     => array( 'class' => true, 'style' => true ),
				'span'   => array( 'class' => true, 'style' => true ),
				'div'    => array( 'class' => true, 'style' => true ),
				'h1'     => array( 'class' => true, 'style' => true ),
				'h2'     => array( 'class' => true, 'style' => true ),
				'h3'     => array( 'class' => true, 'style' => true ),
				'h4'     => array( 'class' => true, 'style' => true ),
				'input'  => array(
					'type'        => true,
					'value'       => true,
					'class'       => true,
					'id'          => true,
					'name'        => true,
					'placeholder' => true,
					'style'       => true,
				),
				'button' => array(
					'type'  => true,
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'img'    => array(
					'src'    => true,
					'alt'    => true,
					'width'  => true,
					'height' => true,
					'class'  => true,
					'style'  => true,
				),
			)
		);
	}
}
