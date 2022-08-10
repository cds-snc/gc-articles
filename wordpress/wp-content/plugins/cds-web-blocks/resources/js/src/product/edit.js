import { useBlockProps } from '@wordpress/block-editor';
import TextControl from '../components/TextControl';
import { __ } from '@wordpress/i18n';
import GCPostMetaSlotFill from "../../../../../gc-post-meta/resources/js/slot";
import { ProductFields } from './components/ProductFields';

const Edit = ({ attributes, setAttributes }) => {

    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <p>{__('Product', 'cds-web')}</p>
            <GCPostMetaSlotFill>
                <ProductFields />
            </GCPostMetaSlotFill>
            <TextControl label={__('Subtitle', 'cds-web')} metaKey="cds_product:subtitle" />
            <TextControl label={__('Description', 'cds-web')} metaKey="cds_product:description" />
            <p>{__('Call to action', 'cds-web')}</p>
            <TextControl label={__('Button Text', 'cds-web')} metaKey="cds_product:button-text" />
            <TextControl label={__('Button Link', 'cds-web')} metaKey="cds_product:button-link" />
            <TextControl label={__('Button Aria', 'cds-web')} metaKey="cds_product:button-aria" />
            <p>{__('Other', 'cds-web')}</p>
            <TextControl label={__('Weight', 'cds-web')} metaKey="cds_product:weight" />
            <TextControl label={__('TagId', 'cds-web')} metaKey="cds_product:tag-id" />
        </div>
    );
};

export default Edit;
