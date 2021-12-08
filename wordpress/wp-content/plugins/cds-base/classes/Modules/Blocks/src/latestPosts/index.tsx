import * as React from 'react';
import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { __ } from "@wordpress/i18n";
import { ToggleControl, PanelBody, PanelRow } from "@wordpress/components";
import {
  InspectorControls,
} from "@wordpress/block-editor";

const latestPostsBlockName = 'core/latest-posts';
const namespace = 'cds-snc/latest-posts';
const hideReadMoreClass = 'read-more--hidden';

function filterCoverBlockAlignments(settings, name) {
  if (name === latestPostsBlockName) {
    const newAttributes = { ...settings.attributes, ...{ showReadMore: { type: 'boolean', default: true }} };
    
    return Object.assign({}, settings, { attributes: newAttributes });
  }

  return settings;
}

addFilter(
  'blocks.registerBlockType',
  namespace,
  filterCoverBlockAlignments,
);

const addInspectorControl = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    const {
      // @ts-ignore
      attributes: { showReadMore },
      // @ts-ignore
      setAttributes, 
      // @ts-ignore
      name,
    } = props;
    if (name !== latestPostsBlockName) {
      return <BlockEdit {...props} />;
    }

    return (
      <>
       <BlockEdit {...props} />
        <InspectorControls>
          <PanelBody title={__('Show “Read More”', 'cds-snc')} initialOpen={true}>
            <ToggleControl
              label={__('Show “Read More” link', 'intro-to-filters')}
              checked={showReadMore}
              onChange={(value) => {
                setAttributes({ showReadMore: value });
              }}
            />
          </PanelBody>
        </InspectorControls>
      </>
    );
  };
}, 'withInspectorControl');

addFilter(
  'editor.BlockEdit',
  namespace,
  addInspectorControl,
);

/**
 * Add "read-more--hidden" class to the block in the editor
 */
 const addSizeClass = createHigherOrderComponent((BlockListBlock) => {
  return (props) => {
    const {
      // @ts-ignore
      attributes: { showReadMore },
      // @ts-ignore
      className, 
      // @ts-ignore
      name,
    } = props;

    if (name !== latestPostsBlockName) {
      return <BlockListBlock {...props} />;
    }

    return (
      <BlockListBlock
        {...props}
        // @ts-ignore
        className={showReadMore ? '' : ` ${hideReadMoreClass} `}
      />
    );
  };
}, 'withClientIdClassName');

addFilter(
   'editor.BlockListBlock',
   namespace,
   addSizeClass
);

/**
 * Add "read-more--hidden" class to the block on the front end
 */
 function addSizeClassFrontEnd(props, block, attributes) {
  if (block.name !== latestPostsBlockName) {
    return props;
  }

  const { className } = props;
  const { showReadMore } = attributes;

  return Object.assign({}, props, {
    className: ` ${hideReadMoreClass} `
  });
}
