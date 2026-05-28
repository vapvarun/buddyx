<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Checkbox
 *
 * Standard checkbox. Different from Toggle (which renders a visual on/off
 * slider for the 'switch' field type). Used by BuddyX Pro for dense
 * option lists where a traditional checkbox UX is preferred.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Checkbox
 */
class Checkbox extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-checkbox';

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<label>
			<input
				type="checkbox"
				value="1"
				<?php $this->link(); ?>
				<?php checked( (bool) $this->value() ); ?>
			/>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title-inline"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
		</label>
		<?php
	}
}
