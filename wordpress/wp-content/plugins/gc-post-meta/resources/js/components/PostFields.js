const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import TextControl from "./TextControl";

export const PostFields = () => {
    return (
        <PanelBody title={__("Author information", "gc-post-meta")} initialOpen={true}>
            <TextControl label={__("Name", "gc-post-meta")} metaKey="gc_author_name" />
        </PanelBody>
    )
}

