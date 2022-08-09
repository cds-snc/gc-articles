import { TextControl } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { parseMetaKey, updateValue, getValue } from "../../utils";

export const CDSTextControl = compose(
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
        <TextControl
            style={{ padding: '10px' }}
            type="text"
            label={props.label}
            value={value || ""}
            onChange={(content) => { props.setMetaValue(content, props.metaValue) }}
        />
    );
});

export default CDSTextControl;