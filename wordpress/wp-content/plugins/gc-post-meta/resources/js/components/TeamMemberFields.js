const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import ToggleControl from "./ToggleControl";

export const TeamMemberFields = () => {
    return (
        <div>
            <PanelBody title={__("Status ", "gc-post-meta")} initialOpen={true}>
                <ToggleControl label={__("Archived", "gc-post-meta")} metaKey="gc_team_member_archived" />
                <ToggleControl label={__("Key Contact", "gc-post-meta")} metaKey="gc_team_member_key_contact" />
            </PanelBody>
            
        </div>
    )
}

