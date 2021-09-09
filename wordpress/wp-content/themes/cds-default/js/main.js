(function($) {

  var search_form_value = $("#site-search").attr("action");

  $('#wb-srch-q').keyup(function() {
    $("#site-search").attr("action", search_form_value+ "?q=" + $(this).val());
  });
})(jQuery);