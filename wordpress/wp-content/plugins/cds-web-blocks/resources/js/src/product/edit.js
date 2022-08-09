import { useBlockProps } from '@wordpress/block-editor';
import TextControl from './components/TextControl';
import { __ } from '@wordpress/i18n';

// http://localhost/cds/wp-json/wp/v2/product?_embed

const Edit = ({ attributes, setAttributes }) => {

    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <p>{__('Product', 'cds-web')}</p>
            <TextControl label={__('Name', 'cds-web')} metaKey="cds_product:name" />
            <TextControl label={__('Description', 'cds-web')} metaKey="cds_product:description" />
        </div>
    );
};

export default Edit;
