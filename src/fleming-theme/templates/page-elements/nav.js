function showMenu() {
    $('.global-nav')
        .removeClass('mobile-hidden')
        .find('.mobile-toggle-menu')
        .attr('aria-label', 'hide menu')
        .text('Menu ▲');
}

function hideMenu() {
    var globalNav = $('.global-nav');
    globalNav
        .addClass('mobile-hidden')
        .find('.mobile-toggle-menu')
        .attr('aria-label', 'show menu')
        .text('Menu ▼');
    hideAllSubmenusBelow(globalNav);
}

function menuToggleButtonClicked() {
    $('.mobile-toggle-menu').blur();
    if ($('.global-nav').hasClass('mobile-hidden')) {
        showMenu();
    } else {
        hideMenu();
    }
}

function initMenuForMobile() {
    $('.global-nav').prepend($(
        '<div class="container">' +
        '<a class="mobile-toggle-menu" href="javascript:void(0);" onclick="Fleming.nav.menuToggleButtonClicked();"></a>' +
        '</div>'
    ));
    hideMenu();
}

function hideAllSubmenusBelow(rootMenu) {
    rootMenu
        .find('.mobile-submenu-showing')
        .each(function (i, menuItem) {
            hideSubmenu($(menuItem));
        });
}

function showSubmenu(menuItem) {
    menuItem
        .addClass('mobile-submenu-showing')
        .children('.mobile-toggle-submenu')
        .attr('aria-label', 'hide submenu')
        .text('-');
}

function hideSubmenu(menuItem) {
    menuItem
        .removeClass('mobile-submenu-showing')
        .children('.mobile-toggle-submenu')
        .attr('aria-label', 'show submenu')
        .text('+');
    hideAllSubmenusBelow(menuItem);
}

function submenuToggleButtonClicked(toggleButtonClicked) {
    var menuItem = $(toggleButtonClicked).parent();
    menuItem.find('.mobile-toggle-submenu').blur();
    if (menuItem.hasClass('mobile-submenu-showing')) {
        hideSubmenu(menuItem);
    } else {
        showSubmenu(menuItem);
    }
}

function initSubmenuForMobile() {
    $('.global-nav')
        .find('.mobile-submenu')
        .before(
            $('<a class="mobile-toggle-submenu" href="javascript:void(0);" onclick="Fleming.nav.submenuToggleButtonClicked(this);" aria-label="show submenu">+</a>')
        );
}

function showMenuMore() {
    $('.global-nav')
        .removeClass('tablet-more-hidden')
        .find('.tablet-toggle-more a')
        .attr('aria-label', 'hide more')
        .text('More ▲');
}

function hideMenuMore() {
    $('.global-nav')
        .addClass('tablet-more-hidden')
        .find('.tablet-toggle-more a')
        .attr('aria-label', 'show more')
        .text('More ▼');
}

function menuMoreToggleButtonClicked() {
    $('.tablet-toggle-more a').blur();
    if ($('.global-nav').hasClass('tablet-more-hidden')) {
        showMenuMore();
    } else {
        hideMenuMore();
    }
}

function initMenuForTablet() {
    var hideOnTablet = $('.global-nav')
        .find('ul')
        .first()
        .children('li')
        .slice(3);
    if (hideOnTablet.length > 0) {
        hideOnTablet
            .addClass('tablet-hide')
            .first()
            .before($('<li class="tablet-toggle-more"><a href="javascript:void(0);" onclick="Fleming.nav.menuMoreToggleButtonClicked();"></a></li>'));
        hideMenuMore();
    }
}

function showSubmenuMore() {
    $('.sub-global-nav')
        .removeClass('tablet-more-hidden')
        .find('.tablet-toggle-more a')
        .text('More ▲');
}

function hideSubmenuMore() {
    $('.sub-global-nav')
        .addClass('tablet-more-hidden')
        .find('.tablet-toggle-more a')
        .text('More ▼');
}

function submenuMoreToggleButtonClicked() {
    $('.tablet-toggle-more a').blur();
    if ($('.sub-global-nav').hasClass('tablet-more-hidden')) {
        showSubmenuMore();
    } else {
        hideSubmenuMore();
    }
}

function initSubmenuForTablet() {
    var hideOnTablet = $('.sub-global-nav')
        .find('ul')
        .first()
        .children('li')
        .slice(3);
    if (hideOnTablet.length > 0) {
        hideOnTablet
            .addClass('tablet-hide')
            .first()
            .before($('<li class="tablet-toggle-more"><a href="javascript:void(0);" onclick="Fleming.nav.submenuMoreToggleButtonClicked();"></a></li>'));
        hideSubmenuMore();
    }
}

function init() {
    initMenuForTablet();
    initSubmenuForTablet();
    initMenuForMobile();
    initSubmenuForMobile();
}

module.exports = {
    init,
    menuToggleButtonClicked,
    menuMoreToggleButtonClicked,
    submenuToggleButtonClicked,
    submenuMoreToggleButtonClicked,
};