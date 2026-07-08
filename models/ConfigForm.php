<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme\models;

use humhub\components\SettingsManager;
use humhub\modules\thiscoveryTheme\Module;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use Yii;
use yii\base\Model;

class ConfigForm extends Model
{
    public const TOP_BAR_HEIGHT_SM = 50;
    public const TOP_BAR_BOTTOM_SPACING = 12;
    public const TOP_BAR_BOTTOM_SPACING_SM = 6;
    public const BOTTOM_BAR_HEIGHT_SM = 50;
    public const FLOATING_BAR_HEIGHT_SM = 76;
    public const MOBILE_MENU_STYLE_HAMBURGER = 'hamburger';
    public const MOBILE_MENU_STYLE_BOTTOM_BAR = 'bottom-bar';
    public const MOBILE_MENU_STYLE_FLOATING_BAR = 'floating-bar';
    public const EXPORT_FORMAT = 'thiscovery-theme';
    public const EXPORT_VERSION = 1;

    private const COLOR_ATTRIBUTES = [
        'themePrimaryColor',
        'themeAccentColor',
        'themeSecondaryColor',
        'themeSuccessColor',
        'themeDangerColor',
        'themeWarningColor',
        'themeInfoColor',
        'themeLightColor',
        'themeDarkColor',
        'topMenuBackgroundColor',
        'topMenuTextColor',
        'topMenuButtonHoverBackgroundColor',
        'topMenuButtonHoverTextColor',
        'linkColor',
        'textColorMain',
        'textColorSecondary',
        'backgroundColorMain',
        'backgroundColorPage',
        'panelBorderColor',
        'menuItemTextColor',
        'menuItemHoverTextColor',
        'menuItemActiveTextColor',
        'menuItemActiveBackgroundColor',
        'inputBackgroundColor',
        'inputTextColor',
        'inputBorderColor',
        'cardBackgroundColor',
        'cardTextColor',
        'tableHeaderBackgroundColor',
        'tableHeaderTextColor',
        'tableRowBorderColor',
        'listGroupItemTextColor',
        'listGroupItemHoverTextColor',
        'listGroupItemActiveTextColor',
        'listGroupItemBackgroundColor',
        'listGroupItemHoverBackgroundColor',
        'listGroupItemActiveBackgroundColor',
        'listGroupItemBorderColor',
        'sidebarBackgroundColor',
        'sidebarTextColor',
        'sideMenuItemTextColor',
        'sideMenuItemHoverTextColor',
        'sideMenuItemActiveTextColor',
        'sideMenuItemHoverBackgroundColor',
        'sideMenuItemActiveBackgroundColor',
        'footerBackgroundColor',
        'footerTextColor',
        'bottomMenuBackgroundColor',
        'bottomMenuTextColor',
        'floatingMenuBackgroundColor',
        'floatingMenuTextColor',
        'floatingMenuActiveColor',
        'mobileMenuBackgroundColor',
        'mobileMenuTextColor',
        'dangerBadgeBackgroundColor',
        'dangerBadgeTextColor',
        'buttonBackgroundColor',
        'buttonTextColor',
        'buttonBorderColor',
        'buttonHoverBackgroundColor',
        'buttonHoverTextColor',
        'buttonSecondaryBackgroundColor',
        'buttonSecondaryTextColor',
        'buttonSecondaryBorderColor',
        'buttonSecondaryHoverBackgroundColor',
        'buttonSecondaryHoverTextColor',
        'accordionBorderColor',
        'accordionBackgroundColor',
        'accordionHeaderBackgroundColor',
        'accordionHeaderTextColor',
        'accordionHeaderHoverBackgroundColor',
        'accordionHeaderHoverTextColor',
        'accordionHeaderOpenBackgroundColor',
        'accordionHeaderOpenTextColor',
        'accordionContentTextColor',
        'accordionFaqTitleColor',
    ];

    private const EXPORTABLE_ATTRIBUTES = [
        'themePrimaryColor',
        'themeAccentColor',
        'themeSecondaryColor',
        'themeSuccessColor',
        'themeDangerColor',
        'themeWarningColor',
        'themeInfoColor',
        'themeLightColor',
        'themeDarkColor',
        'topBarHeight',
        'topBarFontSize',
        'topMenuNavJustifyContent',
        'topMenuBackgroundColor',
        'topMenuTextColor',
        'topMenuButtonHoverBackgroundColor',
        'topMenuButtonHoverTextColor',
        'topMenuTextTransform',
        'topMenuFontWeight',
        'topMenuLetterSpacing',
        'topMenuFontStyle',
        'containerMaxWidth',
        'fontFamily',
        'headingFontFamily',
        'fontSize',
        'fontWeight',
        'fontBoldWeight',
        'h1FontSize',
        'h2FontSize',
        'h3FontSize',
        'h4FontSize',
        'h5FontSize',
        'h6FontSize',
        'linkColor',
        'textColorMain',
        'textColorSecondary',
        'backgroundColorMain',
        'backgroundColorPage',
        'panelBorderColor',
        'panelBorderRadius',
        'panelBoxShadow',
        'buttonBorderRadius',
        'menuItemTextColor',
        'menuItemHoverTextColor',
        'menuItemActiveTextColor',
        'menuItemActiveBackgroundColor',
        'menuItemPaddingX',
        'menuItemPaddingY',
        'buttonPaddingX',
        'buttonPaddingY',
        'buttonFontWeight',
        'inputBackgroundColor',
        'inputTextColor',
        'inputBorderColor',
        'inputBorderRadius',
        'cardBackgroundColor',
        'cardTextColor',
        'cardPadding',
        'tableHeaderBackgroundColor',
        'tableHeaderTextColor',
        'tableRowBorderColor',
        'listGroupItemTextColor',
        'listGroupItemHoverTextColor',
        'listGroupItemActiveTextColor',
        'listGroupItemBackgroundColor',
        'listGroupItemHoverBackgroundColor',
        'listGroupItemActiveBackgroundColor',
        'listGroupItemBorderColor',
        'listGroupItemPaddingY',
        'listGroupItemPaddingX',
        'listGroupItemBorderRadius',
        'sidebarBackgroundColor',
        'sidebarTextColor',
        'sideMenuItemTextColor',
        'sideMenuItemHoverTextColor',
        'sideMenuItemActiveTextColor',
        'sideMenuItemHoverBackgroundColor',
        'sideMenuItemActiveBackgroundColor',
        'sideMenuItemPaddingY',
        'sideMenuItemPaddingX',
        'sideMenuItemBorderRadius',
        'footerBackgroundColor',
        'footerTextColor',
        'mobileMenuStyle',
        'bottomMenuBackgroundColor',
        'bottomMenuTextColor',
        'floatingMenuBackgroundColor',
        'floatingMenuTextColor',
        'floatingMenuActiveColor',
        'floatingMenuBackgroundOpacity',
        'hideFloatingMenuItemLabels',
        'hideFloatingMenuOnScrollDown',
        'hideTextInBottomMenuItems',
        'mobileMenuBackgroundColor',
        'mobileMenuTextColor',
        'mobileMenuFontSize',
        'mobileMenuHighlightActive',
        'mobileMenuItemPaddingX',
        'mobileMenuItemPaddingY',
        'mobileContentPaddingX',
        'mobileContentPaddingY',
        'mobileContentGutter',
        'mobilePanelSpacing',
        'mobilePanelBodyPadding',
        'mobileTopbarPaddingX',
        'dangerBadgeBackgroundColor',
        'dangerBadgeTextColor',
        'buttonBackgroundColor',
        'buttonTextColor',
        'buttonBorderColor',
        'buttonHoverBackgroundColor',
        'buttonHoverTextColor',
        'buttonSecondaryBackgroundColor',
        'buttonSecondaryTextColor',
        'buttonSecondaryBorderColor',
        'buttonSecondaryHoverBackgroundColor',
        'buttonSecondaryHoverTextColor',
        'accordionBorderColor',
        'accordionBorderRadius',
        'accordionMarginBottom',
        'accordionBackgroundColor',
        'accordionBoxShadow',
        'accordionHeaderBackgroundColor',
        'accordionHeaderTextColor',
        'accordionHeaderFontSize',
        'accordionHeaderFontWeight',
        'accordionHeaderPaddingY',
        'accordionHeaderPaddingX',
        'accordionHeaderHoverBackgroundColor',
        'accordionHeaderHoverTextColor',
        'accordionHeaderOpenBackgroundColor',
        'accordionHeaderOpenTextColor',
        'accordionContentTextColor',
        'accordionContentFontSize',
        'accordionContentPadding',
        'accordionFaqTitleColor',
        'accordionFaqTitleFontSize',
        'accordionEnableAnimation',
        'customCssRules',
    ];

    protected ?SettingsManager $settings = null;

    public ?string $themePrimaryColor = null;
    public ?string $themeAccentColor = null;
    public ?string $themeSecondaryColor = null;
    public ?string $themeSuccessColor = null;
    public ?string $themeDangerColor = null;
    public ?string $themeWarningColor = null;
    public ?string $themeInfoColor = null;
    public ?string $themeLightColor = null;
    public ?string $themeDarkColor = null;
    public int|string|null $topBarHeight = null;
    public int|string|null $topBarFontSize = null;
    public ?string $topMenuNavJustifyContent = null;
    public ?string $topMenuBackgroundColor = null;
    public ?string $topMenuTextColor = null;
    public ?string $topMenuButtonHoverBackgroundColor = null;
    public ?string $topMenuButtonHoverTextColor = null;
    public ?string $topMenuTextTransform = null;
    public int|string|null $topMenuFontWeight = null;
    public ?string $topMenuLetterSpacing = null;
    public ?string $topMenuFontStyle = null;
    public int|string|null $containerMaxWidth = null;
    public ?string $fontFamily = null;
    public ?string $headingFontFamily = null;
    public int|string|null $fontSize = null;
    public int|string|null $fontWeight = null;
    public int|string|null $fontBoldWeight = null;
    public int|string|null $h1FontSize = null;
    public int|string|null $h2FontSize = null;
    public int|string|null $h3FontSize = null;
    public int|string|null $h4FontSize = null;
    public int|string|null $h5FontSize = null;
    public int|string|null $h6FontSize = null;
    public ?string $linkColor = null;
    public ?string $textColorMain = null;
    public ?string $textColorSecondary = null;
    public ?string $backgroundColorMain = null;
    public ?string $backgroundColorPage = null;
    public ?string $panelBorderColor = null;
    public int|string|null $panelBorderRadius = null;
    public ?string $panelBoxShadow = null;
    public int|string|null $buttonBorderRadius = null;
    public ?string $menuItemTextColor = null;
    public ?string $menuItemHoverTextColor = null;
    public ?string $menuItemActiveTextColor = null;
    public ?string $menuItemActiveBackgroundColor = null;
    public int|string|null $menuItemPaddingX = null;
    public int|string|null $menuItemPaddingY = null;
    public int|string|null $buttonPaddingX = null;
    public int|string|null $buttonPaddingY = null;
    public int|string|null $buttonFontWeight = null;
    public ?string $inputBackgroundColor = null;
    public ?string $inputTextColor = null;
    public ?string $inputBorderColor = null;
    public int|string|null $inputBorderRadius = null;
    public ?string $cardBackgroundColor = null;
    public ?string $cardTextColor = null;
    public int|string|null $cardPadding = null;
    public ?string $tableHeaderBackgroundColor = null;
    public ?string $tableHeaderTextColor = null;
    public ?string $tableRowBorderColor = null;
    public ?string $listGroupItemTextColor = null;
    public ?string $listGroupItemHoverTextColor = null;
    public ?string $listGroupItemActiveTextColor = null;
    public ?string $listGroupItemBackgroundColor = null;
    public ?string $listGroupItemHoverBackgroundColor = null;
    public ?string $listGroupItemActiveBackgroundColor = null;
    public ?string $listGroupItemBorderColor = null;
    public int|string|null $listGroupItemPaddingY = null;
    public int|string|null $listGroupItemPaddingX = null;
    public int|string|null $listGroupItemBorderRadius = null;
    public ?string $sidebarBackgroundColor = null;
    public ?string $sidebarTextColor = null;
    public ?string $sideMenuItemTextColor = null;
    public ?string $sideMenuItemHoverTextColor = null;
    public ?string $sideMenuItemActiveTextColor = null;
    public ?string $sideMenuItemHoverBackgroundColor = null;
    public ?string $sideMenuItemActiveBackgroundColor = null;
    public int|string|null $sideMenuItemPaddingY = null;
    public int|string|null $sideMenuItemPaddingX = null;
    public int|string|null $sideMenuItemBorderRadius = null;
    public ?string $footerBackgroundColor = null;
    public ?string $footerTextColor = null;
    public ?string $mobileMenuStyle = null;
    public ?string $bottomMenuBackgroundColor = null;
    public ?string $bottomMenuTextColor = null;
    public ?string $floatingMenuBackgroundColor = null;
    public ?string $floatingMenuTextColor = null;
    public ?string $floatingMenuActiveColor = null;
    public int|string|null $floatingMenuBackgroundOpacity = null;
    public string|bool|null $hideFloatingMenuItemLabels = null;
    public string|bool|null $hideFloatingMenuOnScrollDown = null;
    public string|bool|null $hideTextInBottomMenuItems = null;
    public ?string $mobileMenuBackgroundColor = null;
    public ?string $mobileMenuTextColor = null;
    public int|string|null $mobileMenuFontSize = null;
    public string|bool|null $mobileMenuHighlightActive = null;
    public int|string|null $mobileMenuItemPaddingX = null;
    public int|string|null $mobileMenuItemPaddingY = null;
    public int|string|null $mobileContentPaddingX = null;
    public int|string|null $mobileContentPaddingY = null;
    public int|string|null $mobileContentGutter = null;
    public int|string|null $mobilePanelSpacing = null;
    public int|string|null $mobilePanelBodyPadding = null;
    public int|string|null $mobileTopbarPaddingX = null;
    public ?string $dangerBadgeBackgroundColor = null;
    public ?string $dangerBadgeTextColor = null;
    public ?string $buttonBackgroundColor = null;
    public ?string $buttonTextColor = null;
    public ?string $buttonBorderColor = null;
    public ?string $buttonHoverBackgroundColor = null;
    public ?string $buttonHoverTextColor = null;
    public ?string $buttonSecondaryBackgroundColor = null;
    public ?string $buttonSecondaryTextColor = null;
    public ?string $buttonSecondaryBorderColor = null;
    public ?string $buttonSecondaryHoverBackgroundColor = null;
    public ?string $buttonSecondaryHoverTextColor = null;
    public ?string $accordionBorderColor = null;
    public int|string|null $accordionBorderRadius = null;
    public int|string|null $accordionMarginBottom = null;
    public ?string $accordionBackgroundColor = null;
    public ?string $accordionBoxShadow = null;
    public ?string $accordionHeaderBackgroundColor = null;
    public ?string $accordionHeaderTextColor = null;
    public int|string|null $accordionHeaderFontSize = null;
    public int|string|null $accordionHeaderFontWeight = null;
    public int|string|null $accordionHeaderPaddingY = null;
    public int|string|null $accordionHeaderPaddingX = null;
    public ?string $accordionHeaderHoverBackgroundColor = null;
    public ?string $accordionHeaderHoverTextColor = null;
    public ?string $accordionHeaderOpenBackgroundColor = null;
    public ?string $accordionHeaderOpenTextColor = null;
    public ?string $accordionContentTextColor = null;
    public int|string|null $accordionContentFontSize = null;
    public int|string|null $accordionContentPadding = null;
    public ?string $accordionFaqTitleColor = null;
    public int|string|null $accordionFaqTitleFontSize = null;
    public string|bool|null $accordionEnableAnimation = null;
    public array $customCssRules = [];

