const { select, dispatch } = wp.data;

/* Remove the Yoast gutenberg panel */
dispatch('core/edit-post').removeEditorPanel('yoast-seo/document-panel'); // Remove the Yoast Panel

/* Set the Yoast Metabox to "closed" */
const isYoastPanelOpen = select("core/edit-post").isEditorPanelOpened('meta-box-wpseo_meta');
if (isYoastPanelOpen) {
  // sets the "opened" attribute to false, but doesn't actually close the SEO panel
  dispatch('core/edit-post').toggleEditorPanelOpened('meta-box-wpseo_meta');
}

/* Actually hide the Yoast metabox */

// Wait for element to appear and then click it: https://stackoverflow.com/a/29754070
const buttonSelector = "#wpseo_meta button.handlediv[aria-expanded='true']";
waitForElementToDisplay(buttonSelector,
  function() {
    document.querySelector(buttonSelector).click();
  },
  1000, // check once per second
  4001  // stop after 4 seconds
);

function waitForElementToDisplay(selector, callback, checkFrequencyInMs, timeoutInMs) {
  var startTimeInMs = Date.now();
  (function loopSearch() {
    if (document.querySelector(selector) != null) {
      callback();
      return;
    }
    else {
      setTimeout(function () {
        if (timeoutInMs && Date.now() - startTimeInMs > timeoutInMs)
          return;
        loopSearch();
      }, checkFrequencyInMs);
    }
  })();
}
