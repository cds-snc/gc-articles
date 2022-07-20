const { SelectControl } = wp.components;
const { useState } = wp.element;
const { __ } = wp.i18n;

export const PageSelect = () => {
    const [size, setSize] = useState('');

    return (
        <SelectControl
            label={__("Page Name", "cds-wpml-mods")}
            value={size}
            options={[
                { label: __("Select a page", "cds-wpml-mods"), value: '' },
                { label: __("Page 1", "cds-wpml-mods"), value: '1' },
                { label: __("Page 2", "cds-wpml-mods"), value: '2' },
                { label: __("Page 3", "cds-wpml-mods"), value: '3' },
            ]}
            onChange={(newSize) => setSize(newSize)}
            __nextHasNoMarginBottom
        />
    );
};