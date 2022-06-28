const { __ } = wp.i18n;

alert(__('js-alert', 'test'));

console.log(__('js-log', 'test'));

div = document.getElementById('h1');
div.innerHTML += __('js-html', 'test');