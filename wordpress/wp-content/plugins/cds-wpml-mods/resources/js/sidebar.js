

// https://rudrastyh.com/gutenberg/plugin-sidebars.html
const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { Fragment } = wp.element;
const { PanelRow } = wp.components;
const { dispatch } = wp.data;

import { PageSelect } from "./components/PageSelect";
const { __ } = wp.i18n;

/* Remove the WPML "Language" panel */
dispatch('core/edit-post').removeEditorPanel('meta-box-icl_div');

registerPlugin('cds-wpml-mods', {
	icon: 'translation',
	render: () => {
		return (
			<Fragment>
				<PluginDocumentSettingPanel title={__('Translations', 'cds-wpml-mods')} initialOpen="true">
					<PanelRow>
						<PageSelect />
					</PanelRow>
				</PluginDocumentSettingPanel>
			</Fragment>
		)
	}
});
