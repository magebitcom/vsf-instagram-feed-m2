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

use Magebit\InstagramFeed\Api\MediaManagementInterface;
use Magebit\InstagramFeed\Helper\Data;
use Magento\Framework\HTTP\Client\Curl;

/**
 * @package Magebit\InstagramFeed\Model
 */
class MediaManagement implements MediaManagementInterface
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var Data
     */
    protected $helper;
    
    /**
     * @param Curl $curl
     * @param Data $helper
     * @return void
     */
    public function __construct(
        Curl $curl,
        Data $helper
    ) {
        $this->curl = $curl;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getMedia()
    {
        $this->curl->setTimeout(30);
        $this->curl->get($this->getUserMediaUrl());
        $body = json_decode($this->curl->getBody(), true);
        return $body['data'];
    }

    /**
     * Get user media endpoint url
     *
     * @return string
     */
    protected function getUserMediaUrl()
    {
        return Data::INSTAGRAM_GRAPH_URL . '/' . $this->helper->getUserId() . '/media?' . http_build_query([
            'fields' => join(',', [
                'media_type',
                'caption',
                'media_url',
                'permalink',
                'thumbnail_url',
                'timestamp',
                'media_timestamp',
                'username'
            ]),
            'access_token' => $this->helper->getAuthToken()
        ]);
    }
}
