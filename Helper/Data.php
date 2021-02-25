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

use LogicException;
use BadMethodCallException;
use InvalidArgumentException;
use Magento\Framework\Url;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use RuntimeException;

/**
 * @package Magebit\InstagramFeed\Helper
 */
class Data extends AbstractHelper
{
    const INSTAGRAM_AUTH_URL = 'https://api.instagram.com/oauth/authorize';
    const INSTAGRAM_ACCESS_URL = 'https://api.instagram.com/oauth/access_token';
    const INSTAGRAM_GRAPH_URL = 'https://graph.instagram.com';
    const CONFIG_APP_ID = 'magebit_instagram/settings/app_id';
    const CONFIG_APP_SECRET = 'magebit_instagram/settings/app_secret';
    const CONFIG_AUTH_TOKEN = 'magebit_instagram/settings/auth_token';
    const CONFIG_TOKEN_EXPIRY = 'magebit_instagram/settings/token_expiry';
    const CONFIG_USER_ID = 'magebit_instagram/settings/user_id';
    const CONFIG_CALLBACK_URL = 'magebit_instagram/settings/callback_url';

    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @param Context $context
     * @param Url $urlHelper
     * @param WriterInterface $writer
     * @return void
     */
    public function __construct(
        Context $context,
        Url $urlHelper,
        WriterInterface $writer
    ) {
        parent::__construct($context);
        $this->urlHelper = $urlHelper;
        $this->writer = $writer;
    }

    /**
     * Get app id from config
     *
     * @return mixed
     */
    public function getAppId()
    {
        return $this->scopeConfig->getValue(self::CONFIG_APP_ID);
    }

    /**
     * Get app secret from config
     *
     * @return mixed
     */
    public function getAppSecret()
    {
        return $this->scopeConfig->getValue(self::CONFIG_APP_SECRET);
    }

    /**
     * Get auth token from config
     *
     * @return mixed
     */
    public function getAuthToken()
    {
        return $this->scopeConfig->getValue(self::CONFIG_AUTH_TOKEN);
    }

    /**
     * Get token timestamp from config
     *
     * @return mixed
     */
    public function getTokenTimestamp()
    {
        return $this->scopeConfig->getValue(self::CONFIG_TOKEN_EXPIRY);
    }
    
    /**
     * Get user id from config
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->scopeConfig->getValue(self::CONFIG_USER_ID);
    }

    /**
     * Get oauth callback url
     *
     * @return string
     * @throws RuntimeException
     * @throws LogicException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    public function getCallbackUrl()
    {
        return $this->urlHelper->getUrl($this->scopeConfig->getValue(self::CONFIG_CALLBACK_URL));
    }

    /**
     * Set auth token
     *
     * @param mixed $token
     * @return void
     */
    public function setAuthToken($token)
    {
        $this->writer->save(self::CONFIG_AUTH_TOKEN, $token);
    }

    /**
     * Set expiry timestamp
     *
     * @param mixed $timestamp
     * @return void
     */
    public function setExpiryTimestamp($timestamp)
    {
        $this->writer->save(self::CONFIG_TOKEN_EXPIRY, $timestamp);
    }

    /**
     * Parse data from Facebook's signed request
     *
     * @param string $signedRequest
     * @return mixed
     */
    public function parseSignedRequest($signedRequest)
    {
        list($encoded_sig, $payload) = explode('.', $signedRequest, 2);

        $secret = $this->getAppSecret();

        // decode the data
        $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }
}
