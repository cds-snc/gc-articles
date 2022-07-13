const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import TextControl from "./TextControl";

export const JobFields = () => {
    return (
        <PanelBody title={__("Lever ", "gc-post-meta")} initialOpen={true}>
            <TextControl label={__("Lever Id", "gc-post-meta")} metaKey="gc_lever_id" />
        </PanelBody>
    )
}

