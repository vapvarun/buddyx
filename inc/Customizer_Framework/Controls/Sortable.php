<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Sortable
 *
 * Drag-to-reorder list with per-row enable toggle. Stores a JSON-encoded
 * array of { slug, enabled } objects. Used in Pro for builder-style UIs
 * like "header element order" or "footer column visibility".
 *
 * UI behavior (drag handle, checkbox, JSON sync) is wired by
 * customizer-controls.js (Task 18).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Sortable
 */
class Sortable extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-sortable';

	/**
	 * Expose the option-label map to the JS template.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['choices'] = $this->choices ?? array();
	}

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<fieldset class="buddyx-sortable" data-setting="<?php echo esc_attr( $this->setting ? $this->setting->id : '' ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<ul class="buddyx-sortable-list" role="list"></ul>
			<input type="hidden" class="buddyx-sortable-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
