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
interface PostInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const MEDIA = 'media';
    const MEDIA_HQ = 'media_hq';
    const STORE_ID = 'store_id';
    const POST_ID = 'post_id';
    const PERMALINK = 'permalink';
    const ID = 'id';
    const CAPTION = 'caption';
    const LIKE_COUNT = 'like_count';

    /**
     * Get post_id
     * @return string|null
     */
    public function getPostId();

    /**
     * Set post_id
     * @param string $postId
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setPostId($postId);

    /**
     * Get media
     * @return string|null
     */
    public function getMedia();

    /**
     * Set media
     * @param string $media
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setMedia($media);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Magebit\InstagramFeed\Api\Data\PostExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Magebit\InstagramFeed\Api\Data\PostExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magebit\InstagramFeed\Api\Data\PostExtensionInterface $extensionAttributes
    );

    /**
     * Get caption
     * @return string|null
     */
    public function getCaption();

    /**
     * Set caption
     * @param string $caption
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setCaption($caption);

    /**
     * Get permalink
     * @return string|null
     */
    public function getPermalink();

    /**
     * Set permalink
     * @param string $permalink
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setPermalink($permalink);

    /**
     * Get like_count
     * @return string|null
     */
    public function getLikeCount();

    /**
     * Set like_count
     * @param string $likeCount
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setLikeCount($likeCount);

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setId($id);

    /**
     * Get media_hq
     * @return string|null
     */
    public function getMediaHq();

    /**
     * Set media_hq
     * @param string $mediaHq
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setMediaHq($mediaHq);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $storeId
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setStoreId($storeId);
}
