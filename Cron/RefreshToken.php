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

use Magento\Framework\HTTP\Client\Curl;
use Magebit\InstagramFeed\Helper\Data;
use Magento\Framework\App\Cache\TypeListInterface;
use Psr\Log\LoggerInterface;

class RefreshToken
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
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * @param Curl $curl
     * @param Data $helper
     * @param TypeListInterface $cacheTypeList
     * @param LoggerInterface $logger
     * @return void
     */
    public function __construct(
        Curl $curl,
        Data $helper,
        TypeListInterface $cacheTypeList,
        LoggerInterface $logger
    ) {
        $this->curl = $curl;
        $this->helper = $helper;
        $this->cacheTypeList = $cacheTypeList;
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $accessToken = $this->helper->getAuthToken();
        $expiryTimestamp = $this->helper->getTokenTimestamp();

        if (!$accessToken) {
            return $this->logger->debug('[Magebit_InstagramFeed] No access token. Skipping token refresh...');
        }

        if (!$expiryTimestamp) {
            return $this->logger->debug('[Magebit_InstagramFeed] No expiry timestamp. Skipping token refresh...');
        }

        $diff = $expiryTimestamp - time();
        $hrsLeft = $diff / 60 / 60;

        if ($hrsLeft > 24) {
            return $this->logger->debug('[Magebit_InstagramFeed] Token still valid for ' . round($hrsLeft * 100) / 100 .  ' hours');
        }

        $this->logger->debug('[Magebit_InstagramFeed] Refreshing access token');

        $this->curl->setTimeout(30);

        $this->curl->get(Data::INSTAGRAM_GRAPH_URL . '/refresh_access_token?' . http_build_query([
            'access_token' => $accessToken,
            'grant_type' => 'ig_refresh_token'
        ]));

        $response = json_decode($this->curl->getBody(), true);

        if (isset($response['error'])) {
            return $this->logger->error('[Magebit_InstagramFeed] Failed to refresh token: ' . $response['error']['message']);
        }

        if (!isset($response['access_token'])) {
            return $this->logger->error('[Magebit_InstagramFeed] Invalid access token ' . json_encode($response));
        }

        $this->helper->setAuthToken($response['access_token']);
        $timestamp = time() + intval($response['expires_in']);
        $this->helper->setExpiryTimestamp($timestamp);
        $this->cacheTypeList->cleanType('config');

        $this->logger->info('[Magebit_InstagramFeed] Access token refreshed!');
    }
}
