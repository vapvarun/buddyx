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
	 * Prepends a "Default (theme)" entry mapped to '' so customers can
	 * explicitly opt out of any font-family override and fall through to
	 * the global stylesheet's font stack. Output_Builder skips emitting
	 * font-family when the saved value is '', preserving theme defaults.
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
		// Prepend the "Default (theme)" option so it sits at the top of
		// the dropdown and is the natural fallback for customers who
		// haven't explicitly chosen a family.
		return array( '' => esc_html__( 'Default (theme)', 'buddyx' ) ) + $out;
	}

	/**
	 * Render the control content.
	 *
	 * Layout: 5 logical rows, 9 inputs total. All paired-rows are 50/50 grid
	 * so column inputs align perfectly. Labels are kept to one word/line to
	 * prevent vertical drift between paired columns. Unit hints (px, em) live
	 * on the input itself via inline `unit` decoration and step attribute.
	 *
	 *   1. Font family            (full)
	 *   2. Weight | Style         (paired — italic/normal added)
	 *   3. Size   | Line height   (paired)
	 *   4. Letter | Transform     (paired)
	 *   5. Align  | Decoration    (paired — both newly exposed)
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

				<label class="buddyx-typo-row buddyx-typo-row--full">
					<span><?php esc_html_e( 'Family', 'buddyx' ); ?></span>
					<select class="buddyx-typo-family"></select>
				</label>

				<div class="buddyx-typo-row-pair">
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Weight', 'buddyx' ); ?></span>
						<select class="buddyx-typo-weight"></select>
					</label>
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Style', 'buddyx' ); ?></span>
						<select class="buddyx-typo-style">
							<option value="normal"><?php esc_html_e( 'Normal', 'buddyx' ); ?></option>
							<option value="italic"><?php esc_html_e( 'Italic', 'buddyx' ); ?></option>
						</select>
					</label>
				</div>

				<div class="buddyx-typo-row-pair">
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Size', 'buddyx' ); ?> <em class="buddyx-typo-unit">px</em></span>
						<input class="buddyx-typo-size" type="number" min="8" max="200" step="1" />
					</label>
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Line height', 'buddyx' ); ?></span>
						<input class="buddyx-typo-line-height" type="number" min="0.8" max="3" step="0.05" />
					</label>
				</div>

				<div class="buddyx-typo-row-pair">
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Letter', 'buddyx' ); ?> <em class="buddyx-typo-unit">em</em></span>
						<input class="buddyx-typo-letter-spacing" type="number" min="-0.1" max="0.5" step="0.005" />
					</label>
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Transform', 'buddyx' ); ?></span>
						<select class="buddyx-typo-transform">
							<option value="none"><?php esc_html_e( 'None', 'buddyx' ); ?></option>
							<option value="uppercase"><?php esc_html_e( 'UPPER', 'buddyx' ); ?></option>
							<option value="lowercase"><?php esc_html_e( 'lower', 'buddyx' ); ?></option>
							<option value="capitalize"><?php esc_html_e( 'Capitalize', 'buddyx' ); ?></option>
						</select>
					</label>
				</div>

				<div class="buddyx-typo-row-pair">
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Align', 'buddyx' ); ?></span>
						<select class="buddyx-typo-align">
							<option value=""><?php esc_html_e( 'Inherit', 'buddyx' ); ?></option>
							<option value="left"><?php esc_html_e( 'Left', 'buddyx' ); ?></option>
							<option value="center"><?php esc_html_e( 'Center', 'buddyx' ); ?></option>
							<option value="right"><?php esc_html_e( 'Right', 'buddyx' ); ?></option>
							<option value="justify"><?php esc_html_e( 'Justify', 'buddyx' ); ?></option>
						</select>
					</label>
					<label class="buddyx-typo-row">
						<span><?php esc_html_e( 'Decoration', 'buddyx' ); ?></span>
						<select class="buddyx-typo-decoration">
							<option value=""><?php esc_html_e( 'None', 'buddyx' ); ?></option>
							<option value="underline"><?php esc_html_e( 'Underline', 'buddyx' ); ?></option>
							<option value="line-through"><?php esc_html_e( 'Strikethrough', 'buddyx' ); ?></option>
							<option value="overline"><?php esc_html_e( 'Overline', 'buddyx' ); ?></option>
						</select>
					</label>
				</div>
			</div>
			<input type="hidden" class="buddyx-typo-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
