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
  const $submenuButton = $('.menu-item-has-children button');
  let closeMenuTimeout;

  // this is for the mobile nav
  $menuToggle.on('click', function (e) {
    // toggle "aria-expanded"
    const expanded = e.target.getAttribute('aria-expanded') === 'false' ? true : false;
    $menuToggle.attr("aria-expanded", expanded);

    // set the submenu to "expanded" if the mobile nav is opened
    if (expanded === true) {
      $('.menu-item-has-children').attr('aria-expanded', true);
    }

    // toggle menu class
    $menu = $(e.target.getAttribute('data-target'))
    $menu.toggleClass('show');
  })

  // open or close the menu
  $submenuButton.on('click mouseenter', function(e) {
    $itemWithSubmenu = $(e.target).parent();
    isOpen = $itemWithSubmenu.find('ul.sub-menu').hasClass('open');
    isMouseEnter = e.type === 'mouseenter'
    clearTimeout(closeMenuTimeout)

    if(isOpen) {
      if (e.type === 'click') {
        closeMenu($itemWithSubmenu)
      }
      
    } else {
      // close other submenus
      closeMenu($('li.menu-item-has-children').remove($itemWithSubmenu))
  
      openMenu($itemWithSubmenu, isMouseEnter)
    }
  })
  
  // set a timer when we mouseleave
  $('.sub-menu').on('mouseleave', function(e) {
    $itemWithSubmenu = $(e.target).parents('li.menu-item-has-children');
    isOpenMouseenter = $itemWithSubmenu.find('ul.sub-menu.open.mouseenter').length > 0

    if(isOpenMouseenter) {
      clearTimeout(closeMenuTimeout)
      closeMenuTimeout = setTimeout(() => closeMenu($itemWithSubmenu), 1000);
    }
  })

  function openMenu($itemWithSubmenu, isMouseEnter = false) {
    $itemWithSubmenu.find('ul.sub-menu').addClass('open');
    $itemWithSubmenu.find('> a').attr("aria-expanded", 'true');
    $itemWithSubmenu.find('> button').attr("aria-expanded", 'true');
    $itemWithSubmenu.find('> button span').text('Close submenu');

    if(isMouseEnter) {
      $itemWithSubmenu.find('ul.sub-menu').addClass('mouseenter');
    }
  }

  function closeMenu($itemWithSubmenu) {
    $itemWithSubmenu.find('ul.sub-menu').removeClass('open').removeClass('mouseenter');
    $itemWithSubmenu.find('> a').attr("aria-expanded", 'false');
    $itemWithSubmenu.find('> button').attr("aria-expanded", 'false');
    $itemWithSubmenu.find('> button span').text('Open submenu');
  }

  createToc();

})(jQuery);
