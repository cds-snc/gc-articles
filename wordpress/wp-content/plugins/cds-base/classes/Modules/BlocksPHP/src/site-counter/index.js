import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
registerBlockType('cds/site-counter', {
	edit: Edit,
	save: ({ attributes, className }) => null,
});
