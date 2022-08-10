const {
    ToggleControl,
} = wp.components;

const { withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;

export const CDSToggleControl = compose(
    withDispatch((dispatch, props) => {
        return {
            setMetaValue: (value) => {
                dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } });
            }
        }
    }),
    withSelect((select, props) => {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
        }
    })
)((props) => {
    return (
        <ToggleControl
            label={props.label}
            checked={props.metaValue}
            onChange={(content) => { props.setMetaValue(content) }}
        />
    );
});

export default CDSToggleControl;