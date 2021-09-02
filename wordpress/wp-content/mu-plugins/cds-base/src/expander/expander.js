import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps, RichText } from "@wordpress/block-editor";

registerBlockType("cds-snc/expander", {
  title: __("Expander", "cds-snc"),
  icon: "megaphone",
  category: "layout",
  example: {},
  attributes: {
    content: {
      type: "string",
      source: "html",
      selector: "div",
    },
    title: {
      type: "string",
      source: "html",
      selector: "h3",
    },
  },

  edit({ attributes, setAttributes }) {
    return (
      <>
        <RichText
          tagName="h3" // The tag here is the element output and editable in the admin
          value={attributes.title} // Any existing content, either from the database or an attribute default
          allowedFormats={[]} // Allow the content to be made bold or italic, but do not allow other formatting options
          onChange={(title) => setAttributes({ title })} // Store updated content as a block attribute
          placeholder={__("Title", "cds-snc")} // Display this text before any content has been added by the user
        />
        <RichText
          tagName="div" // The tag here is the element output and editable in the admin
          value={attributes.content} // Any existing content, either from the database or an attribute default
          // allowedFormats={["core/bold", "core/italic", "core/list"]} // Allow the content to be made bold or italic, but do not allow other formatting options
          onChange={(content) => setAttributes({ content })} // Store updated content as a block attribute
          placeholder={__("Summary", "cds-snc")} // Display this text before any content has been added by the user
        />
      </>
    );
  },

  save({ attributes }) {
    const blockProps = useBlockProps.save();

    return (
      <details>
        <summary>
          <h3>{attributes.title}</h3>
        </summary>
        <RichText.Content
          {...blockProps}
          tagName="div"
          value={attributes.content}
        />
      </details>
    );
  },
});
