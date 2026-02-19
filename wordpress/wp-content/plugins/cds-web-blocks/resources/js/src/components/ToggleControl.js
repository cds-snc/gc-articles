import { ToggleControl } from '@wordpress/components';

import { parseMetaKey, updateValue, getValue } from "../utils";

const { withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;

export const CDSToggleControl = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value, content) => {
                const { key } = parseMetaKey(props.metaKey);
                const newValue = updateValue(props.metaKey, value, content);
                dispatch('core/editor').editPost({ meta: { [key]: newValue } });
            }
        }
    }),
    withSelect((select, props) => {
        const { key } = parseMetaKey(props.metaKey);
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[key],
        }
    })
)((props) => {
    const value = getValue(props.metaKey, props.metaValue);
    return (
        <ToggleControl
            label={props.label}
            checked={value}
            onChange={(content) => { props.setMetaValue(content, props.metaValue) }}
        />
    );
});

export default CDSToggleControl;