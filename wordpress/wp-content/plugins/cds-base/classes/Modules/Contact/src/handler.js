(function ($) {
    function toggleOptional($el) {
        $controlsElement = $('#' + $el.attr('aria-controls'));

        // only change attributes if "aria-controls" element exists
        if($controlsElement) {
            if($el.is(':checked')) {
                //show element
                $el.attr('aria-expanded', true);
                $controlsElement.attr('aria-hidden', false);
            } else {
                // hide element
                $el.attr('aria-expanded', false);
                $controlsElement.attr('aria-hidden', true);
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

    $("body").on("submit", "#contact-form", function (e) {
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
                if (data && data["error"]) {
                    var errorEl = '<p id="contact-error" data-testid="errorMessage" class="gc-alert gc-alert--error gc-alert--validation gc-error-message" role="alert">' + data.error_message + '</p>'
                    $(errorEl).insertAfter('#contact');
                    document.getElementById("contact-error").scrollIntoView();
                }

                if (data && data["success"]) {
                    $("#contact-form").replaceWith('<div class="panel-body"><div class="alert alert-success"><p>' + data.success + '</p></div></div>');
                }
            }
        });
    });
})(jQuery);

