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
namespace Magebit\InstagramFeed\Model;

use Magebit\InstagramFeed\Api\PostRepositoryInterface;
use Magebit\InstagramFeed\Helper\Data;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * @package Magebit\InstagramFeed\Model
 */
class PostsManagement implements \Magebit\InstagramFeed\Api\PostsManagementInterface
{
    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param PostRepositoryInterface $postRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        PostRepositoryInterface $postRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->postRepository = $postRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }
    /**
     * {@inheritdoc}
     */
    public function getPosts($limit = 20)
    {
        $posts = $this->postRepository->getList($this->searchCriteriaBuilder->setPageSize($limit)->create())->getItems();

        $mediaBase = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return array_map(function ($post) use ($mediaBase) {
            /** @var \Magebit\InstagramFeed\Api\Data\PostInterface $post */
            return [
                'media_url' => $mediaBase . Data::MEDIA_DIR . DIRECTORY_SEPARATOR . $post->getMedia(),
                'media_url_hq' => $mediaBase . Data::MEDIA_DIR . DIRECTORY_SEPARATOR  . $post->getMediaHq(),
                'caption' => $post->getCaption(),
                'permalink' => $post->getPermalink(),
                'like_count' => $post->getLikeCount(),
                'id' => $post->getId()
            ];
        }, $posts);
    }
}
