import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.scss';

// http://localhost/cds/wp-json/wp/v2/product?_embed

registerBlockType('cds/product', {
	edit: Edit,
	save: ({ attributes, className }) => {
		return null
	},
});