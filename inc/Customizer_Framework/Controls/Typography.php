<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Typography
 *
 * Six-input typography picker: family, weight, size (px), line-height,
 * letter-spacing (em), text-transform. Stores a structured value array
 * that Output_Builder turns into a multi-property declaration block.
 *
 * Live preview wiring + value sync handled by customizer-controls.js (Task 18).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Typography
 *
 * Reads available font families from theme.json fontFamilies (registered in
 * 5.0.3) so users see the same palette of fonts the Site Editor exposes.
 * Falls back to a sane minimum if theme.json hasn't registered any.
 */
class Typography extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-typography';

	/**
	 * Expose default + family/weight choices to the JS template.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default']      = $this->setting ? $this->setting->default : array();
		$this->json['fontFamilies'] = self::available_font_families();
		$this->json['weights']      = array( '300', '400', '500', '600', '700' );
	}

	/**
	 * Read theme.json fontFamilies into a slug => label map.
	 *
	 * @return array<string, string>
	 */
	protected static function available_font_families(): array {
		$theme_json = function_exists( 'wp_get_global_settings' ) ? wp_get_global_settings() : array();
		$families   = $theme_json['typography']['fontFamilies']['theme'] ?? array();
		$out        = array();
		foreach ( $families as $f ) {
			if ( isset( $f['slug'], $f['name'] ) ) {
				$out[ $f['slug'] ] = $f['name'];
			}
		}
		if ( empty( $out ) ) {
			$out = array(
				'system' => 'System UI',
				'inter'  => 'Inter',
			);
		}
		return $out;
	}

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<fieldset>
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="buddyx-typography-controls" data-setting="<?php echo esc_attr( $this->setting ? $this->setting->id : '' ); ?>">
				<label class="buddyx-typo-row">
					<span><?php esc_html_e( 'Family', 'buddyx' ); ?></span>
					<select class="buddyx-typo-family"></select>
				</label>
				<label class="buddyx-typo-row">
					<span><?php esc_html_e( 'Weight', 'buddyx' ); ?></span>
					<select class="buddyx-typo-weight"></select>
				</label>
				<label class="buddyx-typo-row">
					<span><?php esc_html_e( 'Size (px)', 'buddyx' ); ?></span>
					<input class="buddyx-typo-size" type="number" min="8" max="200" />
				</label>
				<label class="buddyx-typo-row">
					<span><?php esc_html_e( 'Line height', 'buddyx' ); ?></span>
					<input class="buddyx-typo-line-height" type="number" min="0.8" max="3" step="0.05" />
				</label>
				<label class="buddyx-typo-row">
					<span><?php esc_html_e( 'Letter spacing (em)', 'buddyx' ); ?></span>
					<input class="buddyx-typo-letter-spacing" type="number" min="-0.1" max="0.5" step="0.005" />
				</label>
				<label class="buddyx-typo-row">
					<span><?php esc_html_e( 'Transform', 'buddyx' ); ?></span>
					<select class="buddyx-typo-transform">
						<option value="none">none</option>
						<option value="uppercase">uppercase</option>
						<option value="lowercase">lowercase</option>
						<option value="capitalize">capitalize</option>
					</select>
				</label>
			</div>
			<input type="hidden" class="buddyx-typo-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
