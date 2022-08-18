import { RichText } from '@wordpress/block-editor';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { parseMetaKey, updateValue, getValue } from "../utils";

export const CDSRichText = compose(
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
        <RichText
            tagName="div"
            className="cds-rich-text"
            allowedFormats={props.allowedFormats}
            label={props.label}
            value={value || ""}
            onChange={(content) => { props.setMetaValue(content, props.metaValue) }}
        />
    );
});

export default CDSRichText;