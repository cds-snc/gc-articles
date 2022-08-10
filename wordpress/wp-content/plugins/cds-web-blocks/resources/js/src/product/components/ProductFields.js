const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import ToggleControl from "../../components/ToggleControl";

export const ProductFields = () => {
    return (
        <div>
            <PanelBody title={__("Status", "cds-web")} initialOpen={true}>
                <ToggleControl label={__("On Homepage", "cds-web")} metaKey="cds_product:on_home_page" />
            </PanelBody>
        </div>
    )
}

