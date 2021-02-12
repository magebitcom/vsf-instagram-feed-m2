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

namespace Magebit\InstagramFeed\Helper;

use Exception;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * @package Magebit\InstagramFeed\Helper
 */
class Instagram extends AbstractHelper
{
    const CONFIG_PROFILE_ID = 'magebit_instagram/feed/profile_id';
    const CONFIG_QUERY_ID = 'magebit_instagram/feed/query_id';

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @param Context $context
     * @param Curl $curl
     * @return void
     */
    public function __construct(Context $context, Curl $curl)
    {
        parent::__construct($context);
        $this->curl = $curl;
    }

    /**
     * Fetches latest posts from the public GQL endpoint
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchPosts()
    {
        $profileId = $this->scopeConfig->getValue(self::CONFIG_PROFILE_ID);
        $queryId = $this->scopeConfig->getValue(self::CONFIG_QUERY_ID);

        if (!$profileId || !$queryId) {
            throw new \Exception('Missing profileId or queryId');
        }

        $this->curl->setTimeout(30);
        $this->curl->get("https://www.instagram.com/graphql/query/?query_id={$queryId}&variables={\"id\":{$profileId},\"first\":20,\"after\":null}");
        $response = json_decode($this->curl->getBody(), true);

        if (isset($response['data']['user']['edge_owner_to_timeline_media']['edges'])) {
            $edges = $response['data']['user']['edge_owner_to_timeline_media']['edges'];
            return $edges;
        } else {
            throw new \Exception('Received invalid data from instagram');
        }

        return null;
    }
}