    public function init()
    {
        parent::init();

        /** @var Module $module */
        $module = Yii::$app->getModule('thiscovery-theme');
        $this->settings = $module->settings;

        $this->themePrimaryColor = $this->settings->get('themePrimaryColor', '#1d70b8');
        $this->themeAccentColor = $this->settings->get('themeAccentColor', '#003078');
        $this->themeSecondaryColor = $this->settings->get('themeSecondaryColor', '#505a5f');
        $this->themeSuccessColor = $this->settings->get('themeSuccessColor', '#00703c');
        $this->themeDangerColor = $this->settings->get('themeDangerColor', '#d4351c');
        $this->themeWarningColor = $this->settings->get('themeWarningColor', '#f47738');
        $this->themeInfoColor = $this->settings->get('themeInfoColor', '#1d70b8');
        $this->themeLightColor = $this->settings->get('themeLightColor', '#f3f2f1');
        $this->themeDarkColor = $this->settings->get('themeDarkColor', '#0b0c0c');
        $this->topBarHeight = (int)$this->settings->get('topBarHeight', 64);
        $this->topBarFontSize = (int)$this->settings->get('topBarFontSize', 11);
        $this->topMenuNavJustifyContent = $this->settings->get('topMenuNavJustifyContent', 'flex-start');
        $this->topMenuBackgroundColor = $this->settings->get('topMenuBackgroundColor', '#0b0c0c');
        $this->topMenuTextColor = $this->settings->get('topMenuTextColor', '#ffffff');
        $this->topMenuButtonHoverBackgroundColor = $this->settings->get('topMenuButtonHoverBackgroundColor', '#1d70b8');
        $this->topMenuButtonHoverTextColor = $this->settings->get('topMenuButtonHoverTextColor', '#ffffff');
        $this->topMenuTextTransform = $this->settings->get('topMenuTextTransform', 'uppercase');
        $this->topMenuFontWeight = (int)$this->settings->get('topMenuFontWeight', 700);
        $this->topMenuLetterSpacing = $this->settings->get('topMenuLetterSpacing', '0.03em');
        $this->topMenuFontStyle = $this->settings->get('topMenuFontStyle', 'normal');
        $this->containerMaxWidth = (int)$this->settings->get('containerMaxWidth', 1600);
        $this->fontFamily = $this->settings->get('fontFamily', 'Arial');
        $this->headingFontFamily = $this->settings->get('headingFontFamily', 'Arial');
        $this->fontSize = (int)$this->settings->get('fontSize', 16);
        $this->fontWeight = (int)$this->settings->get('fontWeight', 400);
        $this->fontBoldWeight = (int)$this->settings->get('fontBoldWeight', 700);
        $this->h1FontSize = (int)$this->settings->get('h1FontSize', 40);
        $this->h2FontSize = (int)$this->settings->get('h2FontSize', 32);
        $this->h3FontSize = (int)$this->settings->get('h3FontSize', 26);
        $this->h4FontSize = (int)$this->settings->get('h4FontSize', 22);
        $this->h5FontSize = (int)$this->settings->get('h5FontSize', 19);
        $this->h6FontSize = (int)$this->settings->get('h6FontSize', 16);
        $this->linkColor = $this->settings->get('linkColor', '#1d70b8');
        $this->textColorMain = $this->settings->get('textColorMain', '#0b0c0c');
        $this->textColorSecondary = $this->settings->get('textColorSecondary', '#505a5f');
        $this->backgroundColorMain = $this->settings->get('backgroundColorMain', '#ffffff');
        $this->backgroundColorPage = $this->settings->get('backgroundColorPage', '#f3f2f1');
        $this->panelBorderColor = $this->settings->get('panelBorderColor', '#d8d8d8');
        $this->panelBorderRadius = (int)$this->settings->get('panelBorderRadius', 6);
        $this->panelBoxShadow = $this->settings->get('panelBoxShadow', '0 1px 6px #00000014');
        $this->buttonBorderRadius = (int)$this->settings->get('buttonBorderRadius', 4);
        $this->menuItemTextColor = $this->settings->get('menuItemTextColor', '#ffffff');
        $this->menuItemHoverTextColor = $this->settings->get('menuItemHoverTextColor', '#ffffff');
        $this->menuItemActiveTextColor = $this->settings->get('menuItemActiveTextColor', '#ffffff');
        $this->menuItemActiveBackgroundColor = $this->settings->get('menuItemActiveBackgroundColor', '#1d70b8');
        $this->menuItemPaddingX = (int)$this->settings->get('menuItemPaddingX', 12);
        $this->menuItemPaddingY = (int)$this->settings->get('menuItemPaddingY', 0);
        $this->buttonPaddingX = (int)$this->settings->get('buttonPaddingX', 14);
        $this->buttonPaddingY = (int)$this->settings->get('buttonPaddingY', 8);
        $this->buttonFontWeight = (int)$this->settings->get('buttonFontWeight', 600);
        $this->inputBackgroundColor = $this->settings->get('inputBackgroundColor', '#ffffff');
        $this->inputTextColor = $this->settings->get('inputTextColor', '#0b0c0c');
        $this->inputBorderColor = $this->settings->get('inputBorderColor', '#b1b4b6');
        $this->inputBorderRadius = (int)$this->settings->get('inputBorderRadius', 4);
        $this->cardBackgroundColor = $this->settings->get('cardBackgroundColor', '#ffffff');
        $this->cardTextColor = $this->settings->get('cardTextColor', '#0b0c0c');
        $this->cardPadding = (int)$this->settings->get('cardPadding', 16);
        $this->tableHeaderBackgroundColor = $this->settings->get('tableHeaderBackgroundColor', '#f3f2f1');
        $this->tableHeaderTextColor = $this->settings->get('tableHeaderTextColor', '#0b0c0c');
        $this->tableRowBorderColor = $this->settings->get('tableRowBorderColor', '#d8d8d8');
        $this->listGroupItemTextColor = $this->settings->get('listGroupItemTextColor', '#0b0c0c');
        $this->listGroupItemHoverTextColor = $this->settings->get('listGroupItemHoverTextColor', '#0b0c0c');
        $this->listGroupItemActiveTextColor = $this->settings->get('listGroupItemActiveTextColor', '#ffffff');
        $this->listGroupItemBackgroundColor = $this->settings->get('listGroupItemBackgroundColor', '#ffffff');
        $this->listGroupItemHoverBackgroundColor = $this->settings->get('listGroupItemHoverBackgroundColor', '#f3f2f1');
        $this->listGroupItemActiveBackgroundColor = $this->settings->get('listGroupItemActiveBackgroundColor', '#1d70b8');
        $this->listGroupItemBorderColor = $this->settings->get('listGroupItemBorderColor', '#d8d8d8');
        $this->listGroupItemPaddingY = (int)$this->settings->get('listGroupItemPaddingY', 10);
        $this->listGroupItemPaddingX = (int)$this->settings->get('listGroupItemPaddingX', 14);
        $this->listGroupItemBorderRadius = (int)$this->settings->get('listGroupItemBorderRadius', 4);
        $this->sidebarBackgroundColor = $this->settings->get('sidebarBackgroundColor', '#f8f8f8');
        $this->sidebarTextColor = $this->settings->get('sidebarTextColor', '#0b0c0c');
        $this->sideMenuItemTextColor = $this->settings->get('sideMenuItemTextColor', '#0b0c0c');
        $this->sideMenuItemHoverTextColor = $this->settings->get('sideMenuItemHoverTextColor', '#0b0c0c');
        $this->sideMenuItemActiveTextColor = $this->settings->get('sideMenuItemActiveTextColor', '#ffffff');
        $this->sideMenuItemHoverBackgroundColor = $this->settings->get('sideMenuItemHoverBackgroundColor', '#e5e5e5');
        $this->sideMenuItemActiveBackgroundColor = $this->settings->get('sideMenuItemActiveBackgroundColor', '#1d70b8');
        $this->sideMenuItemPaddingY = (int)$this->settings->get('sideMenuItemPaddingY', 8);
        $this->sideMenuItemPaddingX = (int)$this->settings->get('sideMenuItemPaddingX', 12);
        $this->sideMenuItemBorderRadius = (int)$this->settings->get('sideMenuItemBorderRadius', 4);
        $this->footerBackgroundColor = $this->settings->get('footerBackgroundColor', '#f3f2f1');
        $this->footerTextColor = $this->settings->get('footerTextColor', '#0b0c0c');
        $this->mobileMenuStyle = $this->settings->get('mobileMenuStyle', self::MOBILE_MENU_STYLE_HAMBURGER);
        $this->bottomMenuBackgroundColor = $this->settings->get('bottomMenuBackgroundColor', '#0b0c0c');
        $this->bottomMenuTextColor = $this->settings->get('bottomMenuTextColor', '#ffffff');
        $this->floatingMenuBackgroundColor = $this->settings->get('floatingMenuBackgroundColor', '#ffffff');
        $this->floatingMenuTextColor = $this->settings->get('floatingMenuTextColor', '#262626');
        $this->floatingMenuActiveColor = $this->settings->get('floatingMenuActiveColor', '#1d70b8');
        $this->floatingMenuBackgroundOpacity = (int)$this->settings->get('floatingMenuBackgroundOpacity', 92);
        $this->hideFloatingMenuItemLabels = (bool)$this->settings->get('hideFloatingMenuItemLabels', false);
        $this->hideFloatingMenuOnScrollDown = (bool)$this->settings->get('hideFloatingMenuOnScrollDown', false);
        $this->hideTextInBottomMenuItems = (bool)$this->settings->get('hideTextInBottomMenuItems', false);
        $this->mobileMenuBackgroundColor = $this->settings->get('mobileMenuBackgroundColor', '#0b0c0c');
        $this->mobileMenuTextColor = $this->settings->get('mobileMenuTextColor', '#ffffff');
        $this->mobileMenuFontSize = (int)$this->settings->get('mobileMenuFontSize', 16);
        $this->mobileMenuHighlightActive = (bool)$this->settings->get('mobileMenuHighlightActive', false);
        $this->mobileMenuItemPaddingX = (int)$this->settings->get('mobileMenuItemPaddingX', 16);
        $this->mobileMenuItemPaddingY = (int)$this->settings->get('mobileMenuItemPaddingY', 14);
        $this->mobileContentPaddingX = (int)$this->settings->get('mobileContentPaddingX', 12);
        $this->mobileContentPaddingY = (int)$this->settings->get('mobileContentPaddingY', 8);
        $this->mobileContentGutter = (int)$this->settings->get('mobileContentGutter', 12);
        $this->mobilePanelSpacing = (int)$this->settings->get('mobilePanelSpacing', 12);
        $this->mobilePanelBodyPadding = (int)$this->settings->get('mobilePanelBodyPadding', 16);
        $this->mobileTopbarPaddingX = (int)$this->settings->get('mobileTopbarPaddingX', 12);
        $this->dangerBadgeBackgroundColor = $this->settings->get('dangerBadgeBackgroundColor', '#d4351c');
        $this->dangerBadgeTextColor = $this->settings->get('dangerBadgeTextColor', '#ffffff');
        $this->buttonBackgroundColor = $this->settings->get('buttonBackgroundColor', '#1d70b8');
        $this->buttonTextColor = $this->settings->get('buttonTextColor', '#ffffff');
        $this->buttonBorderColor = $this->settings->get('buttonBorderColor', '#1d70b8');
        $this->buttonHoverBackgroundColor = $this->settings->get('buttonHoverBackgroundColor', '#003078');
        $this->buttonHoverTextColor = $this->settings->get('buttonHoverTextColor', '#ffffff');
        $this->buttonSecondaryBackgroundColor = $this->settings->get('buttonSecondaryBackgroundColor', '#f3f2f1');
        $this->buttonSecondaryTextColor = $this->settings->get('buttonSecondaryTextColor', '#0b0c0c');
        $this->buttonSecondaryBorderColor = $this->settings->get('buttonSecondaryBorderColor', '#b1b4b6');
        $this->buttonSecondaryHoverBackgroundColor = $this->settings->get('buttonSecondaryHoverBackgroundColor', '#e5e5e5');
        $this->buttonSecondaryHoverTextColor = $this->settings->get('buttonSecondaryHoverTextColor', '#0b0c0c');
        $this->accordionBorderColor = $this->settings->get('accordionBorderColor', '#e4eaec');
        $this->accordionBorderRadius = (int)$this->settings->get('accordionBorderRadius', 4);
        $this->accordionMarginBottom = (int)$this->settings->get('accordionMarginBottom', 12);
        $this->accordionBackgroundColor = $this->settings->get('accordionBackgroundColor', '#ffffff');
        $this->accordionBoxShadow = $this->settings->get('accordionBoxShadow', '0 1px 10px rgba(0,0,0,0.1)');
        $this->accordionHeaderBackgroundColor = $this->settings->get('accordionHeaderBackgroundColor', '#f7f7f7');
        $this->accordionHeaderTextColor = $this->settings->get('accordionHeaderTextColor', '#3a3c42');
        $this->accordionHeaderFontSize = (int)$this->settings->get('accordionHeaderFontSize', 18);
        $this->accordionHeaderFontWeight = (int)$this->settings->get('accordionHeaderFontWeight', 600);
        $this->accordionHeaderPaddingY = (int)$this->settings->get('accordionHeaderPaddingY', 18);
        $this->accordionHeaderPaddingX = (int)$this->settings->get('accordionHeaderPaddingX', 20);
        $this->accordionHeaderHoverBackgroundColor = $this->settings->get('accordionHeaderHoverBackgroundColor', '#f3a5b9');
        $this->accordionHeaderHoverTextColor = $this->settings->get('accordionHeaderHoverTextColor', '#000000');
        $this->accordionHeaderOpenBackgroundColor = $this->settings->get('accordionHeaderOpenBackgroundColor', '#dd0031');
        $this->accordionHeaderOpenTextColor = $this->settings->get('accordionHeaderOpenTextColor', '#ffffff');
        $this->accordionContentTextColor = $this->settings->get('accordionContentTextColor', '#3a3c42');
        $this->accordionContentFontSize = (int)$this->settings->get('accordionContentFontSize', 16);
        $this->accordionContentPadding = (int)$this->settings->get('accordionContentPadding', 20);
        $this->accordionFaqTitleColor = $this->settings->get('accordionFaqTitleColor', '#dd0031');
        $this->accordionFaqTitleFontSize = (int)$this->settings->get('accordionFaqTitleFontSize', 38);
        $this->accordionEnableAnimation = (bool)$this->settings->get('accordionEnableAnimation', true);
        $this->loadCustomCssRulesFromSettings();
    }

