const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import TextControl from "./TextControl";
import ToggleControl from "./ToggleControl";

export const JobFields = () => {
    return (
        <div>
            <PanelBody title={__("Status ", "gc-post-meta")} initialOpen={true}>
                <ToggleControl label={__("Archived", "gc-post-meta")} metaKey="gc_job_archived" />
            </PanelBody>
            <PanelBody title={__("Lever ", "gc-post-meta")} initialOpen={true}>
                <TextControl label={__("ID", "gc-post-meta")} metaKey="gc_lever_id" />
            </PanelBody>
        </div>
    )
}

