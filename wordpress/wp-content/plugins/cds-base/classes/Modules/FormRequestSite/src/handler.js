(function ($) {
    function toggleOptional($el) {
        $controlsElement = $('#' + $el.attr('aria-controls'));

        // only change attributes if "aria-controls" element exists
        if($controlsElement) {
            if($el.is(':checked')) {
                //show element
                $el.attr('aria-expanded', true);
                $controlsElement.removeClass('displayNone');
            } else {
                // hide element
                $el.attr('aria-expanded', false);
                $controlsElement.addClass('displayNone').find('input').val('');
            }
        }
    }

    $('input[aria-controls]').on("click", function (e) {
        // add click handler
        toggleOptional($(e.target));
    }).each(function() {
        // run it once on page load
        toggleOptional($(this));
    });

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
                            errorEl += '<li><a href="#' + key + '" class="gc-error-link">' + key +'</li>'

                            var $validationMsg = '<p data-testid="errorMessage" class="gc-error-message" role="alert">' + data.error_message + '</p>'
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
