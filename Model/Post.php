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

use Magebit\InstagramFeed\Api\Data\PostInterface;
use Magebit\InstagramFeed\Api\Data\PostInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * @package Magebit\InstagramFeed\Model
 */
class Post extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var PostInterfaceFactory
     */
    protected $postDataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    protected $_eventPrefix = 'magebit_instagramfeed_post';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PostInterfaceFactory $postDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magebit\InstagramFeed\Model\ResourceModel\Post $resource
     * @param \Magebit\InstagramFeed\Model\ResourceModel\Post\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PostInterfaceFactory $postDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Magebit\InstagramFeed\Model\ResourceModel\Post $resource,
        \Magebit\InstagramFeed\Model\ResourceModel\Post\Collection $resourceCollection,
        array $data = []
    ) {
        $this->postDataFactory = $postDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve post model with post data
     * @return PostInterface
     */
    public function getDataModel()
    {
        $postData = $this->getData();
        
        $postDataObject = $this->postDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $postDataObject,
            $postData,
            PostInterface::class
        );
        
        return $postDataObject;
    }
}
