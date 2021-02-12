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
namespace Magebit\InstagramFeed\Cron;

use Magebit\InstagramFeed\Api\Data\PostInterface;
use Magebit\InstagramFeed\Api\Data\PostInterfaceFactory;
use Magebit\InstagramFeed\Api\Data\PostSearchResultsInterface;
use Magebit\InstagramFeed\Api\PostRepositoryInterface;
use Magebit\InstagramFeed\Helper\Data;
use Magebit\InstagramFeed\Helper\Instagram;
use Magebit\InstagramFeed\Logger\Logger;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Filesystem\Io\File;

/**
 * @package Magebit\InstagramFeed\Cron
 */
class UpdatePosts
{
    /**
     * @var Instagram
     */
    protected $instagram;

    /**
     * @var Data
     */
    protected $data;

    /**
     * @var File
     */
    protected $file;

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var PostInterfaceFactory
     */
    protected $postFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Instagram $instagram
     * @param Data $data
     * @param File $file
     * @param PostRepositoryInterface $postRepository
     * @param PostInterfaceFactory $postFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Logger $logger
     * @return void
     */
    public function __construct(
        Instagram $instagram,
        Data $data,
        File $file,
        PostRepositoryInterface $postRepository,
        PostInterfaceFactory $postFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Logger $logger
    ) {
        $this->instagram = $instagram;
        $this->data = $data;
        $this->file = $file;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->debug('Fetching instagram posts...');

        try {
            $posts = $this->instagram->fetchPosts();
        } catch (\Exception $e) {
            return $this->logger->error('Something went wrong when fetching posts: ' . $e->getMessage());
        }

        if (!$posts) {
            return $this->logger->error('Something went wrong when fetching posts. Exiting');
        }

        $imgDir = $this->data->getMediaDir();

        $this->logger->debug('Removing old posts');
        $this->file->rmdirRecursive($imgDir);
        $this->removeOldPosts();

        $this->file->checkAndCreateFolder($imgDir);
        
        foreach ($posts as $i => $post) {
            $this->logger->debug('Saving post ' . ($i + 1) . '/' . count($posts));
            $node = $post['node'];
            list($media) = explode('?', basename($node['display_url']));
            $mediaHq = 'hq_' . $media;
            $resultMedia = $this->file->read($node['thumbnail_src'], $imgDir . $media);
            $resultMediaHq = $this->file->read($node['display_url'], $imgDir . $mediaHq);

            if ($resultMedia && $resultMediaHq) {
                /** @var PostInterface $post */
                $post = $this->postFactory->create();
                $post->setMedia($media);
                $post->setMediaHq($mediaHq);
                if (isset($node['edge_media_to_caption']['edges'][0]['node']['text'])) {
                    $post->setCaption($node['edge_media_to_caption']['edges'][0]['node']['text']);
                }
                $post->setPermalink('https://instagram.com/p/' . $node['shortcode']);
                $post->setLikeCount($node['edge_media_preview_like']['count']);
                $post->setId($node['id']);
                $this->postRepository->save($post);
            } else {
                $this->logger->error('Failed to load and save images. Skipping post.');
            }
        }

        $this->logger->debug('Done!');
    }

    /**
     * @return PostSearchResultsInterface
     */
    protected function removeOldPosts()
    {
        $posts = $this->postRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        foreach ($posts as $post) {
            $this->postRepository->delete($post);
        }
    }
}
