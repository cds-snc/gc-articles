( function ( wp, definitions ) {
	'use strict';

	const el = wp.element.createElement;
	const { Fragment } = wp.element;
	const { __ } = wp.i18n;
	const { InnerBlocks, InspectorControls, RichText, useBlockProps } =
		wp.blockEditor;
	const {
		Notice,
		PanelBody,
		SelectControl,
		TextControl,
		TextareaControl,
		ToggleControl,
	} = wp.components;
	const ServerSideRender = wp.serverSideRender.default || wp.serverSideRender;

	function LegacyBlockEdit( props ) {
		const blockProps = useBlockProps();
		const alert = props.name === 'cds-snc/alert';
		return el(
			Fragment,
			null,
			alert
				? el(
						InspectorControls,
						null,
						el(
							PanelBody,
							{ title: __( 'Settings', 'cds-snc' ) },
							el( SelectControl, {
								label: __( 'Alert Type', 'cds-snc' ),
								value: props.attributes.alertType,
								options: [
									'info',
									'success',
									'warning',
									'danger',
								].map( ( role ) => ( {
									label: role,
									value: `alert-${ role }`,
								} ) ),
								onChange: ( alertType ) =>
									props.setAttributes( { alertType } ),
							} )
						)
				  )
				: null,
			el(
				'div',
				blockProps,
				el( RichText, {
					tagName: 'h2',
					value: props.attributes.title,
					allowedFormats: [],
					placeholder: __( 'Title', 'cds-snc' ),
					onChange: ( title ) => props.setAttributes( { title } ),
				} ),
				props.name === 'cds-snc/accordion'
					? el( InnerBlocks )
					: el( RichText, {
							tagName: 'div',
							value: props.attributes.content,
							onChange: ( content ) =>
								props.setAttributes( { content } ),
							placeholder: __( 'Summary', 'cds-snc' ),
					  } )
			)
		);
	}

	wp.hooks.addFilter(
		'editor.BlockEdit',
		'cds-gcds/legacy-blocks',
		( BlockEdit ) => ( props ) =>
			el(
				[
					'cds-snc/accordion',
					'cds-snc/expander',
					'cds-snc/alert',
				].includes( props.name )
					? LegacyBlockEdit
					: BlockEdit,
				props
			)
	);

	Object.entries( definitions ).forEach( ( [ name, definition ] ) => {
		const attributes = {
			content: { type: 'string', default: '' },
			anchor: { type: 'string', default: '' },
		};
		Object.entries( definition.fields ).forEach( ( [ key, field ] ) => {
			attributes[ key ] = { type: field.type, default: field.default };
			if ( field.enum ) {
				attributes[ key ].enum = field.enum;
			}
		} );

		wp.blocks.registerBlockType( `cds-gcds/${ name }`, {
			apiVersion: 3,
			title: definition.title,
			icon: 'layout',
			category: 'cds-gcds',
			attributes,
			parent: definition.parent,
			supports: { html: false, anchor: true, customClassName: false },
			edit: function Edit( props ) {
				const blockProps = useBlockProps();
				const errors = [];
				const controls = Object.entries( definition.fields ).map(
					( [ key, field ] ) => {
						const value = props.attributes[ key ];
						const label = field.label;
						if ( field.required && ! value ) {
							errors.push(
								`${ label }: ${ __(
									'This field is required.',
									'cds-snc'
								) }`
							);
						}
						if ( field.json ) {
							try {
								if ( ! Array.isArray( JSON.parse( value ) ) ) {
									errors.push(
										`${ label }: ${ __(
											'Enter a JSON array.',
											'cds-snc'
										) }`
									);
								}
							} catch {
								errors.push(
									`${ label }: ${ __(
										'Enter valid JSON.',
										'cds-snc'
									) }`
								);
							}
						}
						const common = {
							key,
							label,
							onChange: ( next ) =>
								props.setAttributes( { [ key ]: next } ),
						};
						if ( field.type === 'boolean' ) {
							return el( ToggleControl, {
								...common,
								checked: value,
							} );
						}
						if ( field.enum ) {
							return el( SelectControl, {
								...common,
								value,
								options: field.enum.map( ( option ) => ( {
									label: option,
									value: option,
								} ) ),
							} );
						}
						return el( field.json ? TextareaControl : TextControl, {
							...common,
							value,
						} );
					}
				);
				return el(
					Fragment,
					null,
					el(
						InspectorControls,
						null,
						el(
							PanelBody,
							{ title: __( 'Settings', 'cds-snc' ) },
							controls
						)
					),
					el(
						'div',
						blockProps,
						el( 'strong', null, definition.title ),
						...errors.map( ( message ) =>
							el(
								Notice,
								{
									key: message,
									status: 'warning',
									isDismissible: false,
								},
								message
							)
						),
						definition.content
							? el( RichText, {
									tagName:
										name === 'heading'
											? props.attributes.tag
											: 'p',
									value: props.attributes.content,
									allowedFormats:
										name === 'button'
											? []
											: [
													'core/bold',
													'core/italic',
													...( [
														'link',
														'nav-link',
														'card',
													].includes( name )
														? []
														: [ 'core/link' ] ),
											  ],
									onChange: ( content ) =>
										props.setAttributes( { content } ),
									placeholder: __(
										'Enter content',
										'cds-snc'
									),
							  } )
							: null,
						definition.inner
							? el( InnerBlocks, {
									allowedBlocks: definition.allowed,
									template: definition.template,
							  } )
							: null,
						! definition.inner &&
							! definition.parent &&
							errors.length === 0
							? el( ServerSideRender, {
									block: `cds-gcds/${ name }`,
									attributes: props.attributes,
							  } )
							: null
					)
				);
			},
			save() {
				return definition.inner ? el( InnerBlocks.Content ) : null;
			},
		} );
	} );
} )( window.wp, window.cdsGcdsBlocks );
