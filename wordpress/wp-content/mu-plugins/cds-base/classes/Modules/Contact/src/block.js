import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { apiVersion, category, icon, name } from '../block.json';
import Edit from './block-edit';

registerBlockType(name, {
	apiVersion,
	title: __('Contact', 'cds-snc'),
	description: __('Platform team contact form', 'cds-snc'),
	category,
	icon,
	keywords: [],
	edit: Edit,
	save: ({ attributes, className }) => null,
});
