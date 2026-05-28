<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Radio_Buttonset
 *
 * Radio buttons rendered as a horizontal button group instead of vanilla
 * radio inputs. Same data model as Radio. Used in Pro for compact
 * single-select UIs (e.g. layout direction, container size).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Radio_Buttonset
 */
class Radio_Buttonset extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-radio-buttonset';

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
			<div class="buddyx-buttonset" role="radiogroup">
				<?php foreach ( $this->choices as $value => $label ) : ?>
					<label class="buddyx-buttonset-option">
						<input
							type="radio"
							value="<?php echo esc_attr( (string) $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
						/>
						<span class="buddyx-buttonset-label"><?php echo esc_html( (string) $label ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
		</fieldset>
		<?php
	}
}
