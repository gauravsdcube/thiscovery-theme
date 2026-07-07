<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme\libs;

use humhub\libs\ProfileBannerImage as HumHubProfileBannerImage;

/**
 * Wider space/profile banner crop for Thiscovery (1962 x 192).
 */
class ProfileBannerImage extends HumHubProfileBannerImage
{
    protected $width = 1962;

    protected $height = 192;
}
