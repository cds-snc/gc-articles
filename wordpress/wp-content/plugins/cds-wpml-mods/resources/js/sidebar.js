

// https://rudrastyh.com/gutenberg/plugin-sidebars.html
const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginSidebarMoreMenuItem, PluginDocumentSettingPanel } = wp.editPost;
const { Fragment } = wp.element;
const { PanelBody, PanelRow } = wp.components;

import { PageSelect } from "./components/PageSelect";
const { __ } = wp.i18n;

registerPlugin('cds-wpml-mods', {
	render: () => {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem target="cds-wpml-mods" icon="admin-links">
					{__("Translations", "cds-wpml-mods")}
				</PluginSidebarMoreMenuItem>


				<PluginDocumentSettingPanel title={__('Translations', 'cds-wpml-mods')} initialOpen="true">
					<PanelRow>
						Hello there.
					</PanelRow>
				</PluginDocumentSettingPanel>

				<PluginSidebar name="cds-wpml-mods" title={__("Translations", "cds-wpml-mods")} icon="admin-links">
					<PanelBody title={__("Linked page", "cds-wpml-mods")} initialOpen={true}>
						<PageSelect />
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		)
	}
});
