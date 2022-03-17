(function ($) {
    $requestForm = $('#cds-form-step-1');
    var previewVisible = false;

    if($requestForm.length) {

        $requestForm.find('input#site').on('keyup', function () {
            var inputValue = $(this).val()
                .toLowerCase()
                .trim()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // replace accented letters with latin characters
                .replace(/ +/g,'-') // replace whitespace with hyphens
                .replace(/[^a-z0-9-]/g,'') // replace anything that's not a letter, number, or a hyphen

            // if there is an input value at all
            if(inputValue.length) {
                if(!previewVisible) {
                    // show the "Your preview URL" string
                    $requestForm.find('.url-typer--empty').addClass('displayNone')
                    $requestForm.find('.url-typer--message').removeClass('displayNone');
                    previewVisible = true;
                }

                // update preview text with cleaned input text
                $('#url-typer__preview').text(inputValue);
            } else {
                if(previewVisible) {
                    // show the "enter your title to get a preview URL" string
                    $requestForm.find('.url-typer--empty').removeClass('displayNone')
                    $requestForm.find('.url-typer--message').addClass('displayNone');
                    previewVisible = false;
                }
            }
        });

    }

})(jQuery);
