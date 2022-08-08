import { TextControl } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';

export const CDSHiddenControl = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } });
            }
        }
    }),
    withSelect((select, props) => {

        let data = {};
        const blocks = select('core/block-editor').getBlocks();

        if (blocks && blocks[0] && blocks[0].innerBlocks) {
            const innerBlocks = blocks[0].innerBlocks;

            innerBlocks.forEach((block) => {
                data[block.attributes.className] = block.attributes;
            })

            window.myData = data;
        }

        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
        }
    })
)((props) => {
    return (
        <TextControl
            style={{ padding: '10px' }}
            type="text"
            label={props.label}
            value={props.metaValue || ""}
            onChange={(content) => {
                console.log("window.myData", JSON.stringify(window.myData))
                props.setMetaValue(content)
            }
            }
        />
    );
});

export default CDSHiddenControl;