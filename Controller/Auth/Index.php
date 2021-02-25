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
namespace Magebit\InstagramFeed\Controller\Auth;

use BadMethodCallException;
use InvalidArgumentException;
use Exception;
use LogicException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\HTTP\Client\Curl;
use Magebit\InstagramFeed\Helper\Data;
use Magento\Framework\App\Cache\TypeListInterface;
use RuntimeException;

/**
 * @package Magebit\InstagramFeed\Controller\Auth
 */
class Index extends Action
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;
    
    /**
     * @param Context $context
     * @param UrlInterface $urlBuilder
     * @param ManagerInterface $messageManager
     * @param PageFactory $resultPageFactory
     * @param Curl $curl
     * @param Data $helper
     * @return void
     */
    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        ManagerInterface $messageManager,
        PageFactory $resultPageFactory,
        Curl $curl,
        Data $helper,
        TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context);
        $this->urlBuilder = $urlBuilder;
        $this->messageManager = $messageManager;
        $this->resultPageFactory = $resultPageFactory;
        $this->curl = $curl;
        $this->helper = $helper;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $code = $this->getRequest()->getParam('code');

        if ($code) {
            $accessToken = $this->getAccessToken($code);
            $data = $this->getLongLivedToken($accessToken);
            $this->helper->setAuthToken($data['access_token']);
            $timestamp = time() + intval($data['expires_in']);
            $this->helper->setExpiryTimestamp($timestamp);
        }

        $this->cacheTypeList->cleanType('config');
        return $this->resultPageFactory->create();
    }

    /**
     * Fetches instagram access token
     *
     * @param mixed $code
     * @return mixed
     * @throws RuntimeException
     * @throws LogicException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function getAccessToken($code)
    {
        $this->curl->setTimeout(30);

        $this->curl->post(Data::INSTAGRAM_ACCESS_URL, [
            'client_id' => $this->helper->getAppId(),
            'client_secret' => $this->helper->getAppSecret(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->helper->getCallbackUrl(),
            'code' => $code
        ]);

        $response = json_decode($this->curl->getBody(), true);

        if (isset($response['error_message'])) {
            throw new \Exception($response['error_message']);
        }

        if (!isset($response['access_token'])) {
            throw new \Exception('Invalid access token');
        }

        return $response['access_token'];
    }

    /**
     * Fetches long lived token
     *
     * @param mixed $token
     * @return exit
     * @throws Exception
     */
    protected function getLongLivedToken($token)
    {
        $this->curl->setTimeout(30);

        $this->curl->get(Data::INSTAGRAM_GRAPH_URL . '/access_token?' . http_build_query([
            'access_token' => $token,
            'client_secret' => $this->helper->getAppSecret(),
            'grant_type' => 'ig_exchange_token'
        ]));

        $response = json_decode($this->curl->getBody(), true);

        if (isset($response['error_message'])) {
            throw new \Exception($response['error_message']);
        }

        if (!isset($response['access_token'])) {
            throw new \Exception('Invalid access token');
        }

        return $response;
    }
}
