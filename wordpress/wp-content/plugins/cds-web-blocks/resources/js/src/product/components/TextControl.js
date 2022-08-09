import { TextControl } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';

export const CDSTextControl = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value, content) => {
                let metaKey = props.metaKey.split(":");
                let key = "";
                let prop = "";
                if (metaKey.length === 2) {
                    key = metaKey[0];
                    prop = metaKey[1];
                }

                try {
                    let data = {};
                    data = JSON.parse(content);
                    data[prop] = value;
                    value = JSON.stringify(data)
                } catch (err) {
                    console.log(err.message);
                }

                dispatch('core/editor').editPost({ meta: { [key]: value } });
            }
        }
    }),
    withSelect((select, props) => {
        let key = props.metaKey.split(":");
        if (key.length === 2) {
            key = key[0];
        }
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[key],
        }
    })
)((props) => {
    const key = props.metaKey.split(":");
    let value = props.metaValue;
    if (value && key.length === 2) {
        value = JSON.parse(props.metaValue)[key[1]];
    }

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