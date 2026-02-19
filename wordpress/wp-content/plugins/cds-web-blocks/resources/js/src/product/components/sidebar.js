const { PanelBody } = wp.components;
const { __ } = wp.i18n;
import ToggleControl from "../../components/ToggleControl";
import SelectControl from "../../components/SelectControl";

export const ProductFields = () => {
    return (
        <div>
            <PanelBody title={__("Status", "cds-web")} initialOpen={true}>
                <ToggleControl label={__("On Homepage", "cds-web")} metaKey="cds_product:on_home_page" />
                <SelectControl
                    options={[
                        { label: 'Select one', value: '' },
                        { label: 'Discovery', value: 'Discovery' },
                        { label: 'Alpha', value: 'alpha' },
                        { label: 'Beta', value: 'beta' },
                        { label: 'Live', value: 'live' },
                    ]}
                    label={__("Phase", "cds-web")}
                    metaKey="cds_product:phase"
                />
            </PanelBody>
        </div>
    )
}

