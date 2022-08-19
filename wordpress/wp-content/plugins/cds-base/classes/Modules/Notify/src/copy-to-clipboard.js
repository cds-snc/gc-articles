window.addEventListener('DOMContentLoaded', function(){
    const { alertText } = CDS_VARS;

    function copyToClipboard({ copyText, eventId }) {
        try {
            navigator.clipboard.writeText(copyText).then(() => {
                alert(alertText + ': ' + eventId);
            });
        } catch (err) {
            console.log(err);
        }
    }

    document.addEventListener('click', function (event) {
        // If the clicked element doesn't have the right selector or no templateText, bail
        if (!event.target.matches('.button--copy-to-clipboard') || !CDS_VARS[event.target.id]) return;
    
        // Cancel the default button action
        event.preventDefault();
        copyToClipboard({
            copyText: CDS_VARS[event.target.id],
            eventId: event.target.id
        });
    }, false);
});
