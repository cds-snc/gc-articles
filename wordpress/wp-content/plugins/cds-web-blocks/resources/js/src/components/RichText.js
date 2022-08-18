import { RichText } from '@wordpress/block-editor';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { parseMetaKey, updateValue, getValue } from "../utils";

export const CDSRichText = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value, content) => {
                const { key } = parseMetaKey(props.metaKey);
                let newValue = updateValue(props.metaKey, value, content);
                if (props.parser) {
                    const parserKey = `parsed-${props.metaKey}`;
                    let data = JSON.parse(newValue);
                    data[parserKey] = props.parser(value);
                    newValue = JSON.stringify(data);
                }
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