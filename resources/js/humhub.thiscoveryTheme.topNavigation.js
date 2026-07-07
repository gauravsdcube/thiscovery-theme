humhub.module('thiscoveryTheme.topNavigation', function (module, require, $) {
    module.initOnPjaxLoad = true;

    const event = require('event');
    const MOBILE_MENU_STYLE_HAMBURGER = 'hamburger';
    const MOBILE_MENU_STYLE_BOTTOM_BAR = 'bottom-bar';
    const MOBILE_MENU_STYLE_FLOATING_BAR = 'floating-bar';
    const EVENT_NS = '.thiscoveryThemeTopNavigation';

    let accountHomeParent = null;
    let accountHomeNext = null;
    let navHomeParent = null;
    let navHomeNext = null;
    let searchHomeParent = null;
    let searchHomeNext = null;
    let notificationsHomeParent = null;
    let notificationsHomeNext = null;
    let chooserHomeParent = null;
    let chooserHomeNext = null;

    const getBody = function () {
        return $('body');
    };

    const getTopMenuNav = function () {
        return $('#top-menu-nav');
    };

    const getTopMenuSub = function () {
        return $('#top-menu-sub');
    };

    const getTopMenuDropdown = function () {
        return $('#top-menu-sub-dropdown');
    };

    const getHamburger = function () {
        return $('#top-menu-hamburger');
    };

    const getMobilePanel = function () {
        return $('#top-menu-mobile-panel');
    };

    const getFloatingBar = function () {
        return $('#top-menu-floating-bar');
    };

    const getAccountSlot = function () {
        return $('#top-menu-mobile-account-slot');
    };

    const getAccountActions = function () {
        return $('#topbar .topbar-actions');
    };

    const getSearchMenuNav = function () {
        return $('#search-menu-nav');
    };

    const getTopbarContainer = function () {
        return $('#topbar > .container');
    };

    const getNotifications = function () {
        return $('#topbar .notifications').first();
    };

    const getSpaceChooser = function () {
        const $chooser = $('#space-menu').closest('li.nav-item.dropdown');
        return $chooser.length ? $chooser : $();
    };

    const resetStoredHomes = function () {
        accountHomeParent = null;
        accountHomeNext = null;
        navHomeParent = null;
        navHomeNext = null;
        searchHomeParent = null;
        searchHomeNext = null;
        notificationsHomeParent = null;
        notificationsHomeNext = null;
        chooserHomeParent = null;
        chooserHomeNext = null;
    };

    const storeAccountHome = function () {
        const $accountActions = getAccountActions();
        if (accountHomeParent || !$accountActions.length) {
            return;
        }

        accountHomeParent = $accountActions.parent();
        accountHomeNext = $accountActions.next();
    };

    const storeNavHome = function () {
        const $nav = getTopMenuNav();
        if (navHomeParent || !$nav.length) {
            return;
        }

        navHomeParent = $nav.parent();
        navHomeNext = $nav.next();
    };

    const storeSearchHome = function () {
        const $search = getSearchMenuNav();
        if (searchHomeParent || !$search.length) {
            return;
        }

        searchHomeParent = $search.parent();
        searchHomeNext = $search.next();
    };

    const storeNotificationsHome = function () {
        const $notifications = getNotifications();
        if (notificationsHomeParent || !$notifications.length) {
            return;
        }

        notificationsHomeParent = $notifications.parent();
        notificationsHomeNext = $notifications.next();
    };

    const storeChooserHome = function () {
        const $chooser = getSpaceChooser();
        if (chooserHomeParent || !$chooser.length) {
            return;
        }

        chooserHomeParent = $chooser.parent();
        chooserHomeNext = $chooser.next();
    };

    const restoreAccountMenu = function () {
        const $accountActions = getAccountActions();
        if (!$accountActions.length || !accountHomeParent) {
            return;
        }

        if (accountHomeNext && accountHomeNext.length) {
            $accountActions.insertBefore(accountHomeNext);
            return;
        }

        $accountActions.appendTo(accountHomeParent);
    };

    const restoreSearchMenu = function () {
        const $search = getSearchMenuNav();
        if (!$search.length || !searchHomeParent) {
            return;
        }

        if (searchHomeNext && searchHomeNext.length) {
            $search.insertBefore(searchHomeNext);
            return;
        }

        $search.appendTo(searchHomeParent);
    };

    const restoreNotifications = function () {
        const $notifications = getNotifications();
        if (!$notifications.length || !notificationsHomeParent) {
            return;
        }

        if (notificationsHomeNext && notificationsHomeNext.length) {
            $notifications.insertBefore(notificationsHomeNext);
            return;
        }

        $notifications.appendTo(notificationsHomeParent);
    };

    const restoreSpaceChooser = function () {
        const $chooser = getSpaceChooser();
        const $nav = getTopMenuNav();

        if (!$chooser.length || !chooserHomeParent) {
            return;
        }

        if ($chooser.closest($nav).length) {
            return;
        }

        if (chooserHomeNext && chooserHomeNext.length) {
            $chooser.insertBefore(chooserHomeNext);
            return;
        }

        $nav.prepend($chooser);
    };

    const restoreMainNav = function () {
        const $nav = getTopMenuNav();
        if (!$nav.length || !navHomeParent) {
            return;
        }

        if (navHomeNext && navHomeNext.length) {
            $nav.insertBefore(navHomeNext);
            return;
        }

        $nav.appendTo(navHomeParent);
    };

    const isHamburgerMode = function () {
        return module.config.mobileMenuStyle === MOBILE_MENU_STYLE_HAMBURGER
            || getBody().hasClass('hh-yg-mobile-menu-hamburger');
    };

    const isBottomBarMode = function () {
        return module.config.mobileMenuStyle === MOBILE_MENU_STYLE_BOTTOM_BAR
            || getBody().hasClass('hh-yg-mobile-menu-bottom-bar');
    };

    const isFloatingBarMode = function () {
        return module.config.mobileMenuStyle === MOBILE_MENU_STYLE_FLOATING_BAR
            || getBody().hasClass('hh-yg-mobile-menu-floating-bar');
    };

    const isHamburgerViewport = function () {
        return isHamburgerMode() && window.matchMedia('(max-width: 767.98px)').matches;
    };

    const isFloatingViewport = function () {
        return isFloatingBarMode() && window.matchMedia('(max-width: 767.98px)').matches;
    };

    const isBottomBarViewport = function () {
        return isBottomBarMode() && window.matchMedia('(max-width: 575.98px)').matches;
    };

    const isMobilePanelViewport = function () {
        return isHamburgerViewport() || isFloatingViewport();
    };

    const closeMobileMenu = function () {
        const $body = getBody();
        const $mobilePanel = getMobilePanel();
        const $hamburger = getHamburger();

        $body.removeClass('mobile-menu-open');
        $mobilePanel.removeClass('is-open');
        $hamburger.attr('aria-expanded', 'false');
        $hamburger.find('.fa').addClass('fa-bars').removeClass('fa-times');
    };

    const openMobileMenu = function () {
        const $body = getBody();
        const $mobilePanel = getMobilePanel();
        const $hamburger = getHamburger();

        $body.addClass('mobile-menu-open');
        $mobilePanel.addClass('is-open');
        $hamburger.attr('aria-expanded', 'true');
        $hamburger.find('.fa').removeClass('fa-bars').addClass('fa-times');
    };

    const toggleMobileMenu = function () {
        if (getBody().hasClass('mobile-menu-open')) {
            closeMobileMenu();
            return;
        }

        openMobileMenu();
    };

    const relocateMainNav = function () {
        const $nav = getTopMenuNav();
        const $floatingBar = getFloatingBar();
        const $mobilePanel = getMobilePanel();

        storeNavHome();

        if (!$nav.length) {
            return;
        }

        if (isHamburgerViewport()) {
            if (!$nav.closest($mobilePanel).length) {
                $nav.prependTo($mobilePanel);
            }
            return;
        }

        if (!$nav.closest($floatingBar).length) {
            $nav.appendTo($floatingBar);
        }
    };

    const ensureTopbarUtilities = function () {
        const $container = getTopbarContainer();
        const $hamburger = getHamburger();
        const $search = getSearchMenuNav();
        const $notifications = getNotifications();

        if (!$container.length) {
            return;
        }

        if (!isFloatingViewport() && !isHamburgerViewport()) {
            return;
        }

        const $anchor = $hamburger.length ? $hamburger : $container.children('.topbar-actions').first();

        if (!$anchor.length) {
            return;
        }

        if ($search.length && !$search.parent().is($container)) {
            $search.detach().insertBefore($anchor);
        }

        if ($notifications.length && !$notifications.parent().is($container)) {
            $notifications.detach().insertBefore($anchor);
        }
    };

    const relocateNavigationLayout = function () {
        relocateMainNav();
        storeChooserHome();
        restoreSpaceChooser();
        ensureTopbarUtilities();
        relocateAccountMenu();
    };

    const relocateAccountMenu = function () {
        const $body = getBody();
        const $accountActions = getAccountActions();
        const $accountSlot = getAccountSlot();

        storeAccountHome();

        if (!$accountActions.length || $body.hasClass('hh-yg-is-guest')) {
            return;
        }

        if (isMobilePanelViewport()) {
            if (!$accountActions.closest($accountSlot).length) {
                $accountActions.appendTo($accountSlot);
            }
            return;
        }

        if ($accountActions.closest('#topbar > .container').length && !$accountActions.closest($accountSlot).length) {
            return;
        }

        restoreAccountMenu();
    };

    const unbindEvents = function () {
        $(document).off(EVENT_NS);
        $(window).off(EVENT_NS);
    };

    const bindEvents = function () {
        unbindEvents();

        $(document).on('click' + EVENT_NS, '#top-menu-hamburger', function (event) {
            event.preventDefault();
            event.stopPropagation();

            if (!isMobilePanelViewport()) {
                return;
            }

            toggleMobileMenu();
        });

        $(document).on('click' + EVENT_NS, function (event) {
            if (!getBody().hasClass('mobile-menu-open')) {
                return;
            }

            if ($(event.target).closest('#top-menu-mobile-panel, #top-menu-hamburger').length) {
                return;
            }

            closeMobileMenu();
        });

        $(document).on('click' + EVENT_NS, '#top-menu-mobile-panel a', function (event) {
            if (!isMobilePanelViewport() || $(this).hasClass('dropdown-toggle')) {
                return;
            }

            if (isFloatingViewport() && $(this).closest('#top-menu-nav').length) {
                return;
            }

            closeMobileMenu();
        });

        $(window).on('resize' + EVENT_NS, function () {
            clearTimeout(module.resizeTimeout);
            module.resizeTimeout = setTimeout(onResize, 250);
        });
    };

    const onResize = function () {
        relocateNavigationLayout();

        if (!isMobilePanelViewport()) {
            closeMobileMenu();
        }

        if (isHamburgerViewport() || isFloatingViewport()) {
            getTopMenuSub().hide();
            return;
        }

        fixNavigationOverflow();
        updateDropdownDirection();
    };

    const init = function () {
        resetStoredHomes();
        closeMobileMenu();
        bindEvents();
        relocateNavigationLayout();
        setTimeout(onResize, 50);
    };

    event.on('humhub:ready', function () {
        window.setTimeout(init, 0);
    });

    const fixNavigationOverflow = function () {
        const $topMenuNav = getTopMenuNav();
        const $topMenuSub = getTopMenuSub();
        const $topMenuDropdown = getTopMenuDropdown();

        if (!$topMenuNav.length) {
            return;
        }

        if ((isHamburgerMode() || isFloatingBarMode()) && !window.matchMedia('(max-width: 767.98px)').matches) {
            $topMenuSub.hide();
            $topMenuNav.addClass('overflow-visible flex-nowrap');
            return;
        }

        $topMenuNav.removeClass('overflow-visible flex-nowrap');

        while (!isOverflow()) {
            if (!moveFromDropDown('.top-menu-item:first', $topMenuNav, $topMenuDropdown)) {
                break;
            }
        }

        if (isOverflow()) {
            $topMenuSub.show();
        } else {
            $topMenuSub.hide();
        }

        while (isOverflow()) {
            if (!moveToDropDown('.top-menu-item:last', $topMenuNav, $topMenuDropdown)) {
                break;
            }
        }

        $topMenuSub.appendTo($topMenuNav);
        updateDropdownDirection();
        $topMenuNav.addClass('overflow-visible flex-nowrap');

        if ($topMenuSub.is(':visible')) {
            $topMenuSub.find('.dropdown-toggle').dropdown();
        }
    };

    const moveToDropDown = function (itemClass, from, $topMenuDropdown) {
        const $item = from.children(itemClass);
        if (!$item.length) {
            return false;
        }

        $item.find('br').remove();
        $topMenuDropdown.prepend($item);
        return true;
    };

    const moveFromDropDown = function (itemClass, to, $topMenuDropdown) {
        const $item = $topMenuDropdown.children(itemClass);
        if (!$item.length) {
            return false;
        }

        const $icon = $item.find('a:first > i:first');
        if ($icon.length) {
            $icon.after('<br/>');
        }

        to.append($item);
        return true;
    };

    const isOverflow = function () {
        const $topMenuNav = getTopMenuNav();
        const $lastVisibleChild = $topMenuNav.children(':visible:last');
        if (!$lastVisibleChild.length) {
            return false;
        }

        return Math.round($lastVisibleChild.position().top) > 0;
    };

    const updateDropdownDirection = function () {
        const $topMenuNav = getTopMenuNav();

        if (isBottomBarViewport()) {
            $topMenuNav.children('.dropdown').removeClass('dropdown').addClass('dropup');
            return;
        }

        $topMenuNav.children('.dropup').removeClass('dropup').addClass('dropdown');
    };

    module.unload = function () {
        unbindEvents();
        closeMobileMenu();
    };

    module.export({
        init: init,
        sortOrder: 100,
    });
});
