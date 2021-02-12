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
namespace Magebit\InstagramFeed\Console\Command;

use Magebit\InstagramFeed\Api\Data\PostSearchResultsInterface;
use Magebit\InstagramFeed\Helper\Instagram;
use Magebit\InstagramFeed\Helper\Data;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Filesystem\Io\File;
use Magebit\InstagramFeed\Model\PostRepository;
use Magebit\InstagramFeed\Model\Data\PostFactory;
use Magebit\InstagramFeed\Model\Data\Post;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * @package Magebit\InstagramFeed\Console\Command
 */
class Fetch extends Command
{
    const COMMAND_NAME = 'magebit:instagram:fetch';

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
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param Instagram $instagram
     * @param Data $data
     * @param File $file
     * @param PostRepository $postRepository
     * @param PostFactory $postFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct(
        Instagram $instagram,
        Data $data,
        File $file,
        PostRepository $postRepository,
        PostFactory $postFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct();
        $this->instagram = $instagram;
        $this->data = $data;
        $this->file = $file;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $output->writeln('Fetching instagram posts...');

        try {
            $posts = $this->instagram->fetchPosts();
        } catch (\Exception $e) {
            return $output->writeln('Something went wrong when fetching posts: ' . $e->getMessage());
        }

        if (!$posts) {
            return $output->writeln('Something went wrong when fetching posts. Exiting');
        }

        $imgDir = $this->data->getMediaDir();

        $output->writeln('Removing old posts');
        $this->file->rmdirRecursive($imgDir);
        $this->removeOldPosts();

        $this->file->checkAndCreateFolder($imgDir);

        foreach ($posts as $i => $post) {
            $output->writeln('Saving post ' . ($i + 1) . '/' . count($posts));
            $node = $post['node'];
            list($media) = explode('?', basename($node['display_url']));
            $mediaHq = 'hq_' . $media;
            $resultMedia = $this->file->read($node['thumbnail_src'], $imgDir . $media);
            $resultMediaHq = $this->file->read($node['display_url'], $imgDir . $mediaHq);

            if ($resultMedia && $resultMediaHq) {
                /** @var Post $postModel */
                $postModel = $this->postFactory->create();
                $postModel->setMedia($media);
                $postModel->setMediaHq($mediaHq);
                if (isset($node['edge_media_to_caption']['edges'][0]['node']['text'])) {
                    $postModel->setCaption($node['edge_media_to_caption']['edges'][0]['node']['text']);
                }
                $postModel->setPermalink('https://instagram.com/p/' . $node['shortcode']);
                $postModel->setLikeCount($node['edge_media_preview_like']['count']);
                $postModel->setId($node['id']);
                $this->postRepository->save($postModel);
            } else {
                $output->writeln('Failed to load and save images. Skipping post.');
            }
        }

        $output->writeln('Done!');
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

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription("Fetch latest instagram posts");
        parent::configure();
    }
}
