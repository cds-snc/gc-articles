
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { name } from '../block.json';

const Edit = ({ attributes }) => {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<ServerSideRender block={name} attributes={attributes} />
		</div>
	);
};

export default Edit;
