import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import TextControl from './components/TextControl';
import HiddenControl from './components/HiddenControl';
import { __ } from '@wordpress/i18n';

const Edit = ({ attributes, setAttributes }) => {

    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <p>{__('Product', 'cds-web')}</p>
            <TextControl label={__('Name', 'cds-web')} metaKey="cds_product" />
            <HiddenControl label={__('Hidden', 'cds-web')} metaKey="cds_product" />
            <InnerBlocks template={[
                ['core/button', { id: "test-c", text: 'My button and text!!', url: 'http://github1.com', linkTarget: '_blank', rel: 'noreferrer noopener', className: 'buttonaction' }],
            ]}
                allowedBlocks={[
                    'core/column',
                    'core/button',
                    'core/columns',
                    'core/heading',
                    'core/paragraph',
                ]} />

        </div>
    );
};

export default Edit;
