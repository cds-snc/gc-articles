function createToc() {

  if (!document.querySelector(".toc")) {
    return;
  }

  function wrap(el, wrapper, classList) {
    el.parentNode.insertBefore(wrapper, el);
    wrapper.appendChild(el);
    wrapper.classList.add(...classList)
  }

  try {
    const ulStyles = ["lst-spcd", "col-md-12"];
    document.querySelectorAll('.toc').forEach(x => x.classList.add(...ulStyles));

    const liStyles = ["col-md-4", "col-sm-6"];
    document.querySelectorAll('.toc li').forEach(x => x.classList.add(...liStyles));

    const aStyles = ["list-group-item"];
    document.querySelectorAll('.toc li a').forEach(x => x.classList.add(...aStyles));

    wrap(document.querySelector('.toc'), document.createElement('div'), ["row", "toc-row-wrapper"]);
    wrap(document.querySelector('.toc-row-wrapper'), document.createElement('div'), ["gc-stp-stp"]);
  } catch (e) {
    // no-op
  }
}

(function ($) {

  const $menuToggle = $('button.navbar-toggler');
  //const $itemWithSubmenu = $('.menu-item-has-children');
  const $submenuButton = $('.menu-item-has-children button');

  $menuToggle.on('click', function (e) {
    // toggle "aria-expanded"
    const expanded = e.target.getAttribute('aria-expanded') === 'false' ? true : false;
    $menuToggle.attr("aria-expanded", expanded);

    // set the submenu to "expanded" if the mobile nav is opened
    if (expanded === true) {
      $itemWithSubmenu.attr('aria-expanded', true);
    }

    // toggle menu class
    $menu = $(e.target.getAttribute('data-target'))
    $menu.toggleClass('show');
  })

  $submenuButton.on('click', function (e) {
    $itemWithSubmenu = $(e.target).parent();
    isOpen = $itemWithSubmenu.find('ul.sub-menu').hasClass('open');

    if(isOpen) {
      $itemWithSubmenu.find('ul.sub-menu').removeClass('open');
      $itemWithSubmenu.find('> a').attr("aria-expanded", 'false');
      $itemWithSubmenu.find('> button').attr("aria-expanded", 'false');
      $itemWithSubmenu.find('> button span').text('Open');
    } else {
      $itemWithSubmenu.find('ul.sub-menu').addClass('open');
      $itemWithSubmenu.find('> a').attr("aria-expanded", 'true');
      $itemWithSubmenu.find('> button').attr("aria-expanded", 'true');
      $itemWithSubmenu.find('> button span').text('Close');
    }
  })


  /*
  $itemWithSubmenu.on(
    'focusin mouseover', function (e) {
      // if target is within submenu, "aria-expanded" is true
      if ($.contains($itemWithSubmenu[0], e.target)) {
        $itemWithSubmenu.attr('aria-expanded', true);
      }
    }).on('focusout mouseout', function (e) {
      // if the focused element is outside of submenu, "aria-expanded" is false
      if (!$.contains($itemWithSubmenu[0], document.activeElement)) {
        $itemWithSubmenu.attr('aria-expanded', false);
      }
    })
  */

  createToc();

})(jQuery);
