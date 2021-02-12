<?php declare(strict_types=1);
/**
 * This file is part of the Magebit_InstagramFeed package
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magebit_InstagramFeed
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2021 Magebit (https://magebit.com/)
 * @author    Kristofers Ozolins <info@magebit.com>
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Magebit\InstagramFeed\Api\Data;

/**
 * @package Magebit\InstagramFeed\Api\Data
 */
interface PostResponseInterface
{
    /**
     * Get post_id
     * @return string|null
     */
    public function getId();

    /**
     * Get media
     * @return string|null
     */
    public function getImage();

    /**
     * Get media
     * @return string|null
     */
    public function getImageHq();

    /**
     * Get caption
     * @return string|null
     */
    public function getCaption();

    /**
     * Get permalink
     * @return string|null
     */
    public function getPermalink();

    /**
     * Get like_count
     * @return string|null
     */
    public function getLikeCount();

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();
}
