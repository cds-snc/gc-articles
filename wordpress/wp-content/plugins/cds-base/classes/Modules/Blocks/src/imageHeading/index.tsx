import * as React from 'react';
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";
import { RichText, MediaUpload, MediaUploadCheck, InspectorControls, BlockControls, MediaPlaceholder } from "@wordpress/block-editor";
import { PanelBody, PanelRow, SelectControl, RangeControl, Button, Toolbar } from '@wordpress/components';


import { Path, SVG } from '@wordpress/primitives';
const UploaderIcon = () => {
    return (
        <SVG viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <Path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM5 4.5h14c.3 0 .5.2.5.5v8.4l-3-2.9c-.3-.3-.8-.3-1 0L11.9 14 9 12c-.3-.2-.6-.2-.8 0l-3.6 2.6V5c-.1-.3.1-.5.4-.5zm14 15H5c-.3 0-.5-.2-.5-.5v-2.4l4.1-3 3 1.9c.3.2.7.2.9-.1L16 12l3.5 3.4V19c0 .3-.2.5-.5.5z" />
        </SVG>
    )
}

const ALLOWED_MEDIA_TYPES = ['image'];

/* https://github.com/zgordon/advanced-gutenberg-course/blob/master/blocks/01-gallery/index.js */
export interface Image {
    id?: number,
    width?: number,
    height?: number,
    src?: string,
    alt?: string,
    caption?: string
}

export interface BlockAttributes {
    headingTag: string,
    iconSize: number,
    iconPosition: string,
    image: Image,
    heading: string
}

const Heading = (props) => {
    const { as: Component, children } = props;
    return <Component className="heading">{children}</Component>
}

const Settings = ({ headingTag, iconSize, iconPosition, setAttributes }) => {
    return <InspectorControls>
        <PanelBody title={__("Heading", "cds-snc")} initialOpen={true}>
            <PanelRow>
                <SelectControl
                    label={__("Type", "cds-snc")}
                    value={headingTag}
                    options={[
                        { label: __("Heading 2", "cds-snc"), value: "h2" },
                        { label: __("Heading 3", "cds-snc"), value: "h3" },
                        { label: __("Heading 4", "cds-snc"), value: "h4" },
                        { label: __("Heading 5", "cds-snc"), value: "h5" },
                    ]}
                    onChange={(newval) => setAttributes({ headingTag: newval })}
                />
            </PanelRow>
        </PanelBody>
        <PanelBody title={__("Icon", "cds-snc")} initialOpen={true}>
            <PanelRow>
                <RangeControl
                    label={__("Size", "cds-snc")}
                    value={iconSize}
                    onChange={(value) => setAttributes({ iconSize: Number(value) })}
                    min={10}
                    max={50}
                />
            </PanelRow>
            <PanelRow>
                <SelectControl
                    label={__("Position", "cds-snc")}
                    value={iconPosition}
                    options={[
                        { label: __("Left", "cds-snc"), value: "icon-left" },
                        { label: __("Right", "cds-snc"), value: "icon-right" },

                    ]}
                    onChange={(newval) => setAttributes({ iconPosition: newval })}
                />
            </PanelRow>
        </PanelBody>
    </InspectorControls>
}

function MediaUploader({ image, iconSize, setAttributes }) {
    return (
        <MediaUploadCheck>
            <BlockControls>
                <Toolbar>
                    <MediaUpload
                        title={__("Select or upload icon", "cds-snc")}
                        autoOpen={true}
                        onSelect={(img) => {
                            setAttributes({
                                image: {
                                    id: img.id
                                    , width: img.width
                                    , height: img.width
                                    , src: img.url
                                    , alt: img.alt
                                    , caption: img.caption
                                }
                            })

                        }}
                        allowedTypes={ALLOWED_MEDIA_TYPES}
                        value={image && image.id ? image.id : ""}
                        render={({ open }) => (
                            <Button
                                className="components-toolbar__control"
                                label={__("Edit Icon", "cds-snc")}
                                icon="edit"
                                onClick={open}
                            />
                        )}
                    />
                </Toolbar>
            </BlockControls>

            {!image || !image.src ? (
                <MediaPlaceholder
                    icon={UploaderIcon}
                    labels={{
                        title: __("Heading Icon", "cds-snc"),
                        instructions: __(
                            "Select or upload icon",
                            "cds-snc"
                        )
                    }}
                    accept="images/*"
                    onSelect={(img) => {
                        setAttributes({
                            image: {
                                id: img.id
                                , width: img.width
                                , height: img.width
                                , src: img.url
                                , alt: img.alt
                                , caption: img.caption
                            }
                        })
                    }}
                />
            ) : (
                <div className="image">
                    <img src={image.src} width={iconSize} />
                </div>
            )}

        </MediaUploadCheck>
    );
}

registerBlockType("cds-snc/image-heading", {
    title: __("Icon & Heading", "cds-snc"),
    icon: "heading",
    category: "layout",
    attributes: {
        image: {
            type: "object",
        },
        headingTag: {
            type: "string",
            default: "h2"
        },
        iconSize: {
            type: "number",
            default: 30
        },
        iconPosition: {
            type: "string",
            default: "left"
        },
        heading: {
            type: "string",
            source: "html",
            selector: ".heading",
        },
    },

    edit({ attributes, setAttributes }) {
        const image: Image = attributes.image;
        const className = image && image.src ? "image-heading" : "";
        return (
            <div className={`${className} ${attributes.iconPosition}`}>
                <MediaUploader
                    image={attributes.image}
                    iconSize={attributes.iconSize}
                    setAttributes={setAttributes} />

                {image && image.src ?
                    <>
                        <Settings
                            iconSize={attributes.iconSize}
                            headingTag={attributes.headingTag}
                            iconPosition={attributes.iconPosition}
                            setAttributes={setAttributes} />
                        <RichText
                            tagName={attributes.headingTag}
                            className="heading"
                            value={attributes.heading}
                            allowedFormats={[]}
                            onChange={(heading) => setAttributes({ heading })}
                            placeholder={__("Heading", "cds-snc")}
                        />
                    </>
                    : null}
            </div>
        );
    },

    save({ attributes }: { attributes: BlockAttributes }) {
        const image = attributes.image;
        const imageWidth = attributes.iconSize || 10;
        const iconPosition = attributes.iconPosition;
        return (
            <div className={`image-heading ${iconPosition}`}>
                <span className="image">{image && <img src={image.src} width={imageWidth}></img>}</span>
                <Heading as={attributes.headingTag}>{attributes.heading}</Heading>
            </div>
        );
    },
});
