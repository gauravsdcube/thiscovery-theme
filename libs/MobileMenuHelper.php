<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme\libs;

use humhub\modules\menuManager\models\Configuration as MenuManagerConfiguration;
use humhub\modules\ui\menu\DropdownDivider;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\TopMenu;
use Yii;
use yii\helpers\Url;

class MobileMenuHelper
{
    public const ITEM_HOME = 'home';
    public const ITEM_SPACE_CHOOSER = 'space-chooser';
    public const ITEM_ACCOUNT_PROFILE = 'account-profile';
    public const ITEM_SITE_FOOTER_LEGALS = 'site-footer-legals';
    public const CUSTOM_HAMBURGER_LINK_ID_PREFIX = 'custom-hamburger-link-';

    public static function isCustomHamburgerLinkId(string $id): bool
    {
        return str_starts_with($id, self::CUSTOM_HAMBURGER_LINK_ID_PREFIX);
    }

    public static function generateCustomHamburgerLinkId(): string
    {
        return self::CUSTOM_HAMBURGER_LINK_ID_PREFIX . substr(bin2hex(random_bytes(8)), 0, 10);
    }

    /**
     * @param array<int, mixed>|mixed $links
     * @return array<int, array{id: string, label: string, url: string, sortOrder: int}>
     */
    public static function normalizeHamburgerCustomLinks(mixed $links): array
    {
        if (!is_array($links)) {
            return [];
        }

        $normalized = [];

        foreach ($links as $link) {
            if (!is_array($link)) {
                continue;
            }

            $label = trim((string)($link['label'] ?? ''));
            $url = trim((string)($link['url'] ?? ''));

            if ($label === '' && $url === '') {
                continue;
            }

            $id = trim((string)($link['id'] ?? ''));
            if ($id === '' || !self::isCustomHamburgerLinkId($id)) {
                $id = self::generateCustomHamburgerLinkId();
            }

            $sortOrder = (int)($link['sortOrder'] ?? 0);
            if ($sortOrder <= 0) {
                $sortOrder = 100 + count($normalized);
            }

            if ($url !== '' && !preg_match('#^(?:/|https?://)#i', $url)) {
                $url = '/' . ltrim($url, '/');
            }

            $normalized[] = [
                'id' => $id,
                'label' => $label,
                'url' => $url,
                'sortOrder' => $sortOrder,
            ];
        }

        return $normalized;
    }

    /**
     * @param array<int, array{id: string, label: string, url: string, sortOrder: int}> $links
     * @return array<string, int>
     */
    public static function buildCustomHamburgerLinkSortOrderMap(array $links): array
    {
        $map = [];

        foreach ($links as $link) {
            if (!empty($link['id'])) {
                $map[$link['id']] = (int)$link['sortOrder'];
            }
        }

        return $map;
    }

    /**
     * @param array<int, array{id: string, label: string, url: string, sortOrder: int}> $links
     * @return array<int, string>
     */
    public static function collectCustomHamburgerLinkIds(array $links): array
    {
        return array_values(array_map(static fn(array $link): string => $link['id'], $links));
    }

    /** @var array<int, MenuLink>|null */
    private static ?array $topMenuLinkEntriesCache = null;

    private static ?TopMenu $topMenuWidgetCache = null;

    /** @var array<int, array{id: string, label: string, group: string, url: string}>|null */
    private static ?array $topMenuItemsCache = null;

    /** @var array<int, array{id: string, label: string, group: string, url: string}>|null */
    private static ?array $accountMenuItemsCache = null;

    /** @var array<int, array{id: string, label: string, url: string, iconHtml: string, legacyId: string|null}>|null */
    private static ?array $menuItemCatalogCache = null;

    /** @var array<string, string>|null */
    private static ?array $menuItemIdAliasMapCache = null;

    /** @var array<string, string>|null */
    private static ?array $knownMenuLinkUrlIdMapCache = null;

    private static bool $loadingTopMenuLinkEntries = false;

    private static bool $loadingTopMenuWidget = false;

