import TextControl from "./components/TextControl"

// https://rudrastyh.com/gutenberg/plugin-sidebars.html
const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { PanelBody } = wp.components;

registerPlugin('gc-post-meta', {
	render: () => {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem target="gc-post-meta" icon="insert">
					{__("GC Post Meta", "gc-post-meta")}
				</PluginSidebarMoreMenuItem>
				<PluginSidebar name="gc-post-meta" title={__("Custom Fields", "gc-post-meta")} icon="insert">
					<PanelBody title={__("Author information", "gc-post-meta")} initialOpen={true}>
						<TextControl label={__("Name!", "gc-post-meta")} metaKey="gc_author_name" />
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		)
	}
});
