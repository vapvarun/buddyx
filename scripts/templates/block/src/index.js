import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

registerBlockType( 'buddyx/BLOCKNAME', {
	edit: () => {
		const blockProps = useBlockProps();
		return (
			<div { ...blockProps }>
				<p>{ __( 'BLOCKTITLE — Edit me!', 'buddyx' ) }</p>
			</div>
		);
	},
} );
