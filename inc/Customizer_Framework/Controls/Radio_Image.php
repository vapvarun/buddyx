<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Radio_Image
 *
 * Radio buttons rendered as a grid of clickable image previews.
 * Choices arg is array( slug => image_url ).
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
				<?php foreach ( $this->choices as $value => $img ) : ?>
					<label class="buddyx-radio-image-option">
						<input
							type="radio"
							value="<?php echo esc_attr( (string) $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
						/>
						<img src="<?php echo esc_url( (string) $img ); ?>" alt="<?php echo esc_attr( (string) $value ); ?>" />
					</label>
				<?php endforeach; ?>
			</div>
		</fieldset>
		<?php
	}
}
