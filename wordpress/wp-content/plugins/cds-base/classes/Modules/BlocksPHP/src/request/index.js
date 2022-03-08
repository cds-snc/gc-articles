import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
registerBlockType('cds/request', {
	edit: Edit,
	save: ({ attributes, className }) => null,
});
