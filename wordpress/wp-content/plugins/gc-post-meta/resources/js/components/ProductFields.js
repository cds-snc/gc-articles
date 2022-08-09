const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import ToggleControl from "./ToggleControl";

export const ProductFields = () => {
    return (
        <div>
            <PanelBody title={__("Status ", "gc-post-meta")} initialOpen={true}>
                <ToggleControl label={__("On Homepage", "gc-post-meta")} metaKey="gc_on_homepage" />
            </PanelBody>
        </div>
    )
}

