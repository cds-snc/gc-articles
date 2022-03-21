import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, Disabled, TextControl, SelectControl } from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { name } from './block.json';

const Edit = ({ attributes, setAttributes }) => {

	const { placeholderValue, listId, emailLabel, subscribeLabel } = attributes;

	const blockProps = useBlockProps();

	let listValues = [{id: "",label: __("Select a list") }, ...CDS_VARS.notify_list_ids] || [];

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
					<TextControl
						label={__("Email field label", "cds-snc")}
						value={emailLabel}
						onChange={(value) => setAttributes({
							emailLabel: value,
						})}
					/>
					<TextControl
						label={__("Subscribe button label", "cds-snc")}
						value={subscribeLabel}
						onChange={(value) => setAttributes({
							subscribeLabel: value,
						})}
					/>
					<SelectControl
						label={__("List", "cds-snc")}
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
