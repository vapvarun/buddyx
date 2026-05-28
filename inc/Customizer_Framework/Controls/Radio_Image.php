<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Radio_Image
 *
 * Radio buttons rendered as a grid of clickable previews. Each choice
 * value can be either:
 *   - a string image URL (legacy shape) - rendered as `<img src="...">`
 *   - an array `[ 'image' => url, 'label' => 'Display Name' ]` (new
 *     shape) - rendered as `<img>` with a visible name caption beneath.
 * The new shape lets callers turn this control into a swatch picker
 * (e.g. style preset cards) without forking the control class.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Radio_Image
 */
class Radio_Image extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-radio-image';

	/**
	 * Render the control content.
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		$name = '_customize-radio-' . $this->id;
		?>
		<fieldset>
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="buddyx-radio-image-grid">
				<?php foreach ( $this->choices as $value => $choice ) : ?>
					<?php
					if ( is_array( $choice ) ) {
						$image = isset( $choice['image'] ) ? (string) $choice['image'] : '';
						$label = isset( $choice['label'] ) ? (string) $choice['label'] : (string) $value;
					} else {
						$image = (string) $choice;
						$label = (string) $value;
					}
					?>
					<label class="buddyx-radio-image-option">
						<input
							type="radio"
							value="<?php echo esc_attr( (string) $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
						/>
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $label ); ?>" />
						<?php if ( is_array( $choice ) && '' !== $label ) : ?>
							<span class="buddyx-radio-image-label"><?php echo esc_html( $label ); ?></span>
						<?php endif; ?>
					</label>
				<?php endforeach; ?>
			</div>
		</fieldset>
		<?php
	}
}
