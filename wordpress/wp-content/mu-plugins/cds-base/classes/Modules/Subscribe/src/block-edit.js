import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, Disabled, TextControl, SelectControl } from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { name } from '../block.json';

const Edit = ({ attributes, setAttributes }) => {

	const { placeholderValue, listId } = attributes;
	const blockProps = useBlockProps();

	let listValues = CDS_VARS.notify_list_ids || [];

	listValues = listValues.map((item) => {
		return { value: item?.id, label: item?.label }
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Subscribe form settings', "cds-snc")}>
					<TextControl
						label={__("Placeholder text", "cds-snc")}
						value={placeholderValue}
						onChange={(value) => setAttributes({
							placeholderValue: value,
						})}
					/>
					<SelectControl
						label={__("List ID", "cds-snc")}
						value={listId}
						onChange={(value) => setAttributes({
							listId: value,
						})}
						options={listValues} />
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
