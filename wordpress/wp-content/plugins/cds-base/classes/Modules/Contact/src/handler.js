(function ($) {
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

