const { __ } = wp.i18n;

alert(__('js-alert', 'cds-js-test'));

console.log(__('js-log', 'cds-js-test'));

div = document.getElementById('h1');
div.innerHTML += __('js-html', 'cds-js-test');

(() => {
    "use strict";
    const e = function () {
        return wp.element.createElement("div", null, __("REACT APP wp element", "cds-js-test"), " - ", __("js-html-react", "cds-js-test"), "!!", __("more", "cds-js-test"))
    }; (0, wp.element.render)(wp.element.createElement(e, null), document.getElementById("app1"))
})();