

// https://rudrastyh.com/gutenberg/plugin-sidebars.html
const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { Fragment } = wp.element;
const { PanelBody } = wp.components;

import { PageSelect } from "./components/PageSelect";
const { __ } = wp.i18n;

registerPlugin('cds-wpml-mods', {
	render: () => {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem target="cds-wpml-mods" icon="admin-links">
					{__("Translations", "cds-wpml-mods")}
				</PluginSidebarMoreMenuItem>
				<PluginSidebar name="cds-wpml-mods" title={__("Translations", "cds-wpml-mods")} icon="admin-links">
					<PanelBody title={__("Linked page", "cds-wpml-mods")} initialOpen={true}>
						<PageSelect />
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		)
	}
});
