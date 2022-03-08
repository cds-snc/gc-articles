import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { apiVersion, category, icon, name } from '../block.json';
import Edit from './block-edit';

registerBlockType(name, {
	apiVersion,
	title: __('Request a site', 'cds-snc'),
	description: __('GC Articles team request a site form', 'cds-snc'),
	category,
	icon,
	keywords: [],
	edit: Edit,
	save: ({ attributes, className }) => null,
});