    public static function normalizeHexColor(?string $value): string
    {
        $value = trim((string)$value);
        if ($value === '') {
            return '';
        }

        if (!str_starts_with($value, '#')) {
            $value = '#' . $value;
        }

        if (preg_match('/^#([A-Fa-f0-9]{3})$/', $value, $matches)) {
            $hex = $matches[1];
            $value = '#' . $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return strtolower($value);
    }

    public static function toPickerHex(?string $value): string
    {
        $normalized = self::normalizeHexColor($value);
        if (!preg_match('/^#[a-f0-9]{6}$/', $normalized)) {
            return '#000000';
        }

        return $normalized;
    }

    public static function normalizeLetterSpacing(?string $value): string
    {
        $value = trim(strtolower((string)$value));
        if ($value === '' || $value === 'normal') {
            return 'normal';
        }

        if (preg_match('/^-?[0-9]+(\.[0-9]+)?$/', $value)) {
            return $value . 'em';
        }

        return $value;
    }

    public function beforeValidate(): bool
    {
        foreach (self::COLOR_ATTRIBUTES as $attribute) {
            $this->$attribute = self::normalizeHexColor($this->$attribute);
        }

        $this->topMenuLetterSpacing = self::normalizeLetterSpacing($this->topMenuLetterSpacing);

        return parent::beforeValidate();
    }

    public static function getExportableAttributeNames(): array
    {
        return self::EXPORTABLE_ATTRIBUTES;
    }

    public function toExportArray(): array
    {
        $settings = [];
        foreach (self::EXPORTABLE_ATTRIBUTES as $attribute) {
            $settings[$attribute] = $this->$attribute;
        }

        return [
            'format' => self::EXPORT_FORMAT,
            'version' => self::EXPORT_VERSION,
            'moduleVersion' => Module::VERSION,
            'exportedAt' => gmdate('c'),
            'settings' => $settings,
        ];
    }

    public function applyImportData(array $data): bool
    {
        $settings = $data;
        if (isset($data['settings']) && is_array($data['settings'])) {
            $settings = $data['settings'];
        }

        if (!is_array($settings)) {
            $this->addError('customCssRules', Yii::t('ThiscoveryThemeModule.base', 'The import file does not contain valid theme settings.'));
            return false;
        }

        if (isset($data['format']) && $data['format'] !== self::EXPORT_FORMAT) {
            $this->addError('customCssRules', Yii::t('ThiscoveryThemeModule.base', 'This file is not a Thiscovery Theme configuration export.'));
            return false;
        }

        foreach (self::EXPORTABLE_ATTRIBUTES as $attribute) {
            if (!array_key_exists($attribute, $settings)) {
                continue;
            }

            if ($attribute === 'customCssRules') {
                $this->customCssRules = $this->normalizeCustomCssRules($settings[$attribute]);
                continue;
            }

            $this->$attribute = $settings[$attribute];
        }

        return $this->validate();
    }

    public function loadCustomCssRulesFromSettings(): void
    {
        $storedCustomCssRules = $this->settings->getSerialized('customCssRules', []);
        if ($storedCustomCssRules === [] || $storedCustomCssRules === null) {
            $uncached = $this->settings->getUncached('customCssRules');
            if (is_string($uncached) && $uncached !== '') {
                $storedCustomCssRules = $uncached;
            }
        }

        $this->customCssRules = $this->normalizeCustomCssRules($storedCustomCssRules);
    }

    public function rules(): array
    {
        return [
            [[
                'themePrimaryColor',
                'themeAccentColor',
                'themeSecondaryColor',
                'themeSuccessColor',
                'themeDangerColor',
                'themeWarningColor',
                'themeInfoColor',
                'themeLightColor',
                'themeDarkColor',
            ], 'required'],
            [[
                'topBarHeight',
                'topBarFontSize',
                'containerMaxWidth',
                'fontSize',
                'fontWeight',
                'fontBoldWeight',
                'h1FontSize',
                'h2FontSize',
                'h3FontSize',
                'h4FontSize',
                'h5FontSize',
                'h6FontSize',
                'panelBorderRadius',
                'buttonBorderRadius',
                'menuItemPaddingX',
                'menuItemPaddingY',
                'buttonPaddingX',
                'buttonPaddingY',
                'buttonFontWeight',
                'inputBorderRadius',
                'cardPadding',
                'sideMenuItemPaddingY',
                'sideMenuItemPaddingX',
                'sideMenuItemBorderRadius',
                'listGroupItemPaddingY',
                'listGroupItemPaddingX',
                'listGroupItemBorderRadius',
                'topMenuFontWeight',
            ], 'required'],
            [['topMenuTextTransform', 'topMenuLetterSpacing', 'topMenuFontStyle'], 'required'],
            [['topBarHeight'], 'integer', 'min' => 50, 'max' => 140],
            [['topBarFontSize'], 'integer', 'min' => 8, 'max' => 20],
            [['containerMaxWidth'], 'integer', 'min' => 800, 'max' => 2600],
            [['fontSize'], 'integer', 'min' => 12, 'max' => 24],
            [['fontWeight', 'fontBoldWeight'], 'integer', 'min' => 100, 'max' => 900],
            [['h1FontSize', 'h2FontSize', 'h3FontSize', 'h4FontSize', 'h5FontSize', 'h6FontSize'], 'integer', 'min' => 12, 'max' => 72],
            [['panelBorderRadius', 'buttonBorderRadius'], 'integer', 'min' => 0, 'max' => 30],
            [['menuItemPaddingX', 'menuItemPaddingY', 'buttonPaddingX', 'buttonPaddingY', 'inputBorderRadius'], 'integer', 'min' => 0, 'max' => 40],
            [['cardPadding'], 'integer', 'min' => 0, 'max' => 60],
            [['sideMenuItemPaddingY', 'sideMenuItemPaddingX'], 'integer', 'min' => 0, 'max' => 30],
            [['sideMenuItemBorderRadius'], 'integer', 'min' => 0, 'max' => 20],
            [['listGroupItemPaddingY', 'listGroupItemPaddingX'], 'integer', 'min' => 0, 'max' => 40],
            [['listGroupItemBorderRadius'], 'integer', 'min' => 0, 'max' => 20],
            [['buttonFontWeight'], 'integer', 'min' => 100, 'max' => 900],
            [['topMenuFontWeight'], 'integer', 'min' => 100, 'max' => 900],
            [['topMenuNavJustifyContent'], 'in', 'range' => array_keys(self::getJustifyContentOptions())],
            [['mobileMenuStyle'], 'in', 'range' => array_keys(self::getMobileMenuStyleOptions())],
            [['floatingMenuBackgroundOpacity'], 'integer', 'min' => 0, 'max' => 100],
            [['hideFloatingMenuItemLabels', 'hideFloatingMenuOnScrollDown', 'hideTextInBottomMenuItems', 'mobileMenuHighlightActive'], 'boolean'],
            [['mobileMenuFontSize'], 'integer', 'min' => 12, 'max' => 24],
            [['mobileMenuItemPaddingX', 'mobileMenuItemPaddingY'], 'integer', 'min' => 0, 'max' => 40],
            [['mobileContentPaddingX', 'mobileContentPaddingY', 'mobileContentGutter', 'mobilePanelSpacing', 'mobilePanelBodyPadding', 'mobileTopbarPaddingX'], 'integer', 'min' => 0, 'max' => 40],
            [['topMenuTextTransform'], 'in', 'range' => array_keys(self::getTopMenuTextTransformOptions())],
            [['topMenuFontStyle'], 'in', 'range' => array_keys(self::getTopMenuFontStyleOptions())],
            [['topMenuLetterSpacing'], 'match', 'pattern' => '/^(normal|-?[0-9]+(\.[0-9]+)?(em|px|rem)?)$/i', 'message' => Yii::t('ThiscoveryThemeModule.base', 'Use normal, or a value such as 0.03em or 1px.')],
            [['fontFamily', 'headingFontFamily', 'panelBoxShadow', 'accordionBoxShadow'], 'string', 'max' => 255],
            [['accordionBorderRadius', 'accordionMarginBottom', 'accordionHeaderFontSize', 'accordionHeaderPaddingY', 'accordionHeaderPaddingX', 'accordionContentFontSize', 'accordionContentPadding', 'accordionFaqTitleFontSize'], 'integer', 'min' => 0, 'max' => 100],
            [['accordionHeaderFontWeight'], 'integer', 'min' => 100, 'max' => 900],
            [['accordionEnableAnimation'], 'boolean'],
            ['customCssRules', 'safe'],
            ['customCssRules', 'validateCustomCssRules'],
            [self::COLOR_ATTRIBUTES, 'match', 'pattern' => '/^#[a-f0-9]{6}$/', 'message' => Yii::t('ThiscoveryThemeModule.base', 'Please enter a hex colour such as #f7f7f7 or f7f7f7.')],
        ];
    }

    public function validateCustomCssRules(): void
    {
        foreach ($this->customCssRules as $idx => $rule) {
            $selector = trim((string)($rule['selector'] ?? ''));
            $declarations = trim((string)($rule['declarations'] ?? ''));
            $description = trim((string)($rule['description'] ?? ''));

            if ($selector === '' && $declarations === '' && $description === '') {
                continue;
            }

            if ($selector === '' || $declarations === '') {
                $this->addError('customCssRules', Yii::t('ThiscoveryThemeModule.base', 'Custom CSS rule #{n} must include both selector and CSS declarations.', ['n' => $idx + 1]));
                continue;
            }

            if (str_contains($selector, '{') || str_contains($selector, '}')) {
                $this->addError('customCssRules', Yii::t('ThiscoveryThemeModule.base', 'Custom CSS selector #{n} must not include curly braces.', ['n' => $idx + 1]));
                continue;
            }

            if (preg_match('/^\s*[a-z][a-z0-9-]*\s+[a-z]/i', $declarations)) {
                $this->addError(
                    'customCssRules',
                    Yii::t(
                        'ThiscoveryThemeModule.base',
                        'Custom CSS rule #{n} looks invalid. CSS property names must use hyphens, for example background-color (not "background color").',
                        ['n' => $idx + 1],
                    ),
                );
                continue;
            }

            try {
                (new Compiler())->compileString($this->renderCustomCssRulesScssFromRules([$rule]));
            } catch (SassException $e) {
                $message = trim(preg_replace('/\s+/', ' ', $e->getMessage()) ?? $e->getMessage());
                $this->addError(
                    'customCssRules',
                    Yii::t(
                        'ThiscoveryThemeModule.base',
                        'Custom CSS rule #{n} could not be compiled: {error}',
                        ['n' => $idx + 1, 'error' => $message],
                    ),
                );
            }
        }
    }

    public function load($data, $formName = null): bool
    {
        $loaded = parent::load($data, $formName);

        $selectors = null;
        $declarations = null;
        $resolvedFormName = $formName ?? $this->formName();
        $formData = is_array($data[$resolvedFormName] ?? null) ? $data[$resolvedFormName] : [];

        if (isset($data['customCssSelector']) || isset($data['customCssDeclarations']) || isset($data['customCssDescription'])) {
            $selectors = $data['customCssSelector'] ?? [];
            $declarations = $data['customCssDeclarations'] ?? [];
            $descriptions = $data['customCssDescription'] ?? [];
        } elseif (isset($formData['customCssSelector']) || isset($formData['customCssDeclarations']) || isset($formData['customCssDescription'])) {
            $selectors = $formData['customCssSelector'] ?? [];
            $declarations = $formData['customCssDeclarations'] ?? [];
            $descriptions = $formData['customCssDescription'] ?? [];
        }

        if ($selectors === null && $declarations === null && !isset($descriptions)) {
            return $loaded;
        }

        $selectors = is_array($selectors) ? $selectors : [];
        $declarations = is_array($declarations) ? $declarations : [];
        $descriptions = is_array($descriptions ?? null) ? $descriptions : [];
        $max = max(count($selectors), count($declarations), count($descriptions));
        $this->customCssRules = [];
        for ($i = 0; $i < $max; $i++) {
            $selector = trim((string)($selectors[$i] ?? ''));
            $css = trim((string)($declarations[$i] ?? ''));
            $description = trim((string)($descriptions[$i] ?? ''));
            if ($selector === '' && $css === '' && $description === '') {
                continue;
            }
            $this->customCssRules[] = [
                'description' => $description,
                'selector' => $selector,
                'declarations' => $css,
            ];
        }

        return $loaded;
    }

    public static function getJustifyContentOptions(): array
    {
        return [
            'flex-start' => Yii::t('ThiscoveryThemeModule.base', 'Items grouped on the left'),
            'center' => Yii::t('ThiscoveryThemeModule.base', 'Items centered'),
            'flex-end' => Yii::t('ThiscoveryThemeModule.base', 'Items grouped on the right'),
            'space-between' => Yii::t('ThiscoveryThemeModule.base', 'Items distributed evenly'),
            'space-around' => Yii::t('ThiscoveryThemeModule.base', 'Items distributed with equal spacing'),
        ];
    }

    public static function getTopMenuTextTransformOptions(): array
    {
        return [
            'none' => Yii::t('ThiscoveryThemeModule.base', 'As written (sentence case)'),
            'uppercase' => Yii::t('ThiscoveryThemeModule.base', 'ALL CAPS'),
            'lowercase' => Yii::t('ThiscoveryThemeModule.base', 'All lowercase'),
            'capitalize' => Yii::t('ThiscoveryThemeModule.base', 'Title Case'),
        ];
    }

    public static function getTopMenuFontWeightOptions(): array
    {
        return [
            300 => Yii::t('ThiscoveryThemeModule.base', 'Light'),
            400 => Yii::t('ThiscoveryThemeModule.base', 'Normal'),
            500 => Yii::t('ThiscoveryThemeModule.base', 'Medium'),
            600 => Yii::t('ThiscoveryThemeModule.base', 'Semi-bold'),
            700 => Yii::t('ThiscoveryThemeModule.base', 'Bold'),
            800 => Yii::t('ThiscoveryThemeModule.base', 'Extra bold'),
        ];
    }

    public static function getTopMenuFontStyleOptions(): array
    {
        return [
            'normal' => Yii::t('ThiscoveryThemeModule.base', 'Normal'),
            'italic' => Yii::t('ThiscoveryThemeModule.base', 'Italic'),
        ];
    }

    public static function getMobileMenuStyleOptions(): array
    {
        return [
            self::MOBILE_MENU_STYLE_HAMBURGER => Yii::t('ThiscoveryThemeModule.base', 'Top hamburger menu'),
            self::MOBILE_MENU_STYLE_BOTTOM_BAR => Yii::t('ThiscoveryThemeModule.base', 'Clean Theme bottom navigation bar'),
            self::MOBILE_MENU_STYLE_FLOATING_BAR => Yii::t('ThiscoveryThemeModule.base', 'Floating bottom navigation'),
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'themePrimaryColor' => Yii::t('ThiscoveryThemeModule.base', 'Primary color'),
            'themeAccentColor' => Yii::t('ThiscoveryThemeModule.base', 'Accent color'),
            'themeSecondaryColor' => Yii::t('ThiscoveryThemeModule.base', 'Secondary color'),
            'themeSuccessColor' => Yii::t('ThiscoveryThemeModule.base', 'Success color'),
            'themeDangerColor' => Yii::t('ThiscoveryThemeModule.base', 'Danger color'),
            'themeWarningColor' => Yii::t('ThiscoveryThemeModule.base', 'Warning color'),
            'themeInfoColor' => Yii::t('ThiscoveryThemeModule.base', 'Info color'),
            'themeLightColor' => Yii::t('ThiscoveryThemeModule.base', 'Light color'),
            'themeDarkColor' => Yii::t('ThiscoveryThemeModule.base', 'Dark color'),
            'topBarHeight' => Yii::t('ThiscoveryThemeModule.base', 'Top header height'),
            'topBarFontSize' => Yii::t('ThiscoveryThemeModule.base', 'Top header font size'),
            'topMenuNavJustifyContent' => Yii::t('ThiscoveryThemeModule.base', 'Top navigation alignment'),
            'topMenuBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Top header background color'),
            'topMenuTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Top header text color'),
            'topMenuButtonHoverBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Top button hover background color'),
            'topMenuButtonHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Top button hover text color'),
            'topMenuTextTransform' => Yii::t('ThiscoveryThemeModule.base', 'Top menu text case'),
            'topMenuFontWeight' => Yii::t('ThiscoveryThemeModule.base', 'Top menu font weight'),
            'topMenuLetterSpacing' => Yii::t('ThiscoveryThemeModule.base', 'Top menu letter spacing'),
            'topMenuFontStyle' => Yii::t('ThiscoveryThemeModule.base', 'Top menu font style'),
            'containerMaxWidth' => Yii::t('ThiscoveryThemeModule.base', 'Main content container width'),
            'fontFamily' => Yii::t('ThiscoveryThemeModule.base', 'Base font family'),
            'headingFontFamily' => Yii::t('ThiscoveryThemeModule.base', 'Heading font family'),
            'fontSize' => Yii::t('ThiscoveryThemeModule.base', 'Base font size'),
            'fontWeight' => Yii::t('ThiscoveryThemeModule.base', 'Base font weight'),
            'fontBoldWeight' => Yii::t('ThiscoveryThemeModule.base', 'Bold font weight'),
            'h1FontSize' => Yii::t('ThiscoveryThemeModule.base', 'H1 font size'),
            'h2FontSize' => Yii::t('ThiscoveryThemeModule.base', 'H2 font size'),
            'h3FontSize' => Yii::t('ThiscoveryThemeModule.base', 'H3 font size'),
            'h4FontSize' => Yii::t('ThiscoveryThemeModule.base', 'H4 font size'),
            'h5FontSize' => Yii::t('ThiscoveryThemeModule.base', 'H5 font size'),
            'h6FontSize' => Yii::t('ThiscoveryThemeModule.base', 'H6 font size'),
            'linkColor' => Yii::t('ThiscoveryThemeModule.base', 'Link color'),
            'textColorMain' => Yii::t('ThiscoveryThemeModule.base', 'Main text color'),
            'textColorSecondary' => Yii::t('ThiscoveryThemeModule.base', 'Secondary text color'),
            'backgroundColorMain' => Yii::t('ThiscoveryThemeModule.base', 'Main background color'),
            'backgroundColorPage' => Yii::t('ThiscoveryThemeModule.base', 'Page background color'),
            'panelBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'Panel border color'),
            'panelBorderRadius' => Yii::t('ThiscoveryThemeModule.base', 'Panel border radius'),
            'panelBoxShadow' => Yii::t('ThiscoveryThemeModule.base', 'Panel shadow'),
            'buttonBorderRadius' => Yii::t('ThiscoveryThemeModule.base', 'Button border radius'),
            'menuItemTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Menu item text color'),
            'menuItemHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Menu item hover text color'),
            'menuItemActiveTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Menu item active text color'),
            'menuItemActiveBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Menu item active background color'),
            'menuItemPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Menu item horizontal padding'),
            'menuItemPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'Menu item vertical padding'),
            'buttonPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Button horizontal padding'),
            'buttonPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'Button vertical padding'),
            'buttonFontWeight' => Yii::t('ThiscoveryThemeModule.base', 'Button font weight'),
            'inputBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Input background color'),
            'inputTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Input text color'),
            'inputBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'Input border color'),
            'inputBorderRadius' => Yii::t('ThiscoveryThemeModule.base', 'Input border radius'),
            'cardBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Card background color'),
            'cardTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Card text color'),
            'cardPadding' => Yii::t('ThiscoveryThemeModule.base', 'Card padding'),
            'tableHeaderBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Table header background color'),
            'tableHeaderTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Table header text color'),
            'tableRowBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'Table row border color'),
            'listGroupItemTextColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item text color'),
            'listGroupItemHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item hover text color'),
            'listGroupItemActiveTextColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item active text color'),
            'listGroupItemBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item background color'),
            'listGroupItemHoverBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item hover background color'),
            'listGroupItemActiveBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item active background color'),
            'listGroupItemBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'List group item border color'),
            'listGroupItemPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'List group item vertical padding'),
            'listGroupItemPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'List group item horizontal padding'),
            'listGroupItemBorderRadius' => Yii::t('ThiscoveryThemeModule.base', 'List group item border radius'),
            'sidebarBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Sidebar background color'),
            'sidebarTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Sidebar text color'),
            'sideMenuItemTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item text color'),
            'sideMenuItemHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item hover text color'),
            'sideMenuItemActiveTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item active text color'),
            'sideMenuItemHoverBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item hover background color'),
            'sideMenuItemActiveBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item active background color'),
            'sideMenuItemPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item vertical padding'),
            'sideMenuItemPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item horizontal padding'),
            'sideMenuItemBorderRadius' => Yii::t('ThiscoveryThemeModule.base', 'Side menu item border radius'),
            'footerBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Footer background color'),
            'footerTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Footer text color'),
            'mobileMenuStyle' => Yii::t('ThiscoveryThemeModule.base', 'Mobile navigation style'),
            'bottomMenuBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Bottom menu background color'),
            'bottomMenuTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Bottom menu text color'),
            'floatingMenuBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Floating bar background color'),
            'floatingMenuBackgroundOpacity' => Yii::t('ThiscoveryThemeModule.base', 'Floating bar background opacity (0–100)'),
            'floatingMenuTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Floating bar text color'),
            'floatingMenuActiveColor' => Yii::t('ThiscoveryThemeModule.base', 'Floating bar active item color'),
            'hideFloatingMenuItemLabels' => Yii::t('ThiscoveryThemeModule.base', 'Hide floating bar item labels (icons only)'),
            'hideFloatingMenuOnScrollDown' => Yii::t('ThiscoveryThemeModule.base', 'Hide floating bar on scroll down'),
            'hideTextInBottomMenuItems' => Yii::t('ThiscoveryThemeModule.base', 'Hide Clean Theme bottom bar item labels (icons only)'),
            'mobileMenuBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Hamburger panel background color'),
            'mobileMenuTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Hamburger panel text color'),
            'mobileMenuFontSize' => Yii::t('ThiscoveryThemeModule.base', 'Hamburger menu font size'),
            'mobileMenuHighlightActive' => Yii::t('ThiscoveryThemeModule.base', 'Highlight active item in hamburger menu'),
            'mobileMenuItemPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Hamburger item horizontal padding'),
            'mobileMenuItemPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'Hamburger item vertical padding'),
            'mobileContentPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Mobile content horizontal padding'),
            'mobileContentPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'Mobile content top padding'),
            'mobileContentGutter' => Yii::t('ThiscoveryThemeModule.base', 'Mobile column gutter'),
            'mobilePanelSpacing' => Yii::t('ThiscoveryThemeModule.base', 'Mobile spacing between panels'),
            'mobilePanelBodyPadding' => Yii::t('ThiscoveryThemeModule.base', 'Mobile panel inner padding'),
            'mobileTopbarPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Mobile top bar horizontal padding'),
            'dangerBadgeBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Danger badge background color'),
            'dangerBadgeTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Danger badge text color'),
            'buttonBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Primary button background color'),
            'buttonTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Primary button text color'),
            'buttonBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'Primary button border color'),
            'buttonHoverBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Primary button hover background color'),
            'buttonHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Primary button hover text color'),
            'buttonSecondaryBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Secondary button background color'),
            'buttonSecondaryTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Secondary button text color'),
            'buttonSecondaryBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'Secondary button border color'),
            'buttonSecondaryHoverBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Secondary button hover background color'),
            'buttonSecondaryHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Secondary button hover text color'),
            'accordionBorderColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion border color'),
            'accordionBorderRadius' => Yii::t('ThiscoveryThemeModule.base', 'Accordion border radius'),
            'accordionMarginBottom' => Yii::t('ThiscoveryThemeModule.base', 'Accordion spacing below'),
            'accordionBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion background color'),
            'accordionBoxShadow' => Yii::t('ThiscoveryThemeModule.base', 'Accordion shadow'),
            'accordionHeaderBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header background color'),
            'accordionHeaderTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header text color'),
            'accordionHeaderFontSize' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header font size'),
            'accordionHeaderFontWeight' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header font weight'),
            'accordionHeaderPaddingY' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header vertical padding'),
            'accordionHeaderPaddingX' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header horizontal padding'),
            'accordionHeaderHoverBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header hover background color'),
            'accordionHeaderHoverTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion header hover text color'),
            'accordionHeaderOpenBackgroundColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion open header background color'),
            'accordionHeaderOpenTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion open header text color'),
            'accordionContentTextColor' => Yii::t('ThiscoveryThemeModule.base', 'Accordion content text color'),
            'accordionContentFontSize' => Yii::t('ThiscoveryThemeModule.base', 'Accordion content font size'),
            'accordionContentPadding' => Yii::t('ThiscoveryThemeModule.base', 'Accordion content padding'),
            'accordionFaqTitleColor' => Yii::t('ThiscoveryThemeModule.base', 'FAQ section title color'),
            'accordionFaqTitleFontSize' => Yii::t('ThiscoveryThemeModule.base', 'FAQ section title font size'),
            'accordionEnableAnimation' => Yii::t('ThiscoveryThemeModule.base', 'Animate accordion when opening'),
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->settings->set('themePrimaryColor', $this->themePrimaryColor);
        $this->settings->set('themeAccentColor', $this->themeAccentColor);
        $this->settings->set('themeSecondaryColor', $this->themeSecondaryColor);
        $this->settings->set('themeSuccessColor', $this->themeSuccessColor);
        $this->settings->set('themeDangerColor', $this->themeDangerColor);
        $this->settings->set('themeWarningColor', $this->themeWarningColor);
        $this->settings->set('themeInfoColor', $this->themeInfoColor);
        $this->settings->set('themeLightColor', $this->themeLightColor);
        $this->settings->set('themeDarkColor', $this->themeDarkColor);
        $this->settings->set('topBarHeight', $this->topBarHeight);
        $this->settings->set('topBarFontSize', $this->topBarFontSize);
        $this->settings->set('topMenuNavJustifyContent', $this->topMenuNavJustifyContent);
        $this->settings->set('topMenuBackgroundColor', $this->topMenuBackgroundColor);
        $this->settings->set('topMenuTextColor', $this->topMenuTextColor);
        $this->settings->set('topMenuButtonHoverBackgroundColor', $this->topMenuButtonHoverBackgroundColor);
        $this->settings->set('topMenuButtonHoverTextColor', $this->topMenuButtonHoverTextColor);
        $this->settings->set('topMenuTextTransform', $this->topMenuTextTransform);
        $this->settings->set('topMenuFontWeight', $this->topMenuFontWeight);
        $this->settings->set('topMenuLetterSpacing', $this->topMenuLetterSpacing);
        $this->settings->set('topMenuFontStyle', $this->topMenuFontStyle);
        $this->settings->set('containerMaxWidth', $this->containerMaxWidth);
        $this->settings->set('fontFamily', $this->fontFamily);
        $this->settings->set('headingFontFamily', $this->headingFontFamily);
        $this->settings->set('fontSize', $this->fontSize);
        $this->settings->set('fontWeight', $this->fontWeight);
        $this->settings->set('fontBoldWeight', $this->fontBoldWeight);
        $this->settings->set('h1FontSize', $this->h1FontSize);
        $this->settings->set('h2FontSize', $this->h2FontSize);
        $this->settings->set('h3FontSize', $this->h3FontSize);
        $this->settings->set('h4FontSize', $this->h4FontSize);
        $this->settings->set('h5FontSize', $this->h5FontSize);
        $this->settings->set('h6FontSize', $this->h6FontSize);
        $this->settings->set('linkColor', $this->linkColor);
        $this->settings->set('textColorMain', $this->textColorMain);
        $this->settings->set('textColorSecondary', $this->textColorSecondary);
        $this->settings->set('backgroundColorMain', $this->backgroundColorMain);
        $this->settings->set('backgroundColorPage', $this->backgroundColorPage);
        $this->settings->set('panelBorderColor', $this->panelBorderColor);
        $this->settings->set('panelBorderRadius', $this->panelBorderRadius);
        $this->settings->set('panelBoxShadow', $this->panelBoxShadow);
        $this->settings->set('buttonBorderRadius', $this->buttonBorderRadius);
        $this->settings->set('menuItemTextColor', $this->menuItemTextColor);
        $this->settings->set('menuItemHoverTextColor', $this->menuItemHoverTextColor);
        $this->settings->set('menuItemActiveTextColor', $this->menuItemActiveTextColor);
        $this->settings->set('menuItemActiveBackgroundColor', $this->menuItemActiveBackgroundColor);
        $this->settings->set('menuItemPaddingX', $this->menuItemPaddingX);
        $this->settings->set('menuItemPaddingY', $this->menuItemPaddingY);
        $this->settings->set('buttonPaddingX', $this->buttonPaddingX);
        $this->settings->set('buttonPaddingY', $this->buttonPaddingY);
        $this->settings->set('buttonFontWeight', $this->buttonFontWeight);
        $this->settings->set('inputBackgroundColor', $this->inputBackgroundColor);
        $this->settings->set('inputTextColor', $this->inputTextColor);
        $this->settings->set('inputBorderColor', $this->inputBorderColor);
        $this->settings->set('inputBorderRadius', $this->inputBorderRadius);
        $this->settings->set('cardBackgroundColor', $this->cardBackgroundColor);
        $this->settings->set('cardTextColor', $this->cardTextColor);
        $this->settings->set('cardPadding', $this->cardPadding);
        $this->settings->set('tableHeaderBackgroundColor', $this->tableHeaderBackgroundColor);
        $this->settings->set('tableHeaderTextColor', $this->tableHeaderTextColor);
        $this->settings->set('tableRowBorderColor', $this->tableRowBorderColor);
        $this->settings->set('listGroupItemTextColor', $this->listGroupItemTextColor);
        $this->settings->set('listGroupItemHoverTextColor', $this->listGroupItemHoverTextColor);
        $this->settings->set('listGroupItemActiveTextColor', $this->listGroupItemActiveTextColor);
        $this->settings->set('listGroupItemBackgroundColor', $this->listGroupItemBackgroundColor);
        $this->settings->set('listGroupItemHoverBackgroundColor', $this->listGroupItemHoverBackgroundColor);
        $this->settings->set('listGroupItemActiveBackgroundColor', $this->listGroupItemActiveBackgroundColor);
        $this->settings->set('listGroupItemBorderColor', $this->listGroupItemBorderColor);
        $this->settings->set('listGroupItemPaddingY', $this->listGroupItemPaddingY);
        $this->settings->set('listGroupItemPaddingX', $this->listGroupItemPaddingX);
        $this->settings->set('listGroupItemBorderRadius', $this->listGroupItemBorderRadius);
        $this->settings->set('sidebarBackgroundColor', $this->sidebarBackgroundColor);
        $this->settings->set('sidebarTextColor', $this->sidebarTextColor);
        $this->settings->set('sideMenuItemTextColor', $this->sideMenuItemTextColor);
        $this->settings->set('sideMenuItemHoverTextColor', $this->sideMenuItemHoverTextColor);
        $this->settings->set('sideMenuItemActiveTextColor', $this->sideMenuItemActiveTextColor);
        $this->settings->set('sideMenuItemHoverBackgroundColor', $this->sideMenuItemHoverBackgroundColor);
        $this->settings->set('sideMenuItemActiveBackgroundColor', $this->sideMenuItemActiveBackgroundColor);
        $this->settings->set('sideMenuItemPaddingY', $this->sideMenuItemPaddingY);
        $this->settings->set('sideMenuItemPaddingX', $this->sideMenuItemPaddingX);
        $this->settings->set('sideMenuItemBorderRadius', $this->sideMenuItemBorderRadius);
        $this->settings->set('footerBackgroundColor', $this->footerBackgroundColor);
        $this->settings->set('footerTextColor', $this->footerTextColor);
        $this->settings->set('mobileMenuStyle', $this->mobileMenuStyle);
        $this->settings->set('bottomMenuBackgroundColor', $this->bottomMenuBackgroundColor);
        $this->settings->set('bottomMenuTextColor', $this->bottomMenuTextColor);
        $this->settings->set('floatingMenuBackgroundColor', $this->floatingMenuBackgroundColor);
        $this->settings->set('floatingMenuTextColor', $this->floatingMenuTextColor);
        $this->settings->set('floatingMenuActiveColor', $this->floatingMenuActiveColor);
        $this->settings->set('floatingMenuBackgroundOpacity', (int)$this->floatingMenuBackgroundOpacity);
        $this->settings->set('hideFloatingMenuItemLabels', (bool)$this->hideFloatingMenuItemLabels);
        $this->settings->set('hideFloatingMenuOnScrollDown', (bool)$this->hideFloatingMenuOnScrollDown);
        $this->settings->set('hideTextInBottomMenuItems', (bool)$this->hideTextInBottomMenuItems);
        $this->settings->set('mobileMenuBackgroundColor', $this->mobileMenuBackgroundColor);
        $this->settings->set('mobileMenuTextColor', $this->mobileMenuTextColor);
        $this->settings->set('mobileMenuFontSize', $this->mobileMenuFontSize);
        $this->settings->set('mobileMenuHighlightActive', (bool)$this->mobileMenuHighlightActive);
        $this->settings->set('mobileMenuItemPaddingX', $this->mobileMenuItemPaddingX);
        $this->settings->set('mobileMenuItemPaddingY', $this->mobileMenuItemPaddingY);
        $this->settings->set('mobileContentPaddingX', $this->mobileContentPaddingX);
        $this->settings->set('mobileContentPaddingY', $this->mobileContentPaddingY);
        $this->settings->set('mobileContentGutter', $this->mobileContentGutter);
        $this->settings->set('mobilePanelSpacing', $this->mobilePanelSpacing);
        $this->settings->set('mobilePanelBodyPadding', $this->mobilePanelBodyPadding);
        $this->settings->set('mobileTopbarPaddingX', $this->mobileTopbarPaddingX);
        $this->settings->set('dangerBadgeBackgroundColor', $this->dangerBadgeBackgroundColor);
        $this->settings->set('dangerBadgeTextColor', $this->dangerBadgeTextColor);
        $this->settings->set('buttonBackgroundColor', $this->buttonBackgroundColor);
        $this->settings->set('buttonTextColor', $this->buttonTextColor);
        $this->settings->set('buttonBorderColor', $this->buttonBorderColor);
        $this->settings->set('buttonHoverBackgroundColor', $this->buttonHoverBackgroundColor);
        $this->settings->set('buttonHoverTextColor', $this->buttonHoverTextColor);
        $this->settings->set('buttonSecondaryBackgroundColor', $this->buttonSecondaryBackgroundColor);
        $this->settings->set('buttonSecondaryTextColor', $this->buttonSecondaryTextColor);
        $this->settings->set('buttonSecondaryBorderColor', $this->buttonSecondaryBorderColor);
        $this->settings->set('buttonSecondaryHoverBackgroundColor', $this->buttonSecondaryHoverBackgroundColor);
        $this->settings->set('buttonSecondaryHoverTextColor', $this->buttonSecondaryHoverTextColor);
        $this->settings->set('accordionBorderColor', $this->accordionBorderColor);
        $this->settings->set('accordionBorderRadius', $this->accordionBorderRadius);
        $this->settings->set('accordionMarginBottom', $this->accordionMarginBottom);
        $this->settings->set('accordionBackgroundColor', $this->accordionBackgroundColor);
        $this->settings->set('accordionBoxShadow', $this->accordionBoxShadow);
        $this->settings->set('accordionHeaderBackgroundColor', $this->accordionHeaderBackgroundColor);
        $this->settings->set('accordionHeaderTextColor', $this->accordionHeaderTextColor);
        $this->settings->set('accordionHeaderFontSize', $this->accordionHeaderFontSize);
        $this->settings->set('accordionHeaderFontWeight', $this->accordionHeaderFontWeight);
        $this->settings->set('accordionHeaderPaddingY', $this->accordionHeaderPaddingY);
        $this->settings->set('accordionHeaderPaddingX', $this->accordionHeaderPaddingX);
        $this->settings->set('accordionHeaderHoverBackgroundColor', $this->accordionHeaderHoverBackgroundColor);
        $this->settings->set('accordionHeaderHoverTextColor', $this->accordionHeaderHoverTextColor);
        $this->settings->set('accordionHeaderOpenBackgroundColor', $this->accordionHeaderOpenBackgroundColor);
        $this->settings->set('accordionHeaderOpenTextColor', $this->accordionHeaderOpenTextColor);
        $this->settings->set('accordionContentTextColor', $this->accordionContentTextColor);
        $this->settings->set('accordionContentFontSize', $this->accordionContentFontSize);
        $this->settings->set('accordionContentPadding', $this->accordionContentPadding);
        $this->settings->set('accordionFaqTitleColor', $this->accordionFaqTitleColor);
        $this->settings->set('accordionFaqTitleFontSize', $this->accordionFaqTitleFontSize);
        $this->settings->set('accordionEnableAnimation', (bool)$this->accordionEnableAnimation);
        $this->settings->setSerialized('customCssRules', $this->customCssRules);

        // Persist in global design settings used by ThemeHelper build process.
        $global = Yii::$app->settings;
        $global->set('themePrimaryColor', $this->themePrimaryColor);
        $global->set('useDefaultThemePrimaryColor', false);
        $global->set('themeAccentColor', $this->themeAccentColor);
        $global->set('useDefaultThemeAccentColor', false);
        $global->set('themeSecondaryColor', $this->themeSecondaryColor);
        $global->set('useDefaultThemeSecondaryColor', false);
        $global->set('themeSuccessColor', $this->themeSuccessColor);
        $global->set('useDefaultThemeSuccessColor', false);
        $global->set('themeDangerColor', $this->themeDangerColor);
        $global->set('useDefaultThemeDangerColor', false);
        $global->set('themeWarningColor', $this->themeWarningColor);
        $global->set('useDefaultThemeWarningColor', false);
        $global->set('themeInfoColor', $this->themeInfoColor);
        $global->set('useDefaultThemeInfoColor', false);
        $global->set('themeLightColor', $this->themeLightColor);
        $global->set('useDefaultThemeLightColor', false);
        $global->set('themeDarkColor', $this->themeDarkColor);
        $global->set('useDefaultThemeDarkColor', false);

        $this->syncGeneratedTopbarScss();

        return true;
    }

    private function syncGeneratedTopbarScss(): void
    {
        $startMarker = '/* THISCOVERY_THEME_GENERATED_START */';
        $endMarker = '/* THISCOVERY_THEME_GENERATED_END */';
        $legacyStartMarker = '/* GOVUK_THEME_GENERATED_START */';
        $legacyEndMarker = '/* GOVUK_THEME_GENERATED_END */';

        $generatedScss = $startMarker . PHP_EOL
            . ':root {' . PHP_EOL
            . '    --yg-topbar-height: ' . (int)$this->topBarHeight . 'px;' . PHP_EOL
            . '    --yg-topbar-font-size: ' . (int)$this->topBarFontSize . 'px;' . PHP_EOL
            . '    --yg-topbar-justify-content: ' . $this->topMenuNavJustifyContent . ';' . PHP_EOL
            . '    --yg-topbar-bg: ' . $this->topMenuBackgroundColor . ';' . PHP_EOL
            . '    --yg-topbar-text: ' . $this->topMenuTextColor . ';' . PHP_EOL
            . '    --yg-topbar-hover-bg: ' . $this->topMenuButtonHoverBackgroundColor . ';' . PHP_EOL
            . '    --yg-topbar-hover-text: ' . $this->topMenuButtonHoverTextColor . ';' . PHP_EOL
            . '    --yg-top-menu-text-transform: ' . $this->topMenuTextTransform . ';' . PHP_EOL
            . '    --yg-top-menu-font-weight: ' . (int)$this->topMenuFontWeight . ';' . PHP_EOL
            . '    --yg-top-menu-letter-spacing: ' . $this->topMenuLetterSpacing . ';' . PHP_EOL
            . '    --yg-top-menu-font-style: ' . $this->topMenuFontStyle . ';' . PHP_EOL
            . '    --yg-container-max-width: ' . (int)$this->containerMaxWidth . 'px;' . PHP_EOL
            . '    --yg-font-family: "' . str_replace('"', '', (string)$this->fontFamily) . '", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;' . PHP_EOL
            . '    --yg-heading-font-family: "' . str_replace('"', '', (string)$this->headingFontFamily) . '", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;' . PHP_EOL
            . '    --yg-font-size: ' . (int)$this->fontSize . 'px;' . PHP_EOL
            . '    --yg-font-weight: ' . (int)$this->fontWeight . ';' . PHP_EOL
            . '    --yg-font-bold-weight: ' . (int)$this->fontBoldWeight . ';' . PHP_EOL
            . '    --yg-h1-font-size: ' . (int)$this->h1FontSize . 'px;' . PHP_EOL
            . '    --yg-h2-font-size: ' . (int)$this->h2FontSize . 'px;' . PHP_EOL
            . '    --yg-h3-font-size: ' . (int)$this->h3FontSize . 'px;' . PHP_EOL
            . '    --yg-h4-font-size: ' . (int)$this->h4FontSize . 'px;' . PHP_EOL
            . '    --yg-h5-font-size: ' . (int)$this->h5FontSize . 'px;' . PHP_EOL
            . '    --yg-h6-font-size: ' . (int)$this->h6FontSize . 'px;' . PHP_EOL
            . '    --yg-link-color: ' . $this->linkColor . ';' . PHP_EOL
            . '    --yg-text-main: ' . $this->textColorMain . ';' . PHP_EOL
            . '    --yg-text-secondary: ' . $this->textColorSecondary . ';' . PHP_EOL
            . '    --yg-bg-main: ' . $this->backgroundColorMain . ';' . PHP_EOL
            . '    --yg-bg-page: ' . $this->backgroundColorPage . ';' . PHP_EOL
            . '    --yg-panel-border-color: ' . $this->panelBorderColor . ';' . PHP_EOL
            . '    --yg-panel-border-radius: ' . (int)$this->panelBorderRadius . 'px;' . PHP_EOL
            . '    --yg-panel-shadow: ' . $this->panelBoxShadow . ';' . PHP_EOL
            . '    --yg-button-radius: ' . (int)$this->buttonBorderRadius . 'px;' . PHP_EOL
            . '    --yg-menu-item-text: ' . $this->menuItemTextColor . ';' . PHP_EOL
            . '    --yg-menu-item-hover-text: ' . $this->menuItemHoverTextColor . ';' . PHP_EOL
            . '    --yg-menu-item-active-text: ' . $this->menuItemActiveTextColor . ';' . PHP_EOL
            . '    --yg-menu-item-active-bg: ' . $this->menuItemActiveBackgroundColor . ';' . PHP_EOL
            . '    --yg-menu-item-padding-x: ' . (int)$this->menuItemPaddingX . 'px;' . PHP_EOL
            . '    --yg-menu-item-padding-y: ' . (int)$this->menuItemPaddingY . 'px;' . PHP_EOL
            . '    --yg-button-padding-x: ' . (int)$this->buttonPaddingX . 'px;' . PHP_EOL
            . '    --yg-button-padding-y: ' . (int)$this->buttonPaddingY . 'px;' . PHP_EOL
            . '    --yg-button-font-weight: ' . (int)$this->buttonFontWeight . ';' . PHP_EOL
            . '    --yg-input-bg: ' . $this->inputBackgroundColor . ';' . PHP_EOL
            . '    --yg-input-text: ' . $this->inputTextColor . ';' . PHP_EOL
            . '    --yg-input-border: ' . $this->inputBorderColor . ';' . PHP_EOL
            . '    --yg-input-radius: ' . (int)$this->inputBorderRadius . 'px;' . PHP_EOL
            . '    --yg-card-bg: ' . $this->cardBackgroundColor . ';' . PHP_EOL
            . '    --yg-card-text: ' . $this->cardTextColor . ';' . PHP_EOL
            . '    --yg-card-padding: ' . (int)$this->cardPadding . 'px;' . PHP_EOL
            . '    --yg-table-head-bg: ' . $this->tableHeaderBackgroundColor . ';' . PHP_EOL
            . '    --yg-table-head-text: ' . $this->tableHeaderTextColor . ';' . PHP_EOL
            . '    --yg-table-row-border: ' . $this->tableRowBorderColor . ';' . PHP_EOL
            . '    --yg-list-group-text: ' . $this->listGroupItemTextColor . ';' . PHP_EOL
            . '    --yg-list-group-hover-text: ' . $this->listGroupItemHoverTextColor . ';' . PHP_EOL
            . '    --yg-list-group-active-text: ' . $this->listGroupItemActiveTextColor . ';' . PHP_EOL
            . '    --yg-list-group-bg: ' . $this->listGroupItemBackgroundColor . ';' . PHP_EOL
            . '    --yg-list-group-hover-bg: ' . $this->listGroupItemHoverBackgroundColor . ';' . PHP_EOL
            . '    --yg-list-group-active-bg: ' . $this->listGroupItemActiveBackgroundColor . ';' . PHP_EOL
            . '    --yg-list-group-border: ' . $this->listGroupItemBorderColor . ';' . PHP_EOL
            . '    --yg-list-group-padding-y: ' . (int)$this->listGroupItemPaddingY . 'px;' . PHP_EOL
            . '    --yg-list-group-padding-x: ' . (int)$this->listGroupItemPaddingX . 'px;' . PHP_EOL
            . '    --yg-list-group-radius: ' . (int)$this->listGroupItemBorderRadius . 'px;' . PHP_EOL
            . '    --yg-sidebar-bg: ' . $this->sidebarBackgroundColor . ';' . PHP_EOL
            . '    --yg-sidebar-text: ' . $this->sidebarTextColor . ';' . PHP_EOL
            . '    --yg-side-menu-text: ' . $this->sideMenuItemTextColor . ';' . PHP_EOL
            . '    --yg-side-menu-hover-text: ' . $this->sideMenuItemHoverTextColor . ';' . PHP_EOL
            . '    --yg-side-menu-active-text: ' . $this->sideMenuItemActiveTextColor . ';' . PHP_EOL
            . '    --yg-side-menu-hover-bg: ' . $this->sideMenuItemHoverBackgroundColor . ';' . PHP_EOL
            . '    --yg-side-menu-active-bg: ' . $this->sideMenuItemActiveBackgroundColor . ';' . PHP_EOL
            . '    --yg-side-menu-padding-y: ' . (int)$this->sideMenuItemPaddingY . 'px;' . PHP_EOL
            . '    --yg-side-menu-padding-x: ' . (int)$this->sideMenuItemPaddingX . 'px;' . PHP_EOL
            . '    --yg-side-menu-radius: ' . (int)$this->sideMenuItemBorderRadius . 'px;' . PHP_EOL
            . '    --yg-footer-bg: ' . $this->footerBackgroundColor . ';' . PHP_EOL
            . '    --yg-footer-text: ' . $this->footerTextColor . ';' . PHP_EOL
            . '    --bs-link-color: ' . $this->linkColor . ';' . PHP_EOL
            . '    --bs-body-color: ' . $this->textColorMain . ';' . PHP_EOL
            . '    --bs-body-bg: ' . $this->backgroundColorMain . ';' . PHP_EOL
            . '    --bs-border-radius: ' . (int)$this->buttonBorderRadius . 'px;' . PHP_EOL
            . '    --hh-text-color-main: ' . $this->textColorMain . ';' . PHP_EOL
            . '    --hh-text-color-secondary: ' . $this->textColorSecondary . ';' . PHP_EOL
            . '    --hh-background-color-main: ' . $this->backgroundColorMain . ';' . PHP_EOL
            . '    --hh-background-color-page: ' . $this->backgroundColorPage . ';' . PHP_EOL
            . '    --hh-fixed-header-height: ' . ((int)$this->topBarHeight + self::TOP_BAR_BOTTOM_SPACING) . 'px;' . PHP_EOL
            . '    --hh-fixed-footer-height: 0px;' . PHP_EOL
            . '    --yg-bottom-menu-bg: ' . $this->bottomMenuBackgroundColor . ';' . PHP_EOL
            . '    --yg-bottom-menu-text: ' . $this->bottomMenuTextColor . ';' . PHP_EOL
            . '    --yg-floating-menu-bg: ' . $this->floatingMenuBackgroundColor . ';' . PHP_EOL
            . '    --yg-floating-menu-bg-opacity: ' . max(0, min(100, (int)$this->floatingMenuBackgroundOpacity)) . '%;' . PHP_EOL
            . '    --yg-floating-menu-bg-rgba: ' . $this->formatFloatingMenuBackgroundRgba() . ';' . PHP_EOL
            . '    --yg-floating-menu-text: ' . $this->floatingMenuTextColor . ';' . PHP_EOL
            . '    --yg-floating-menu-active: ' . $this->floatingMenuActiveColor . ';' . PHP_EOL
            . '    --yg-mobile-menu-bg: ' . $this->mobileMenuBackgroundColor . ';' . PHP_EOL
            . '    --yg-mobile-menu-text: ' . $this->mobileMenuTextColor . ';' . PHP_EOL
            . '    --yg-mobile-menu-font-size: ' . (int)$this->mobileMenuFontSize . 'px;' . PHP_EOL
            . '    --yg-mobile-menu-item-padding-x: ' . (int)$this->mobileMenuItemPaddingX . 'px;' . PHP_EOL
            . '    --yg-mobile-menu-item-padding-y: ' . (int)$this->mobileMenuItemPaddingY . 'px;' . PHP_EOL
            . '    --yg-mobile-content-padding-x: ' . (int)$this->mobileContentPaddingX . 'px;' . PHP_EOL
            . '    --yg-mobile-content-padding-y: ' . (int)$this->mobileContentPaddingY . 'px;' . PHP_EOL
            . '    --yg-mobile-content-gutter: ' . (int)$this->mobileContentGutter . 'px;' . PHP_EOL
            . '    --yg-mobile-panel-spacing: ' . (int)$this->mobilePanelSpacing . 'px;' . PHP_EOL
            . '    --yg-mobile-panel-body-padding: ' . (int)$this->mobilePanelBodyPadding . 'px;' . PHP_EOL
            . '    --yg-mobile-topbar-padding-x: ' . (int)$this->mobileTopbarPaddingX . 'px;' . PHP_EOL
            . '    --yg-danger-badge-bg: ' . $this->dangerBadgeBackgroundColor . ';' . PHP_EOL
            . '    --yg-danger-badge-text: ' . $this->dangerBadgeTextColor . ';' . PHP_EOL
            . '    --yg-button-bg: ' . $this->buttonBackgroundColor . ';' . PHP_EOL
            . '    --yg-button-text: ' . $this->buttonTextColor . ';' . PHP_EOL
            . '    --yg-button-border: ' . $this->buttonBorderColor . ';' . PHP_EOL
            . '    --yg-button-hover-bg: ' . $this->buttonHoverBackgroundColor . ';' . PHP_EOL
            . '    --yg-button-hover-text: ' . $this->buttonHoverTextColor . ';' . PHP_EOL
            . '    --yg-button-secondary-bg: ' . $this->buttonSecondaryBackgroundColor . ';' . PHP_EOL
            . '    --yg-button-secondary-text: ' . $this->buttonSecondaryTextColor . ';' . PHP_EOL
            . '    --yg-button-secondary-border: ' . $this->buttonSecondaryBorderColor . ';' . PHP_EOL
            . '    --yg-button-secondary-hover-bg: ' . $this->buttonSecondaryHoverBackgroundColor . ';' . PHP_EOL
            . '    --yg-button-secondary-hover-text: ' . $this->buttonSecondaryHoverTextColor . ';' . PHP_EOL
            . '    --yg-accordion-border-color: ' . $this->accordionBorderColor . ';' . PHP_EOL
            . '    --yg-accordion-border-radius: ' . (int)$this->accordionBorderRadius . 'px;' . PHP_EOL
            . '    --yg-accordion-margin-bottom: ' . (int)$this->accordionMarginBottom . 'px;' . PHP_EOL
            . '    --yg-accordion-bg: ' . $this->accordionBackgroundColor . ';' . PHP_EOL
            . '    --yg-accordion-shadow: ' . $this->accordionBoxShadow . ';' . PHP_EOL
            . '    --yg-accordion-header-bg: ' . $this->accordionHeaderBackgroundColor . ';' . PHP_EOL
            . '    --yg-accordion-header-text: ' . $this->accordionHeaderTextColor . ';' . PHP_EOL
            . '    --yg-accordion-header-font-size: ' . (int)$this->accordionHeaderFontSize . 'px;' . PHP_EOL
            . '    --yg-accordion-header-font-weight: ' . (int)$this->accordionHeaderFontWeight . ';' . PHP_EOL
            . '    --yg-accordion-header-padding-y: ' . (int)$this->accordionHeaderPaddingY . 'px;' . PHP_EOL
            . '    --yg-accordion-header-padding-x: ' . (int)$this->accordionHeaderPaddingX . 'px;' . PHP_EOL
            . '    --yg-accordion-header-hover-bg: ' . $this->accordionHeaderHoverBackgroundColor . ';' . PHP_EOL
            . '    --yg-accordion-header-hover-text: ' . $this->accordionHeaderHoverTextColor . ';' . PHP_EOL
            . '    --yg-accordion-header-open-bg: ' . $this->accordionHeaderOpenBackgroundColor . ';' . PHP_EOL
            . '    --yg-accordion-header-open-text: ' . $this->accordionHeaderOpenTextColor . ';' . PHP_EOL
            . '    --yg-accordion-content-text: ' . $this->accordionContentTextColor . ';' . PHP_EOL
            . '    --yg-accordion-content-font-size: ' . (int)$this->accordionContentFontSize . 'px;' . PHP_EOL
            . '    --yg-accordion-content-padding: ' . (int)$this->accordionContentPadding . 'px;' . PHP_EOL
            . '    --yg-accordion-faq-title-color: ' . $this->accordionFaqTitleColor . ';' . PHP_EOL
            . '    --yg-accordion-faq-title-font-size: ' . (int)$this->accordionFaqTitleFontSize . 'px;' . PHP_EOL
            . '    --yg-accordion-open-animation: ' . ((bool)$this->accordionEnableAnimation ? 'ygAccordionFadeIn 0.3s ease-in-out' : 'none') . ';' . PHP_EOL
            . '}' . PHP_EOL . PHP_EOL
            . 'body {' . PHP_EOL
            . '    font-family: var(--yg-font-family);' . PHP_EOL
            . '    font-size: var(--yg-font-size);' . PHP_EOL
            . '    font-weight: var(--yg-font-weight);' . PHP_EOL
            . '    color: var(--yg-text-main);' . PHP_EOL
            . '    background: var(--yg-bg-page);' . PHP_EOL
            . '}' . PHP_EOL
            . 'strong, b { font-weight: var(--yg-font-bold-weight); }' . PHP_EOL
            . 'h1, h2, h3, h4, h5, h6 {' . PHP_EOL
            . '    font-family: var(--yg-heading-font-family);' . PHP_EOL
            . '    color: var(--yg-text-main);' . PHP_EOL
            . '}' . PHP_EOL
            . 'h1 { font-size: var(--yg-h1-font-size); }' . PHP_EOL
            . 'h2 { font-size: var(--yg-h2-font-size); }' . PHP_EOL
            . 'h3 { font-size: var(--yg-h3-font-size); }' . PHP_EOL
            . 'h4 { font-size: var(--yg-h4-font-size); }' . PHP_EOL
            . 'h5 { font-size: var(--yg-h5-font-size); }' . PHP_EOL
            . 'h6 { font-size: var(--yg-h6-font-size); }' . PHP_EOL
            . 'a { color: var(--yg-link-color); }' . PHP_EOL
            . '#topbar #top-menu-nav > li > a {' . PHP_EOL
            . '    color: var(--yg-menu-item-text) !important;' . PHP_EOL
            . '    padding: var(--yg-menu-item-padding-y) var(--yg-menu-item-padding-x);' . PHP_EOL
            . '    font-weight: var(--yg-top-menu-font-weight);' . PHP_EOL
            . '    text-transform: var(--yg-top-menu-text-transform);' . PHP_EOL
            . '    letter-spacing: var(--yg-top-menu-letter-spacing);' . PHP_EOL
            . '    font-style: var(--yg-top-menu-font-style);' . PHP_EOL
            . '}' . PHP_EOL
            . '#topbar #top-menu-nav > li > a:hover,' . PHP_EOL
            . '#topbar #top-menu-nav > li > a:focus {' . PHP_EOL
            . '    color: var(--yg-menu-item-hover-text) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '#topbar #top-menu-nav > li > a.active {' . PHP_EOL
            . '    color: var(--yg-menu-item-active-text) !important;' . PHP_EOL
            . '    background: var(--yg-menu-item-active-bg) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.panel, .card {' . PHP_EOL
            . '    border-color: var(--yg-panel-border-color);' . PHP_EOL
            . '    border-radius: var(--yg-panel-border-radius);' . PHP_EOL
            . '    box-shadow: var(--yg-panel-shadow);' . PHP_EOL
            . '    background: var(--yg-card-bg);' . PHP_EOL
            . '    color: var(--yg-card-text);' . PHP_EOL
            . '}' . PHP_EOL
            . '.panel {' . PHP_EOL
            . '    padding: 0;' . PHP_EOL
            . '}' . PHP_EOL
            . '.panel .panel-body,' . PHP_EOL
            . '.wall-entry .panel-body,' . PHP_EOL
            . '.layout-content-container .panel-body {' . PHP_EOL
            . '    font-family: var(--yg-font-family);' . PHP_EOL
            . '    font-size: var(--yg-font-size);' . PHP_EOL
            . '    font-weight: var(--yg-font-weight);' . PHP_EOL
            . '    color: var(--yg-card-text);' . PHP_EOL
            . '    padding: var(--yg-card-padding);' . PHP_EOL
            . '}' . PHP_EOL
            . '.card {' . PHP_EOL
            . '    padding: var(--yg-card-padding);' . PHP_EOL
            . '}' . PHP_EOL
            . '.badge {' . PHP_EOL
            . '    font-family: var(--yg-font-family);' . PHP_EOL
            . '}' . PHP_EOL
            . '.badge.text-bg-danger,' . PHP_EOL
            . '.badge.bg-danger,' . PHP_EOL
            . '#badge-notifications,' . PHP_EOL
            . '#badge-messages,' . PHP_EOL
            . '.badge.messageCount,' . PHP_EOL
            . '.badge.badge-notifications {' . PHP_EOL
            . '    background-color: var(--yg-danger-badge-bg) !important;' . PHP_EOL
            . '    color: var(--yg-danger-badge-text) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.btn {' . PHP_EOL
            . '    border-radius: var(--yg-button-radius);' . PHP_EOL
            . '    padding: var(--yg-button-padding-y) var(--yg-button-padding-x);' . PHP_EOL
            . '    font-weight: var(--yg-button-font-weight);' . PHP_EOL
            . '}' . PHP_EOL
            . '.btn-primary {' . PHP_EOL
            . '    --bs-btn-bg: var(--yg-button-bg);' . PHP_EOL
            . '    --bs-btn-border-color: var(--yg-button-border);' . PHP_EOL
            . '    --bs-btn-color: var(--yg-button-text);' . PHP_EOL
            . '    --bs-btn-hover-bg: var(--yg-button-hover-bg);' . PHP_EOL
            . '    --bs-btn-hover-border-color: var(--yg-button-hover-bg);' . PHP_EOL
            . '    --bs-btn-hover-color: var(--yg-button-hover-text);' . PHP_EOL
            . '    --bs-btn-active-bg: var(--yg-button-hover-bg);' . PHP_EOL
            . '    --bs-btn-active-border-color: var(--yg-button-hover-bg);' . PHP_EOL
            . '    --bs-btn-active-color: var(--yg-button-hover-text);' . PHP_EOL
            . '    --bs-btn-disabled-bg: var(--yg-button-bg);' . PHP_EOL
            . '    --bs-btn-disabled-border-color: var(--yg-button-border);' . PHP_EOL
            . '    --bs-btn-disabled-color: var(--yg-button-text);' . PHP_EOL
            . '}' . PHP_EOL
            . '.btn-secondary,' . PHP_EOL
            . '.btn-light {' . PHP_EOL
            . '    --bs-btn-bg: var(--yg-button-secondary-bg);' . PHP_EOL
            . '    --bs-btn-border-color: var(--yg-button-secondary-border);' . PHP_EOL
            . '    --bs-btn-color: var(--yg-button-secondary-text);' . PHP_EOL
            . '    --bs-btn-hover-bg: var(--yg-button-secondary-hover-bg);' . PHP_EOL
            . '    --bs-btn-hover-border-color: var(--yg-button-secondary-hover-bg);' . PHP_EOL
            . '    --bs-btn-hover-color: var(--yg-button-secondary-hover-text);' . PHP_EOL
            . '    --bs-btn-active-bg: var(--yg-button-secondary-hover-bg);' . PHP_EOL
            . '    --bs-btn-active-border-color: var(--yg-button-secondary-hover-bg);' . PHP_EOL
            . '    --bs-btn-active-color: var(--yg-button-secondary-hover-text);' . PHP_EOL
            . '}' . PHP_EOL
            . '.btn-default {' . PHP_EOL
            . '    background: var(--yg-button-secondary-bg);' . PHP_EOL
            . '    border-color: var(--yg-button-secondary-border);' . PHP_EOL
            . '    color: var(--yg-button-secondary-text) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.btn-default:hover,' . PHP_EOL
            . '.btn-default:focus {' . PHP_EOL
            . '    background: var(--yg-button-secondary-hover-bg);' . PHP_EOL
            . '    border-color: var(--yg-button-secondary-hover-bg);' . PHP_EOL
            . '    color: var(--yg-button-secondary-hover-text) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.form-control, .form-select {' . PHP_EOL
            . '    background: var(--yg-input-bg);' . PHP_EOL
            . '    color: var(--yg-input-text);' . PHP_EOL
            . '    border-color: var(--yg-input-border);' . PHP_EOL
            . '    border-radius: var(--yg-input-radius);' . PHP_EOL
            . '}' . PHP_EOL
            . 'table thead th {' . PHP_EOL
            . '    background: var(--yg-table-head-bg);' . PHP_EOL
            . '    color: var(--yg-table-head-text);' . PHP_EOL
            . '}' . PHP_EOL
            . 'table td, table th { border-color: var(--yg-table-row-border) !important; }' . PHP_EOL
            . '.list-group-item {' . PHP_EOL
            . '    color: var(--yg-list-group-text);' . PHP_EOL
            . '    background: var(--yg-list-group-bg);' . PHP_EOL
            . '    border-color: var(--yg-list-group-border);' . PHP_EOL
            . '    padding: var(--yg-list-group-padding-y) var(--yg-list-group-padding-x);' . PHP_EOL
            . '    border-radius: var(--yg-list-group-radius);' . PHP_EOL
            . '}' . PHP_EOL
            . '.list-group-item:hover {' . PHP_EOL
            . '    color: var(--yg-list-group-hover-text);' . PHP_EOL
            . '    background: var(--yg-list-group-hover-bg);' . PHP_EOL
            . '}' . PHP_EOL
            . '.list-group-item:hover, .list-group .list-group-item:hover, .sidebar .list-group-item:hover, .layout-sidebar-left .list-group-item:hover {' . PHP_EOL
            . '    color: var(--yg-list-group-hover-text) !important;' . PHP_EOL
            . '    background: var(--yg-list-group-hover-bg) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.list-group-item.active {' . PHP_EOL
            . '    color: var(--yg-list-group-active-text) !important;' . PHP_EOL
            . '    background: var(--yg-list-group-active-bg) !important;' . PHP_EOL
            . '    border-color: var(--yg-list-group-active-bg) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.list-group-item.active, .list-group .list-group-item.active, .sidebar .list-group-item.active, .layout-sidebar-left .list-group-item.active {' . PHP_EOL
            . '    color: var(--yg-list-group-active-text) !important;' . PHP_EOL
            . '    background: var(--yg-list-group-active-bg) !important;' . PHP_EOL
            . '    border-color: var(--yg-list-group-active-bg) !important;' . PHP_EOL
            . '}' . PHP_EOL
            . '.layout-sidebar-container, .sidebar {' . PHP_EOL
            . '    background: var(--yg-sidebar-bg);' . PHP_EOL
            . '    color: var(--yg-sidebar-text);' . PHP_EOL
            . '}' . PHP_EOL
            . '.layout-sidebar-container .nav-link, .sidebar .nav-link, .sidenav .nav-link {' . PHP_EOL
            . '    color: var(--yg-side-menu-text) !important;' . PHP_EOL
            . '    padding: var(--yg-side-menu-padding-y) var(--yg-side-menu-padding-x);' . PHP_EOL
            . '    border-radius: var(--yg-side-menu-radius);' . PHP_EOL
            . '}' . PHP_EOL
            . '.layout-sidebar-container .nav-link:hover, .sidebar .nav-link:hover, .sidenav .nav-link:hover {' . PHP_EOL
            . '    color: var(--yg-side-menu-hover-text) !important;' . PHP_EOL
            . '    background: var(--yg-side-menu-hover-bg);' . PHP_EOL
            . '}' . PHP_EOL
            . '.layout-sidebar-container .nav-link.active, .sidebar .nav-link.active, .sidenav .nav-link.active {' . PHP_EOL
            . '    color: var(--yg-side-menu-active-text) !important;' . PHP_EOL
            . '    background: var(--yg-side-menu-active-bg);' . PHP_EOL
            . '}' . PHP_EOL
            . '.footer-nav {' . PHP_EOL
            . '    background: var(--yg-footer-bg);' . PHP_EOL
            . '    color: var(--yg-footer-text);' . PHP_EOL
            . '}' . PHP_EOL
            . $this->renderCustomCssRulesScss()
            . '@media (max-width: 767.98px) {' . PHP_EOL
            . '    :root {' . PHP_EOL
            . '        --yg-topbar-height: ' . self::TOP_BAR_HEIGHT_SM . 'px;' . PHP_EOL
            . '        --hh-fixed-header-height: ' . (self::TOP_BAR_HEIGHT_SM + self::TOP_BAR_BOTTOM_SPACING_SM) . 'px;' . PHP_EOL
            . '    }' . PHP_EOL
            . '    body.hh-yg-mobile-menu-floating-bar {' . PHP_EOL
            . '        --hh-fixed-footer-height: ' . (self::FLOATING_BAR_HEIGHT_SM + 2) . 'px;' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL
            . '@media (max-width: 575.98px) {' . PHP_EOL
            . '    body.hh-yg-mobile-menu-bottom-bar {' . PHP_EOL
            . '        --hh-fixed-footer-height: ' . (self::BOTTOM_BAR_HEIGHT_SM + 2) . 'px;' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL
            . $endMarker;

        $globalSettings = Yii::$app->settings;
        $customScss = (string)$globalSettings->get('themeCustomScss', '');
        foreach ([
            [$startMarker, $endMarker],
            [$legacyStartMarker, $legacyEndMarker],
        ] as [$markerStart, $markerEnd]) {
            $pattern = '/' . preg_quote($markerStart, '/') . '.*?' . preg_quote($markerEnd, '/') . '\n?/s';
            $customScss = preg_replace($pattern, '', $customScss) ?? $customScss;
        }
        $customScss = trim($customScss);
        $customScss = $this->stripLegacyAccordionScss($customScss);
        $customScss = ($customScss === '' ? '' : $customScss . PHP_EOL . PHP_EOL) . $generatedScss . PHP_EOL;
        $globalSettings->set('themeCustomScss', $customScss);
    }

    /**
     * Remove legacy hardcoded FAQ/TinyMCE accordion CSS from pasted custom SCSS.
     * Those rules override the configurable accordion styles in _accordions.scss.
     */
    private function stripLegacyAccordionScss(string $scss): string
    {
        if ($scss === '') {
            return $scss;
        }

        $patterns = [
            '/\/\* FAQ Accordion Styling.*?(?=\/\* Rounded Edges|\/\* Imported compatibility|\z)/s',
            '/\/\* TinyMCE Accordion Styling.*?(?=\/\* Rounded Edges|\/\* Animation for|\/\* Imported compatibility|\z)/s',
            '/@keyframes\s+fadeIn\s*\{[^}]*\}/s',
        ];

        foreach ($patterns as $pattern) {
            $scss = preg_replace($pattern, '', $scss) ?? $scss;
        }

        $selectors = [
            '.faq-accordion',
            '.faq-title',
            '.faq-item',
            '.faq-question',
            'details.mce-accordion',
            'body details.mce-accordion',
            '.layout-content-container details.mce-accordion',
            '.panel-body details.mce-accordion',
            '.richtext details.mce-accordion',
            '.stream-entry details.mce-accordion',
            '.mce-content-body details.mce-accordion',
        ];

        foreach ($selectors as $selector) {
            $pattern = '/' . preg_quote($selector, '/') . '(?:[^{,]*,(?:[^{]+)?)*\s*\{(?:[^{}]*|\{[^{}]*\})*\}/s';
            $previous = null;
            while ($previous !== $scss) {
                $previous = $scss;
                $scss = preg_replace($pattern, '', $scss) ?? $scss;
            }
        }

        return trim(preg_replace("/\n{3,}/", "\n\n", $scss) ?? $scss);
    }

    private function renderCustomCssRulesScss(): string
    {
        return $this->renderCustomCssRulesScssFromRules($this->customCssRules);
    }

    /**
     * @param array<int, array{description?: string, selector?: string, declarations?: string}> $rules
     */
    private function renderCustomCssRulesScssFromRules(array $rules): string
    {
        $scss = '';
        foreach ($rules as $rule) {
            $selector = trim((string)($rule['selector'] ?? ''));
            $declarations = trim((string)($rule['declarations'] ?? ''));
            if ($selector === '' || $declarations === '') {
                continue;
            }
            $scss .= $selector . ' {' . PHP_EOL;
            $scss .= $declarations . PHP_EOL;
            $scss .= '}' . PHP_EOL;
        }

        return $scss;
    }

    private function normalizeCustomCssRules($rawRules): array
    {
        if (is_string($rawRules)) {
            $decoded = json_decode($rawRules, true);
            if (is_array($decoded)) {
                $rawRules = $decoded;
            } else {
                return [];
            }
        }

        if (!is_array($rawRules)) {
            return [];
        }

        $normalized = [];
        foreach ($rawRules as $rule) {
            if (!is_array($rule)) {
                continue;
            }

            $selector = trim((string)($rule['selector'] ?? ''));
            $declarations = trim((string)($rule['declarations'] ?? ''));
            $description = trim((string)($rule['description'] ?? ''));
            if ($selector === '' && $declarations === '' && $description === '') {
                continue;
            }

            $normalized[] = [
                'description' => $description,
                'selector' => $selector,
                'declarations' => $declarations,
            ];
        }

        return $normalized;
    }

    private function formatFloatingMenuBackgroundRgba(): string
    {
        $hex = ltrim((string)$this->floatingMenuBackgroundColor, '#');
        if (!preg_match('/^[a-f0-9]{6}$/i', $hex)) {
            return 'rgba(255, 255, 255, 0.92)';
        }

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));
        $alpha = max(0, min(100, (int)$this->floatingMenuBackgroundOpacity)) / 100;

        return sprintf('rgba(%d, %d, %d, %.2f)', $red, $green, $blue, $alpha);
    }
}
