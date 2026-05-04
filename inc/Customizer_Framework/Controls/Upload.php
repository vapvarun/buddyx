<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Upload
 *
 * Generic file upload control extending WP core's upload control.
 * Just normalizes Kirki-style choices.mime_types into core's mime_type arg.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Upload
 */
class Upload extends \WP_Customize_Upload_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-upload';

	/**
	 * Constructor — bridges Kirki args to core's upload control args.
	 *
	 * @param \WP_Customize_Manager $manager
	 * @param string                $id
	 * @param array                 $args
	 */
	public function __construct( $manager, $id, $args = array() ) {
		// Kirki passes mime types via choices.mime_types; core expects mime_type at top level.
		if ( isset( $args['choices']['mime_types'] ) ) {
			$args['mime_type'] = $args['choices']['mime_types'];
		}
		parent::__construct( $manager, $id, $args );
	}
}
