const { dispatch } = wp.data;

/* Remove the Yoast gutenberg panel */
dispatch('core/edit-post').removeEditorPanel('yoast-seo/document-panel'); // Remove the Yoast Panel

