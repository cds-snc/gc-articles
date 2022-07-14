import { useBlockProps } from '@wordpress/block-editor';
import TextControl from './components/TextControl';

const Edit = ({ attributes, setAttributes }) => {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<TextControl label="Name" metaKey="cds_web_product_name" />
			<TextControl label="Title" metaKey="cds_web_product_title" />
		</div>
	);
};

export default Edit;
