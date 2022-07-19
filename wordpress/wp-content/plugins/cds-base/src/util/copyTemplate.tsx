import { __ } from "@wordpress/i18n";

function copyToClipboard() {
    let copyText = "((message))\n\nYou may unsubscribe by clicking this link:";
    copyText+= "\n\n((unsubscribe_link))";
    copyText+= "\n\nYou may unsubscribe in french:";
    copyText+= "\n\n((unsubscribe_link))";

    try {
        navigator.clipboard.writeText(copyText).then(() => {
            alert(__("Copied to clipboard", 'cds-snc'));
        });
    } catch (err) {
        console.log(err);
    }
}

window.addEventListener('DOMContentLoaded', (event) => {
    event.preventDefault();
    event.stopImmediatePropagation();
    const el = document.getElementById("copy-template");
    if (!el) return;
    el.addEventListener("click", function () {
        copyToClipboard();
    });
});