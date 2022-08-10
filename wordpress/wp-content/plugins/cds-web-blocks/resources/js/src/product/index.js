import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

// http://localhost/cds/wp-json/wp/v2/product?_embed

registerBlockType('cds/product', {
	edit: Edit,
	save: ({ attributes, className }) => {
		return null
	},
});