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
                    var errorEl = '<p id="request-error" data-testid="errorMessage" class="gc-alert gc-alert--error gc-alert--validation gc-error-message" role="alert">' + data.error_message + '</p>'
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