    public static function getDefaultFloatingNavItemIds(): array
    {
        return [self::ITEM_HOME, 'dashboard', 'spaces', self::ITEM_SPACE_CHOOSER];
    }

    public static function isSiteFooterLegalsAvailable(): bool
    {
        /** @var \humhub\modules\siteFooter\Module|null $module */
        $module = Yii::$app->getModule('site-footer');

        return $module !== null && $module->isFooterEnabled() && $module->hasFooterContent();
    }

    /**
     * @return array<int, array{id: string, label: string, group: string, url: string}>
     */
    public static function collectConfigurableTopMenuItems(): array
    {
        $items = self::collectTopMenuItems();
        $legalsItem = self::collectSiteFooterLegalsMenuItem();
        if ($legalsItem !== null) {
            $items[] = $legalsItem;
        }

        return self::uniqueItems($items);
    }

    /**
     * @return array{id: string, label: string, group: string, url: string}|null
     */
    public static function collectSiteFooterLegalsMenuItem(): ?array
    {
        if (!self::isSiteFooterLegalsAvailable()) {
            return null;
        }

        return [
            'id' => self::ITEM_SITE_FOOTER_LEGALS,
            'label' => Yii::t('SiteFooterModule.base', 'Legals'),
            'group' => 'main',
            'url' => '#',
        ];
    }

    /**
     * @return array<int, array{id: string, label: string, group: string, url: string}>
     */
    public static function collectTopMenuItems(): array
    {
        if (self::$topMenuItemsCache !== null) {
            return self::$topMenuItemsCache;
        }

        $items = [
            [
                'id' => self::ITEM_HOME,
                'label' => Yii::t('ThiscoveryThemeModule.base', 'Home'),
                'group' => 'main',
                'url' => self::normalizeMenuUrl((string)Url::home()),
            ],
            [
                'id' => self::ITEM_SPACE_CHOOSER,
                'label' => Yii::t('ThiscoveryThemeModule.base', 'Space chooser'),
                'group' => 'main',
                'url' => '',
            ],
        ];

        foreach (self::getTopMenuLinkEntries() as $entry) {
            $items[] = [
                'id' => self::resolveTopMenuItemId($entry),
                'label' => self::formatMenuItemLabel($entry),
                'group' => 'main',
                'url' => self::normalizeMenuUrl((string)Url::to($entry->getUrl())),
            ];
        }

        self::$topMenuItemsCache = self::uniqueItems($items);

        return self::$topMenuItemsCache;
    }

    /**
     * @return array<int, array{id: string, label: string, group: string, url: string}>
     */
    public static function collectAccountMenuItems(): array
    {
        if (self::$accountMenuItemsCache !== null) {
            return self::$accountMenuItemsCache;
        }

        if (Yii::$app->user->isGuest) {
            self::$accountMenuItemsCache = [];
            return self::$accountMenuItemsCache;
        }

        $menu = new AccountTopMenu(['template' => '']);
        $menu->init();

        $items = [];
        foreach ($menu->getSortedEntries() as $entry) {
            if ($entry instanceof DropdownDivider || !$entry instanceof MenuLink) {
                continue;
            }

            $items[] = [
                'id' => self::resolveAccountMenuItemId($entry),
                'label' => self::formatMenuItemLabel($entry),
                'group' => 'account',
                'url' => self::normalizeMenuUrl((string)Url::to($entry->getUrl())),
            ];
        }

        self::$accountMenuItemsCache = self::uniqueItems($items);

        return self::$accountMenuItemsCache;
    }

    /**
     * @return array<int, MenuLink>
     */
    private static function getTopMenuLinkEntries(): array
    {
        if (self::$topMenuLinkEntriesCache !== null) {
            return self::$topMenuLinkEntriesCache;
        }

        if (self::$loadingTopMenuLinkEntries) {
            return [];
        }

        self::$loadingTopMenuLinkEntries = true;

        try {
            $menu = self::getTopMenuWidget();

            $entries = [];
            foreach ($menu->getSortedEntries() as $entry) {
                if ($entry instanceof MenuLink) {
                    $entries[] = $entry;
                }
            }

            self::$topMenuLinkEntriesCache = $entries;
        } finally {
            self::$loadingTopMenuLinkEntries = false;
        }

        return self::$topMenuLinkEntriesCache;
    }

