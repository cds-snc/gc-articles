import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
registerBlockType('cds/team', {
	edit: Edit,
	save: ({ attributes, className }) => null,
});


