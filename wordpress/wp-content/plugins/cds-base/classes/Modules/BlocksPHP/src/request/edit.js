import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';
import './editor.scss';
import { name } from './block.json';
import ServerSideRender from '@wordpress/server-side-render';
export default function Edit() {
	return (
		<div {...useBlockProps()}>
			<Disabled>
				<ServerSideRender block={name} />
			</Disabled>
		</div>
	);
}
