(function ($) {
    $("body").on("submit", "#subscribe-form", function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url = form.attr('action');

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
                    alert(parsedData["error"]);
                }

                if (parsedData && parsedData["success"]) {
                    $("#subscribe-form").replaceWith('<div>' + parsedData.success + '</div>')
                }
            }
        });
    });
})(jQuery);

//requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

