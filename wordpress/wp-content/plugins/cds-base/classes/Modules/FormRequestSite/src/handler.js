(function ($) {
    $("body").on("submit", "#request-form", function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        // clear previous error messages
        $(".gc-error-message").remove();

        $.ajax({
            type: "POST",
            headers: {
                'X-WP-Nonce': CDS_VARS.rest_nonce
            },
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function (data) {
                console.log(data);
                if (data && data["error"]) {
                    var errorEl = '<div id="request-error" class="gc-alert gc-alert--error gc-alert--validation" data-testid="alert" tabindex="0" role="alert">';
                    errorEl += '<div class="gc-alert__body">';
                    errorEl += '<h2 class="gc-h3">' + data.error_message + '</h2>';
                    if(data['keys']) {
                        errorEl += '<ol class="gc-ordered-list">';
                        data['keys'].forEach(key => {
                            errorEl += '<li><a href="#' + key + '" class="gc-error-link">Please complete the required field: ' + key +'</li>'

                            var $validationMsg = '<p data-testid="errorMessage" class="gc-error-message" role="alert">Please complete the required field to continue</p>'
                            $($validationMsg).insertBefore('#' + key);
                        });
                        errorEl += '</ol>';
                    }
                    errorEl += '</div>';

                    $(errorEl).insertAfter('#request');
                    document.getElementById("request-error").scrollIntoView();
                }

                if (data && data["success"]) {
                    $("#request-form").replaceWith('<div class="panel-body"><div class="alert alert-success"><p>' + data.success + '</p></div></div>');
                }
            }
        });
    });
})(jQuery);
