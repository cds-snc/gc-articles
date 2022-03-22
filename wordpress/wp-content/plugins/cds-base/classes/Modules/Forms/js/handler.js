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

    $("body").on("submit", "#cds-form", function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        var $button = $form.find('button#submit');
        // disable button so that it can't be submitted twice
        $button.prop('disabled', true);

        // clear previous error messages
        $(".gc-error-message, .gc-alert--error").remove();

        $.ajax({
            type: "POST",
            headers: {
                'X-WP-Nonce': CDS_VARS.rest_nonce
            },
            url: url,
            data: $form.serialize(), // serializes the form's elements.
            success: function (data) {
                // handle redirect if provided
                if(data.redirect) {
                    window.location.href = data.redirect
                    return;
                }

                // re-enable button
                $button.prop('disabled', false);

                if (data && data["error"]) {
                    var errorEl = '<div id="cds-form-error" class="gc-alert gc-alert--error gc-alert--validation" data-testid="alert" tabindex="0" role="alert">';
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

                    $(errorEl).insertAfter('#cds-form-nonce');
                    document.getElementById("cds-form-error").scrollIntoView();
                }

                if (data && data["success"]) {
                    $("#cds-form").replaceWith('<div class="panel-body"><div class="alert alert-success"><p>' + data.success + '</p></div></div>');
                }
            }
        });
    });
})(jQuery);
