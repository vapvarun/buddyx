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
				),
				'p'      => array( 'class' => true ),
				'strong' => array(),
				'em'     => array(),
				'br'     => array(),
				'span'   => array( 'class' => true ),
				'div'    => array( 'class' => true ),
				'input'  => array(
					'type'  => true,
					'value' => true,
					'class' => true,
				),
				'button' => array(
					'type'  => true,
					'class' => true,
				),
				'img'    => array(
					'src'    => true,
					'alt'    => true,
					'width'  => true,
					'height' => true,
					'class'  => true,
				),
			)
		);
	}
}
