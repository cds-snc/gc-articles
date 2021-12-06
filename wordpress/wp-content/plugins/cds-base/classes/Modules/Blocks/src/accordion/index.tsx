import * as React from 'react';
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps, RichText, InnerBlocks } from "@wordpress/block-editor";

registerBlockType("cds-snc/accordion", {
  title: __("Accordion", "cds-snc"),
  icon: "plus-alt",
  category: "layout",
  attributes: {
    title: {
      type: "string",
      source: "html",
      selector: "summary",
    },
  },

  edit({ attributes, setAttributes }) {
    return (
        <details open>
          <RichText
            tagName="summary" // The tag here is the element output and editable in the admin
            value={attributes.title} // Any existing content, either from the database or an attribute default
            allowedFormats={['core/bold', 'core/italics']}
            onChange={(title) => setAttributes({ title })} // Store updated content as a block attribute
            placeholder={__("Title", "cds-snc")} // Display this text before any content has been added by the user
          />
          <InnerBlocks />
      </details>
    );
  },

  save({ attributes }) {
    const blockProps = useBlockProps.save();

    return (
      <details>
        <summary>
          {attributes.title}
        </summary>
        <InnerBlocks.Content />
      </details>
    );
  },
});
