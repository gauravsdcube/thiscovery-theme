humhub.module('thiscoveryTheme.topNavigation', function (module, require, $) {
    module.initOnPjaxLoad = true;

    const event = require('event');
    const config = require('config');
    const MOBILE_MENU_STYLE_HAMBURGER = 'hamburger';
    const MOBILE_MENU_STYLE_FLOATING_BAR = 'floating-bar';
    const EVENT_NS = '.thiscoveryThemeTopNavigation';
    const ACCOUNT_PROFILE_ID = 'account-profile';
    const HOME_ID = 'home';
    const SPACE_CHOOSER_ID = 'space-chooser';
    const SITE_FOOTER_LEGALS_ID = 'site-footer-legals';
    const NAV_HIDDEN_CLASS = 'tc-mobile-nav-hidden';
    const ACCOUNT_HIDDEN_CLASS = 'tc-mobile-account-hidden';
    const LEGALS_NAV_ITEM_ID = 'site-footer-legals-nav-item';

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
    let floatingMenuLastScrollTop = 0;

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

    const getMobileNavSlot = function () {
        return $('#top-menu-mobile-nav-slot');
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

    const listIncludes = function (list, id) {
        if (!Array.isArray(list)) {
            return false;
        }

        const resolved = resolveMenuItemIdAlias(id);
        return list.some(function (listId) {
            return resolveMenuItemIdAlias(listId) === resolved;
        });
    };

    const resolveMenuItemIdAlias = function (id) {
        const aliases = module.config.menuItemIdAliases || {};
        return aliases[id] || id;
    };

    const getSortPosition = function (id, orderMap, originalIndex) {
        const resolved = resolveMenuItemIdAlias(id);

        if (orderMap[id]) {
            return orderMap[id];
        }
        if (orderMap[resolved]) {
            return orderMap[resolved];
        }

        return Object.keys(orderMap).reduce(function (position, key) {
            if (position !== null) {
                return position;
            }
            if (resolveMenuItemIdAlias(key) === resolved) {
                return orderMap[key];
            }
            return null;
        }, null) || (1000 + (originalIndex[id] ?? originalIndex[resolved] ?? 0));
    };

    const normalizeSortOrder = function (sortOrder) {
        if (!sortOrder || typeof sortOrder !== 'object') {
            return {};
        }

        const normalized = {};
        Object.keys(sortOrder).forEach(function (id) {
            const position = parseInt(sortOrder[id], 10);
            if (!Number.isNaN(position) && position > 0) {
                normalized[id] = position;
            }
        });

        return normalized;
    };

    const sortedIds = function (ids, sortOrder, forceHomeFirst) {
        const orderMap = normalizeSortOrder(sortOrder);
        const originalIndex = {};
        ids.forEach(function (id, index) {
            originalIndex[id] = index;
        });

        return ids.slice().sort(function (a, b) {
            if (forceHomeFirst === true) {
                if (a === HOME_ID && b !== HOME_ID) {
                    return -1;
                }
                if (b === HOME_ID && a !== HOME_ID) {
                    return 1;
                }
            }

            const aSort = getSortPosition(a, orderMap, originalIndex);
            const bSort = getSortPosition(b, orderMap, originalIndex);

            if (aSort === bSort) {
                return (originalIndex[a] || 0) - (originalIndex[b] || 0);
            }

            return aSort - bSort;
        });
    };

    const reorderContainerByIds = function ($container, ids) {
        if (!$container || !$container.length || !Array.isArray(ids) || !ids.length) {
            return;
        }

        ids.forEach(function (id) {
            const resolvedId = resolveMenuItemIdAlias(id);
            const $match = $container.children('li').filter(function () {
                return resolveMenuItemIdAlias(getMenuItemId($(this))) === resolvedId;
            }).first();

            if ($match.length) {
                $container.append($match);
            }
        });
    };

    const setNavItemHidden = function ($li, hidden) {
        $li.toggleClass(NAV_HIDDEN_CLASS, hidden);
        if (!hidden) {
            $li.css('display', '');
        }
    };

    const clearNavItemHidden = function ($li) {
        $li.removeClass(NAV_HIDDEN_CLASS).css('display', '');
    };

    const setAccountItemHidden = function ($li, hidden) {
        $li.toggleClass(ACCOUNT_HIDDEN_CLASS, hidden);
        if (!hidden) {
            $li.css('display', '');
        }
    };

    const isDistributionNavItem = function ($li) {
        return !$li.is('#top-menu-sub, #' + LEGALS_NAV_ITEM_ID)
            && !$li.hasClass('top-menu-profile-item')
            && !$li.hasClass('tc-hamburger-custom-link');
    };

    const readDataMenuId = function ($element) {
        if (!$element || !$element.length) {
            return null;
        }

        const attrValue = $element.attr('data-menu-id');
        if (attrValue) {
            return String(attrValue);
        }

        const dataValue = $element.data('menuId');
        return dataValue ? String(dataValue) : null;
    };

    const refreshModuleConfig = function () {
        const latestConfig = config.get('thiscoveryTheme.topNavigation');
        if (latestConfig) {
            $.extend(module.config, latestConfig);
        }
    };

    const normalizeMenuUrl = function (href) {
        if (!href || href === '#') {
            return '';
        }

        try {
            const url = new URL(href, window.location.origin);
            let path = url.pathname.replace(/\/$/, '') || '/';
            return url.search ? path + url.search : path;
        } catch (error) {
            return href.split('#')[0].replace(/\/$/, '') || '/';
        }
    };

    const resolveIdFromUrlMap = function (href, map) {
        if (!href || !map) {
            return null;
        }

        const normalizedHref = normalizeMenuUrl(href);
        if (normalizedHref && map[normalizedHref]) {
            return map[normalizedHref];
        }

        return null;
    };

    const hasSiteFooterLegals = function () {
        return module.config.siteFooterLegalsAvailable === true && $('#site-footer-root').length;
    };

    const getMenuItemId = function ($li) {
        if ($li.hasClass('top-menu-profile-item')) {
            return ACCOUNT_PROFILE_ID;
        }

        if ($li.hasClass('top-menu-home-item')) {
            return HOME_ID;
        }

        if ($li.is('#' + LEGALS_NAV_ITEM_ID)) {
            return SITE_FOOTER_LEGALS_ID;
        }

        const liId = readDataMenuId($li);
        if (liId) {
            return liId;
        }

        const $link = $li.find('a').first();
        const linkId = readDataMenuId($link);
        if (linkId) {
            return linkId;
        }

        if ($li.find('#space-menu').length) {
            return SPACE_CHOOSER_ID;
        }

        const href = $link.attr('href') || '';
        const mappedId = resolveIdFromUrlMap(href, module.config.menuItemIdByUrl);
        if (mappedId) {
            return mappedId;
        }

        return 'menu-' + normalizeMenuUrl(href);
    };

    const getAccountMenuItemId = function ($link) {
        const linkId = readDataMenuId($link);
        if (linkId) {
            return linkId;
        }

        const href = $link.attr('href') || '';
        const mappedId = resolveIdFromUrlMap(href, module.config.accountMenuItemIdByUrl);
        if (mappedId) {
            return mappedId;
        }

        if (href.indexOf('profile/home') !== -1) {
            return ACCOUNT_PROFILE_ID;
        }
        if (href.indexOf('/user/account/edit') !== -1) {
            return 'account-settings';
        }
        if (href.indexOf('/admin') !== -1) {
            return 'account-administration';
        }
        if (href.indexOf('instance-opener') !== -1) {
            return 'account-switch-network';
        }
        if (href.indexOf('/marketplace/browse') !== -1) {
            return 'account-marketplace';
        }

        const normalizedHref = normalizeMenuUrl(href);
        return normalizedHref ? 'account-' + normalizedHref : 'account-unknown';
    };

    const tagSpaceChooser = function () {
        const $chooser = getSpaceChooser();
        if ($chooser.length) {
            $chooser.attr('data-menu-id', SPACE_CHOOSER_ID);
        }
    };

    const stampNavMenuIds = function () {
        const map = module.config.menuItemIdByUrl || {};
        const $nav = getTopMenuNav();

        tagSpaceChooser();

        $nav.children('li').each(function () {
            const $li = $(this);
            const $link = $li.find('a').first();
            const mappedId = resolveIdFromUrlMap($link.attr('href') || '', map);
            const currentId = readDataMenuId($li);

            if (!mappedId) {
                return;
            }

            if (!currentId || resolveMenuItemIdAlias(currentId) !== resolveMenuItemIdAlias(mappedId)) {
                $li.attr('data-menu-id', mappedId);
                $link.attr('data-menu-id', mappedId);
            }
        });
    };

    const ensureSyntheticNavItems = function () {
        const catalog = module.config.menuItemCatalog || [];
        if (!catalog.length || !isMobilePanelViewport()) {
            return;
        }

        const configuredIds = [].concat(
            module.config.floatingNavMenuItemIds || [],
            module.config.hamburgerNavMenuItemIds || [],
        );

        if (!configuredIds.length) {
            return;
        }

        const $nav = getTopMenuNav();
        if (!$nav.length) {
            return;
        }

        catalog.forEach(function (item) {
            if (!item || !item.id || !item.url || item.id === SPACE_CHOOSER_ID || item.id === HOME_ID) {
                return;
            }

            if (item.id === SITE_FOOTER_LEGALS_ID) {
                return;
            }

            if (!listIncludes(configuredIds, item.id)) {
                return;
            }

            const resolvedId = resolveMenuItemIdAlias(item.id);
            const exists = $nav.children('li').filter(function () {
                return resolveMenuItemIdAlias(getMenuItemId($(this))) === resolvedId;
            }).length > 0;

            if (exists) {
                return;
            }

            const $label = $('<span>').text(item.label || '');
            const $link = $('<a>', {
                'class': 'nav-link',
                'href': item.url,
                'data-menu-id': item.id,
            });

            if (item.iconHtml) {
                $link.append($(item.iconHtml));
                $link.append('<br />');
            }
            $link.append($label);

            const $li = $('<li>', {
                'class': 'nav-item top-menu-item tc-mobile-nav-synthetic',
                'data-menu-id': item.id,
            });
            $li.append($link);
            $nav.append($li);
        });
    };

    const removeSyntheticNavItems = function () {
        getTopMenuNav().children('.tc-mobile-nav-synthetic').remove();
    };

    const removeCustomHamburgerLinks = function () {
        getMobileNavSlot().children('.tc-hamburger-custom-link').remove();
        getTopMenuNav().children('.tc-hamburger-custom-link').remove();
    };

    const ensureCustomHamburgerLinks = function () {
        const links = module.config.hamburgerCustomLinks || [];
        const $slot = getMobileNavSlot();

        if (!isMobilePanelViewport() || !$slot.length) {
            removeCustomHamburgerLinks();
            return;
        }

        const configuredIds = links.map(function (link) {
            return link.id;
        });

        $slot.children('.tc-hamburger-custom-link').each(function () {
            const $li = $(this);
            const id = getMenuItemId($li);
            if (!listIncludes(configuredIds, id)) {
                $li.remove();
            }
        });

        getTopMenuNav().children('.tc-hamburger-custom-link').remove();

        links.forEach(function (link) {
            if (!link || !link.id || !link.label || !link.url) {
                return;
            }

            let $li = $slot.children('li').filter(function () {
                return resolveMenuItemIdAlias(getMenuItemId($(this))) === resolveMenuItemIdAlias(link.id);
            }).first();

            if (!$li.length) {
                const $a = $('<a>', {
                    'class': 'nav-link',
                    'href': link.url,
                    'data-menu-id': link.id,
                    'text': link.label,
                });
                $li = $('<li>', {
                    'class': 'nav-item top-menu-item tc-hamburger-custom-link',
                    'data-menu-id': link.id,
                });
                $li.append($a);
            } else {
                $li.addClass('tc-hamburger-custom-link');
                $li.attr('data-menu-id', link.id);
                const $a = $li.find('a').first();
                $a.attr('href', link.url).attr('data-menu-id', link.id).text(link.label);
            }

            clearNavItemHidden($li);
            $slot.append($li);
        });
    };

    const stampAccountMenuIds = function () {
        const map = module.config.accountMenuItemIdByUrl || {};

        getAccountSlot().find('.dropdown-menu a[href]').each(function () {
            const $link = $(this);
            if (readDataMenuId($link)) {
                return;
            }

            const mappedId = resolveIdFromUrlMap($link.attr('href') || '', map);
            if (mappedId) {
                $link.attr('data-menu-id', mappedId);
            }
        });
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

    const restoreAllNavItemsFromDropdown = function () {
        const $nav = getTopMenuNav();
        const $dropdown = getTopMenuDropdown();

        if (!$nav.length || !$dropdown.length) {
            return;
        }

        while ($dropdown.children('.top-menu-item').length) {
            $nav.append($dropdown.children('.top-menu-item:first'));
        }
    };

    const restoreAllNavItemsToMainNav = function () {
        const $nav = getTopMenuNav();
        const $slot = getMobileNavSlot();

        if (!$nav.length) {
            return;
        }

        restoreAllNavItemsFromDropdown();
        $slot.children('li').not('.tc-hamburger-custom-link').appendTo($nav);
        $nav.children('.tc-hamburger-custom-link').remove();
    };

    const applyAccountMenuFiltering = function () {
        const accountIds = module.config.hamburgerAccountMenuItemIds || [];
        const showProfileInFloating = module.config.showProfileInFloatingNav !== false;
        const $slot = getAccountSlot();

        if (!isMobilePanelViewport() || !$slot.length) {
            $slot.find('.dropdown-menu > li').each(function () {
                setAccountItemHidden($(this), false);
            });
            return;
        }

        $slot.find('.dropdown-menu > li').each(function () {
            const $li = $(this);
            const $link = $li.find('a').first();

            if (!$link.length) {
                setAccountItemHidden($li, true);
                return;
            }

            const id = getAccountMenuItemId($link);
            if (id === ACCOUNT_PROFILE_ID && showProfileInFloating && isFloatingViewport()) {
                setAccountItemHidden($li, true);
                return;
            }

            if (accountIds.length && !listIncludes(accountIds, id)) {
                setAccountItemHidden($li, true);
                return;
            }

            setAccountItemHidden($li, false);
        });
    };

    const ensureSiteFooterLegalsNavItem = function () {
        if (!hasSiteFooterLegals() || !isMobilePanelViewport()) {
            return;
        }

        const showInFloating = module.config.showLegalsInFloatingNav === true;
        const $template = $('#site-footer-legals-nav-template');
        let $legals = $('#' + LEGALS_NAV_ITEM_ID);
        const $nav = getTopMenuNav();
        const $slot = getMobileNavSlot();

        if (!$legals.length && $template.length) {
            $legals = $($template.html().trim());
        }

        if (!$legals.length) {
            return;
        }

        $legals.attr('data-menu-id', SITE_FOOTER_LEGALS_ID);

        if (isFloatingViewport()) {
            if (showInFloating) {
                clearNavItemHidden($legals);
                $legals.appendTo($nav);
                return;
            }

            clearNavItemHidden($legals);
            if ($slot.length) {
                $legals.appendTo($slot);
            }
            return;
        }

        if (isHamburgerViewport()) {
            const hamburgerIds = module.config.hamburgerNavMenuItemIds || [];
            const allowed = !hamburgerIds.length || listIncludes(hamburgerIds, SITE_FOOTER_LEGALS_ID);

            if (allowed) {
                clearNavItemHidden($legals);
                $legals.appendTo($slot.length ? $slot : $nav);
            } else {
                $legals.appendTo($slot.length ? $slot : $nav);
                setNavItemHidden($legals, true);
            }
        }
    };

    const ensureProfileLast = function () {
        if (!isFloatingViewport()) {
            return;
        }

        const $nav = getTopMenuNav();
        const $profile = $nav.children('.top-menu-profile-item');
        if (!$profile.length) {
            return;
        }

        clearNavItemHidden($profile);
        $profile.detach().appendTo($nav);
    };

    const applyMobileNavDistribution = function () {
        removeSyntheticNavItems();
        restoreAllNavItemsToMainNav();
        if (isMobilePanelViewport()) {
            ensureSyntheticNavItems();
        }
        stampNavMenuIds();
        stampAccountMenuIds();

        const $nav = getTopMenuNav();
        const $slot = getMobileNavSlot();
        const $profile = $nav.children('.top-menu-profile-item');
        const floatingIds = sortedIds(module.config.floatingNavMenuItemIds || [], module.config.floatingNavMenuItemSortOrder || {}, true);
        const hamburgerNavIds = sortedIds(module.config.hamburgerNavMenuItemIds || [], module.config.hamburgerNavMenuItemSortOrder || {}, false);
        const showProfile = module.config.showProfileInFloatingNav !== false;

        if (!isMobilePanelViewport() && !isFloatingViewport()) {
            $nav.children('li').each(function () {
                clearNavItemHidden($(this));
            });
            setNavItemHidden($profile, true);
            setNavItemHidden($nav.children('.top-menu-home-item'), true);
            removeSyntheticNavItems();
            removeCustomHamburgerLinks();
            return;
        }

        if (isHamburgerViewport()) {
            const allowed = hamburgerNavIds.length ? hamburgerNavIds : null;

            $nav.children('li').filter(function () {
                return isDistributionNavItem($(this));
            }).each(function () {
                const $li = $(this);
                const id = getMenuItemId($li);
                setNavItemHidden($li, !!(allowed && !listIncludes(allowed, id)));
            });

            setNavItemHidden($profile, true);
            ensureSiteFooterLegalsNavItem();
            ensureCustomHamburgerLinks();
            reorderContainerByIds($nav, hamburgerNavIds);
            reorderContainerByIds($slot, hamburgerNavIds);
            applyAccountMenuFiltering();
            return;
        }

        if (!isFloatingViewport()) {
            $nav.children('li').each(function () {
                clearNavItemHidden($(this));
            });
            setNavItemHidden($profile, true);
            setNavItemHidden($nav.children('.top-menu-home-item'), true);
            return;
        }

        if (showProfile && $profile.length) {
            clearNavItemHidden($profile);
            $profile.appendTo($nav);
        } else {
            setNavItemHidden($profile, true);
        }

        $nav.children('li').filter(function () {
            return isDistributionNavItem($(this));
        }).each(function () {
            const $li = $(this);
            const id = getMenuItemId($li);
            const inFloating = listIncludes(floatingIds, id);
            const inHamburger = hamburgerNavIds.length
                ? listIncludes(hamburgerNavIds, id)
                : !inFloating;

            if (inFloating) {
                clearNavItemHidden($li);
                if (!$li.parent().is($nav)) {
                    $li.appendTo($nav);
                }
                return;
            }

            if (inHamburger) {
                clearNavItemHidden($li);
                $li.appendTo($slot);
                return;
            }

            if (!$li.parent().is($nav)) {
                $li.appendTo($nav);
            }
            setNavItemHidden($li, true);
        });

        ensureSiteFooterLegalsNavItem();
        ensureCustomHamburgerLinks();
        reorderContainerByIds($nav, floatingIds);
        reorderContainerByIds($slot, hamburgerNavIds);
        ensureProfileLast();
        applyAccountMenuFiltering();
    };

    const isHamburgerMode = function () {
        return module.config.mobileMenuStyle === MOBILE_MENU_STYLE_HAMBURGER
            || getBody().hasClass('hh-yg-mobile-menu-hamburger');
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
        const $slot = getMobileNavSlot();

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

        if (isFloatingViewport()) {
            if (!$nav.closest($floatingBar).length) {
                $nav.appendTo($floatingBar);
            }

            if ($slot.length && !$slot.parent().is($mobilePanel)) {
                $slot.prependTo($mobilePanel);
            }
            return;
        }

        restoreMainNav();
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
        applyMobileNavDistribution();
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
            applyAccountMenuFiltering();
            return;
        }

        if ($accountActions.closest('#topbar > .container').length && !$accountActions.closest($accountSlot).length) {
            return;
        }

        restoreAccountMenu();
    };

    const showFloatingMenu = function () {
        getBody().removeClass('hide-floating-menu');
        $(':root').css('--hh-fixed-footer-height', '');
    };

    const hideFloatingMenuOnScroll = function () {
        const onScroll = function () {
            if (!module.config.hideFloatingMenuOnScrollDown || !isFloatingViewport()) {
                showFloatingMenu();
                return;
            }

            const bodyHeightDiffWithWindow = $('body').height() - $(window).height();
            if (bodyHeightDiffWithWindow <= 0) {
                return;
            }

            const scrollTop = Math.max(0, Math.min($(window).scrollTop(), bodyHeightDiffWithWindow));

            if (scrollTop > 10 && scrollTop >= floatingMenuLastScrollTop) {
                getBody().addClass('hide-floating-menu');
                $(':root').css('--hh-fixed-footer-height', '0px');
            } else {
                showFloatingMenu();
            }

            floatingMenuLastScrollTop = scrollTop;
        };

        $(window).on('scroll' + EVENT_NS, onScroll);
        $(document).on('scroll' + EVENT_NS, onScroll);
    };

    const unbindEvents = function () {
        $(document).off(EVENT_NS);
        $(window).off(EVENT_NS);
        showFloatingMenu();
        floatingMenuLastScrollTop = 0;
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

        hideFloatingMenuOnScroll();
    };

    const onResize = function () {
        refreshModuleConfig();
        relocateNavigationLayout();

        if (!isMobilePanelViewport()) {
            closeMobileMenu();
        }

        if (!isFloatingViewport()) {
            showFloatingMenu();
            floatingMenuLastScrollTop = 0;
        }

        if (isHamburgerViewport() || isFloatingViewport()) {
            getTopMenuSub().hide();
            return;
        }

        fixNavigationOverflow();
        updateDropdownDirection();
    };

    const scheduleNavigationRelayout = function () {
        relocateNavigationLayout();
        window.setTimeout(onResize, 50);
        window.setTimeout(relocateNavigationLayout, 150);
        window.setTimeout(function () {
            ensureSiteFooterLegalsNavItem();
            ensureProfileLast();
            applyAccountMenuFiltering();
        }, 300);
    };

    const init = function () {
        refreshModuleConfig();
        resetStoredHomes();
        closeMobileMenu();
        showFloatingMenu();
        floatingMenuLastScrollTop = 0;
        bindEvents();
        scheduleNavigationRelayout();
    };

    event.on('humhub:ready', function () {
        window.setTimeout(init, 0);
    });

    event.on('humhub:afterInitModule', function (evt, mod) {
        if (!mod || mod.id !== 'humhub.modules.siteFooter') {
            return;
        }

        window.setTimeout(function () {
            ensureSiteFooterLegalsNavItem();
            ensureProfileLast();
            applyAccountMenuFiltering();
        }, 0);
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
