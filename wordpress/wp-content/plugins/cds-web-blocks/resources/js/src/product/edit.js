import { useBlockProps } from '@wordpress/block-editor';
import TextControl from './components/TextControl';
import { __ } from '@wordpress/i18n';

const Edit = ({ attributes, setAttributes }) => {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<p>{__('Product', 'cds-web')}</p>
			<TextControl label={__('Name', 'cds-web')} metaKey="cds_product_name" />
		</div>
	);
};

export default Edit;
