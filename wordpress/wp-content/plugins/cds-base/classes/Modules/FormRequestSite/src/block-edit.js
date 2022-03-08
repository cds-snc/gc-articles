import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { Disabled } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';
import { name } from '../block.json';

const Edit = ({ attributes }) => {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<Disabled>
				<ServerSideRender block={name} attributes={attributes} />
			</Disabled>
		</div>
	);
};

export default Edit;
