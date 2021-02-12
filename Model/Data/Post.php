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
namespace Magebit\InstagramFeed\Model\Data;

use Magebit\InstagramFeed\Api\Data\PostInterface;

/**
 * @package Magebit\InstagramFeed\Model\Data
 */
class Post extends \Magento\Framework\Api\AbstractExtensibleObject implements PostInterface
{
    /**
     * Get post_id
     * @return string|null
     */
    public function getPostId()
    {
        return $this->_get(self::POST_ID);
    }

    /**
     * Set post_id
     * @param string $postId
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setPostId($postId)
    {
        return $this->setData(self::POST_ID, $postId);
    }

    /**
     * Get media
     * @return string|null
     */
    public function getMedia()
    {
        return $this->_get(self::MEDIA);
    }

    /**
     * Set media
     * @param string $media
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setMedia($media)
    {
        return $this->setData(self::MEDIA, $media);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Magebit\InstagramFeed\Api\Data\PostExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Magebit\InstagramFeed\Api\Data\PostExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magebit\InstagramFeed\Api\Data\PostExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get caption
     * @return string|null
     */
    public function getCaption()
    {
        return $this->_get(self::CAPTION);
    }

    /**
     * Set caption
     * @param string $caption
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setCaption($caption)
    {
        return $this->setData(self::CAPTION, $caption);
    }

    /**
     * Get permalink
     * @return string|null
     */
    public function getPermalink()
    {
        return $this->_get(self::PERMALINK);
    }

    /**
     * Set permalink
     * @param string $permalink
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setPermalink($permalink)
    {
        return $this->setData(self::PERMALINK, $permalink);
    }

    /**
     * Get like_count
     * @return string|null
     */
    public function getLikeCount()
    {
        return $this->_get(self::LIKE_COUNT);
    }

    /**
     * Set like_count
     * @param string $likeCount
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setLikeCount($likeCount)
    {
        return $this->setData(self::LIKE_COUNT, $likeCount);
    }

    /**
     * Get id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * Set id
     * @param string $id
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Get media_hq
     * @return string|null
     */
    public function getMediaHq()
    {
        return $this->_get(self::MEDIA_HQ);
    }

    /**
     * Set media_hq
     * @param string $mediaHq
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setMediaHq($mediaHq)
    {
        return $this->setData(self::MEDIA_HQ, $mediaHq);
    }

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $storeId
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