    private static function getTopMenuWidget(): TopMenu
    {
        if (self::$topMenuWidgetCache !== null) {
            return self::$topMenuWidgetCache;
        }

        if (self::$loadingTopMenuWidget) {
            return new TopMenu(['template' => '']);
        }

        self::$loadingTopMenuWidget = true;

        try {
            $menu = new TopMenu(['template' => '']);
            $menu->init();
            self::$topMenuWidgetCache = $menu;
        } finally {
            self::$loadingTopMenuWidget = false;
        }

        return self::$topMenuWidgetCache;
    }

    public static function resolveTopMenuItemId(MenuLink $entry): string
    {
        $id = $entry->getId();
        if ($id) {
            return (string)$id;
        }

        $normalizedUrl = self::normalizeMenuUrl((string)Url::to($entry->getUrl()));
        $knownId = self::resolveKnownMenuLinkIdByUrl($normalizedUrl);
        if ($knownId !== null) {
            return $knownId;
        }

        return self::buildLegacyMenuItemId($entry);
    }

    public static function buildLegacyMenuItemId(MenuLink $entry): string
    {
        return 'menu-' . substr(md5((string)Url::to($entry->getUrl())), 0, 10);
    }

    public static function resolveKnownMenuLinkIdByUrl(string $normalizedUrl): ?string
    {
        if ($normalizedUrl === '') {
            return null;
        }

        $map = self::buildKnownMenuLinkUrlIdMap();

        if (isset($map[$normalizedUrl])) {
            return $map[$normalizedUrl];
        }

        if (preg_match('#^/calendar(?:/|$)#', $normalizedUrl)) {
            return 'calendar';
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public static function buildKnownMenuLinkUrlIdMap(): array
    {
        if (self::$knownMenuLinkUrlIdMapCache !== null) {
            return self::$knownMenuLinkUrlIdMapCache;
        }

        $map = [
            self::normalizeMenuUrl((string)Url::home()) => self::ITEM_HOME,
            '/calendar' => 'calendar',
        ];

        if (!class_exists(MenuManagerConfiguration::class)) {
            self::$knownMenuLinkUrlIdMapCache = $map;
            return self::$knownMenuLinkUrlIdMapCache;
        }

        // Seed cache before any ID resolution to avoid recursive rebuilds.
        self::$knownMenuLinkUrlIdMapCache = $map;

        foreach (MenuManagerConfiguration::ATTRIBUTE_MENU_LINK_ID as $attribute => $menuLinkId) {
            if (in_array($attribute, ['topMenuHome', 'topMenuSpaceChooser'], true)) {
                continue;
            }

            $entryUrl = match ($attribute) {
                'topMenuCalendar' => class_exists(\humhub\modules\calendar\helpers\Url::class)
                    ? \humhub\modules\calendar\helpers\Url::toGlobalCalendar()
                    : null,
                'topMenuJitsiMeet' => class_exists(\humhub\modules\jitsiMeet\Module::class)
                    ? ['/jitsi-meet/room']
                    : null,
                default => null,
            };

            if ($entryUrl === null) {
                $entry = self::getTopMenuWidget()->getEntryById($menuLinkId);
                if ($entry instanceof MenuLink) {
                    $entryUrl = $entry->getUrl();
                }
            }

            if ($entryUrl === null) {
                continue;
            }

            $normalizedUrl = self::normalizeMenuUrl((string)Url::to($entryUrl));
            if ($normalizedUrl !== '') {
                $map[$normalizedUrl] = $menuLinkId;
            }
        }

        self::$knownMenuLinkUrlIdMapCache = $map;

        return self::$knownMenuLinkUrlIdMapCache;
    }

    /**
     * @return array<int, array{id: string, label: string, url: string, iconHtml: string, legacyId: string|null}>
     */
    public static function buildMenuItemCatalog(): array
    {
        if (self::$menuItemCatalogCache !== null) {
            return self::$menuItemCatalogCache;
        }

        $catalog = [
            [
                'id' => self::ITEM_HOME,
                'label' => Yii::t('ThiscoveryThemeModule.base', 'Home'),
                'url' => (string)Url::home(),
                'iconHtml' => '<i class="fa fa-home"></i>',
                'legacyId' => null,
            ],
        ];

        foreach (self::getTopMenuLinkEntries() as $entry) {
            $id = self::resolveTopMenuItemId($entry);
            $legacyId = self::buildLegacyMenuItemId($entry);

            $catalog[] = [
                'id' => $id,
                'label' => self::formatMenuItemLabel($entry),
                'url' => (string)Url::to($entry->getUrl()),
                'iconHtml' => (string)$entry->getIcon(),
                'legacyId' => $legacyId !== $id ? $legacyId : null,
            ];
        }

        $legalsItem = self::collectSiteFooterLegalsMenuItem();
        if ($legalsItem !== null) {
            $catalog[] = [
                'id' => $legalsItem['id'],
                'label' => $legalsItem['label'],
                'url' => '#',
                'iconHtml' => '',
                'legacyId' => null,
            ];
        }

        self::$menuItemCatalogCache = self::uniqueCatalogItems($catalog);

        return self::$menuItemCatalogCache;
    }

    /**
     * @return array<string, string>
     */
    public static function buildMenuItemIdAliasMap(): array
    {
        if (self::$menuItemIdAliasMapCache !== null) {
            return self::$menuItemIdAliasMapCache;
        }

        $map = [];

        foreach (self::buildMenuItemCatalog() as $item) {
            $map[$item['id']] = $item['id'];
            if (!empty($item['legacyId'])) {
                $map[$item['legacyId']] = $item['id'];
            }
        }

        self::$menuItemIdAliasMapCache = $map;

        return self::$menuItemIdAliasMapCache;
    }

    public static function resolveMenuItemIdAlias(string $id): string
    {
        $map = self::buildMenuItemIdAliasMap();

        return $map[$id] ?? $id;
    }

    /**
     * @param array<int, string> $configuredIds
     */
    public static function isConfiguredMenuItemId(string $id, array $configuredIds): bool
    {
        $canonical = self::resolveMenuItemIdAlias($id);

        foreach ($configuredIds as $configuredId) {
            if (self::resolveMenuItemIdAlias((string)$configuredId) === $canonical) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, string> $ids
     * @return array<int, string>
     */
    public static function migrateConfiguredMenuItemIds(array $ids): array
    {
        return self::normalizeMenuItemIdsWithAliases($ids);
    }

    /**
     * @param array<string, mixed> $sortOrder
     * @return array<string, int>
     */
    public static function migrateMenuItemSortOrder(array $sortOrder): array
    {
        $migrated = [];

        foreach ($sortOrder as $id => $position) {
            $menuId = self::resolveMenuItemIdAlias((string)$id);
            $position = (int)$position;

            if ($menuId === '' || $position <= 0 || isset($migrated[$menuId])) {
                continue;
            }

            $migrated[$menuId] = $position;
        }

        return $migrated;
    }

    /**
     * @param array<int, string> $ids
     * @return array<int, string>
     */
    public static function normalizeMenuItemIdsWithAliases(array $ids): array
    {
        $normalized = [];

        foreach ($ids as $id) {
            $id = trim((string)$id);
            if ($id === '') {
                continue;
            }

            $canonical = self::resolveMenuItemIdAlias($id);
            if (!in_array($canonical, $normalized, true)) {
                $normalized[] = $canonical;
            }
        }

        return $normalized;
    }

    public static function formatMenuItemLabel(MenuLink $entry): string
    {
        $label = html_entity_decode(strip_tags((string)$entry->getLabel()), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $label) ?? $label);
    }

    public static function resolveAccountMenuItemId(MenuLink $entry): string
    {
        $id = $entry->getId();
        if ($id) {
            return (string)$id;
        }

        $url = Url::to($entry->getUrl());

        if (str_contains($url, '/user/profile/home') || str_contains($url, 'profile/home')) {
            return self::ITEM_ACCOUNT_PROFILE;
        }

        if (str_contains($url, '/user/account/edit')) {
            return 'account-settings';
        }

        if (str_contains($url, '/admin')) {
            return 'account-administration';
        }

        if (str_contains($url, 'instance-opener')) {
            return 'account-switch-network';
        }

        if (str_contains($url, '/marketplace/browse')) {
            return 'account-marketplace';
        }

        return 'account-' . substr(md5($url), 0, 10);
    }

    /**
     * @return array<string, string>
     */
    public static function buildTopMenuUrlIdMap(): array
    {
        $map = [];
        foreach (self::collectConfigurableTopMenuItems() as $item) {
            if ($item['url'] !== '') {
                $map[$item['url']] = $item['id'];
            }
            $map['__id__:' . $item['id']] = $item['id'];
        }

        return $map;
    }

    /**
     * @return array<string, string>
     */
    public static function buildAccountMenuUrlIdMap(): array
    {
        $map = [];
        foreach (self::collectAccountMenuItems() as $item) {
            if ($item['url'] !== '') {
                $map[$item['url']] = $item['id'];
            }
            $map['__id__:' . $item['id']] = $item['id'];
        }

        return $map;
    }

    public static function normalizeMenuUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '' || $url === '#') {
            return '';
        }

        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '/')) {
            $url = '/' . ltrim($url, '/');
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $query = parse_url($url, PHP_URL_QUERY);
        $path = rtrim($path, '/') ?: '/';

        return $query ? $path . '?' . $query : $path;
    }

