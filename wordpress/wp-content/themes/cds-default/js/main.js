(function($) {

  const search_form_value = $("#site-search").attr("action");

  $('#wb-srch-q').keyup(function() {
    $("#site-search").attr("action", search_form_value+ "?q=" + $(this).val());
  });

  const $menuToggle = $('button.navbar-toggler');
  $menuToggle.on('click', function(e) {
    // toggle "aria-expanded"
    const expanded = e.target.getAttribute('aria-expanded') === 'false' ? false : true;
    $menuToggle.attr("aria-expanded", !expanded);

    // toggle menu class
    $menu = $(e.target.getAttribute('data-target'))
    $menu.toggleClass('show');
  })

})(jQuery);
