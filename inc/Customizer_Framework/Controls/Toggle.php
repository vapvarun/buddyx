<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Toggle
 *
 * Visual on/off slider, stores 0 or 1. Field type string is 'switch' to
 * match Kirki's Checkbox_Switch naming; class is named 'Toggle' because
 * 'Switch' is a reserved PHP keyword.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Toggle (registered as type 'switch')
 */
class Toggle extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-switch';

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<label class="buddyx-switch-label">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<span class="buddyx-switch">
				<input
					type="checkbox"
					class="buddyx-switch-input"
					value="1"
					<?php $this->link(); ?>
					<?php checked( (int) $this->value(), 1 ); ?>
				/>
				<span class="buddyx-switch-slider" aria-hidden="true"></span>
			</span>
		</label>
		<?php
	}
}
