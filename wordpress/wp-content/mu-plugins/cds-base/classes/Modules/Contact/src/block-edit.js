import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, Disabled, TextControl } from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { name } from '../block.json';

const Edit = ({ attributes, setAttributes }) => {
	const { placeholderValue } = attributes;
	const blockProps = useBlockProps();
	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Contact form settings', "cds-snc")}>
					<TextControl
						label={__("Placeholder text", "cds-snc")}
						value={placeholderValue}
						onChange={(value) => setAttributes({
							placeholderValue: value,
						})}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<Disabled>
					<ServerSideRender block={name} attributes={attributes} />
				</Disabled>
			</div>
		</>
	);
};

export default Edit;
