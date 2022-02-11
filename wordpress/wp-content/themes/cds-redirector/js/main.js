console.log('redirector!');
console.log(OBJECT.url);

jQuery(function () {
    const checkPreviewInterval = setTimeout(checkPreview, 1000);

    function checkPreview() {
      const previewButton = jQuery(".block-editor-post-preview__button-toggle").first();
      if (previewButton.length) {
        // remove event handlers
        previewButton.off();

        // add our own event handler to open preview link
        previewButton.on("click", function() {
            window.open(OBJECT.url, "_blank");
        });
      }
   }
});