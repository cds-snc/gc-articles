(function($) {

  const $menuToggle = $('button.navbar-toggler');
  const $itemWithSubmenu = $('.menu-item-has-children');

  $menuToggle.on('click', function(e) {
    // toggle "aria-expanded"
    const expanded = e.target.getAttribute('aria-expanded') === 'false' ? true : false;
    $menuToggle.attr("aria-expanded", expanded);

    // set the submenu to "expanded" if the mobile nav is opened
    if(expanded === true) {
      $itemWithSubmenu.attr('aria-expanded', true);
    }

    // toggle menu class
    $menu = $(e.target.getAttribute('data-target'))
    $menu.toggleClass('show');
  })

  $itemWithSubmenu.on(
    'focusin mouseover', function(e) {
      // if target is within submenu, "aria-expanded" is true
      if ($.contains($itemWithSubmenu[0], e.target)) {
        $itemWithSubmenu.attr('aria-expanded', true);
      }
    }).on('focusout mouseout', function(e) {
      // if the focused element is outside of submenu, "aria-expanded" is false
      if (!$.contains($itemWithSubmenu[0], document.activeElement)) {
        $itemWithSubmenu.attr('aria-expanded', false);
      }
    })

})(jQuery);
