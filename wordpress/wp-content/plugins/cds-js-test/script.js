const { __ } = wp.i18n;

alert(__('js-alert', 'cds-js-test'));

console.log(__('js-log', 'cds-js-test'));

div = document.getElementById('h1');
div.innerHTML += __('js-html', 'cds-js-test');