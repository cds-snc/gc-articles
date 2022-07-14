

// https://rudrastyh.com/gutenberg/plugin-sidebars.html
const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { Fragment } = wp.element;
const { __ } = wp.i18n;

import { PostFields } from "./components/PostFields";
import { JobFields } from "./components/JobFields";
import { TeamMemberFields } from "./components/TeamMemberFields";

registerPlugin('gc-post-meta', {
	render: () => {
		const type = wp.data.select("core/editor").getCurrentPostType();
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem target="gc-post-meta" icon="insert">
					{__("GC Post Meta", "gc-post-meta")}
				</PluginSidebarMoreMenuItem>
				<PluginSidebar name="gc-post-meta" title={__("Custom Fields", "gc-post-meta")} icon="insert">
					{type === "post" && <PostFields />}
					{type === "job" && <JobFields />}
					{type === "team" && <TeamMemberFields />}
				</PluginSidebar>
			</Fragment>
		)
	}
});
