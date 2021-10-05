(function ($) {
    $("body").on("submit", "#subscribe-form", function (e) {
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
                var parsedData = JSON.parse(data);

                if (parsedData && parsedData["error"]) {
                    var errorEl = '<p data-testid="errorMessage" class="gc-error-message" role="alert">' + parsedData["error"] + '</p>'
                    $(errorEl).insertAfter('#cds-email');
                }

                if (parsedData && parsedData["success"]) {
                    $("#subscribe-form").replaceWith('<div class="panel-body"><div class="alert alert-success"><p>' + parsedData.success + '</p></div></div>')
                }
            }
        });
    });
})(jQuery);