    /**
     * @param array<int, string> $configuredIds
     * @param array<int, string> $allTopMenuIds
     * @return array<int, string>
     */
    public static function resolveHamburgerNavItemIds(array $configuredIds, array $allTopMenuIds, array $floatingIds): array
    {
        if ($configuredIds !== []) {
            return $configuredIds;
        }

        $floatingLookup = array_fill_keys($floatingIds, true);
        $resolved = [];

        foreach ($allTopMenuIds as $id) {
            if (!isset($floatingLookup[$id])) {
                $resolved[] = $id;
            }
        }

        return $resolved;
    }

    /**
     * @param array<int, string> $configuredIds
     * @param array<int, string> $allAccountIds
     * @return array<int, string>
     */
    public static function resolveHamburgerAccountItemIds(array $configuredIds, array $allAccountIds, bool $showProfileInFloatingNav): array
    {
        if ($configuredIds !== []) {
            return $configuredIds;
        }

        if (!$showProfileInFloatingNav) {
            return $allAccountIds;
        }

        return array_values(array_filter(
            $allAccountIds,
            static fn(string $id): bool => $id !== self::ITEM_ACCOUNT_PROFILE,
        ));
    }

    /**
     * @param array<int, array{id: string, label: string, group: string, url?: string}> $items
     * @return array<int, array{id: string, label: string, group: string, url?: string}>
     */
    private static function uniqueItems(array $items): array
    {
        $seen = [];
        $result = [];

        foreach ($items as $item) {
            if (isset($seen[$item['id']])) {
                continue;
            }

            $seen[$item['id']] = true;
            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param array<int, array{id: string, label: string, url: string, iconHtml: string, legacyId: string|null}> $items
     * @return array<int, array{id: string, label: string, url: string, iconHtml: string, legacyId: string|null}>
     */
    private static function uniqueCatalogItems(array $items): array
    {
        $seen = [];
        $result = [];

        foreach ($items as $item) {
            if (isset($seen[$item['id']])) {
                continue;
            }

            $seen[$item['id']] = true;
            $result[] = $item;
        }

        return $result;
    }
}
