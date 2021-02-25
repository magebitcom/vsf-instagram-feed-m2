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
namespace Magebit\InstagramFeed\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magebit\InstagramFeed\Helper\Data;

/**
 * @package Magebit\InstagramFeed\Block\System\Config
 */
class AuthToken extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Magebit_InstagramFeed::system/config/auth_token.phtml';

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param Context $context
     * @param Data $helper
     * @param array $data
     * @return void
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setReadonly(true, true);
        $element->setData('after_element_html', $this->_toHtml());
        return $element->getElementHtml();
    }

    /**
     * Get auth token
     *
     * @return mixed
     */
    public function getAuthToken()
    {
        return $this->helper->getAuthToken();
    }

    /**
     * Has required fields to request auth token
     *
     * @return bool
     */
    public function hasRequiredFields()
    {
        return $this->helper->getAppId() && $this->helper->getAppSecret();
    }

    /**
     * Is token expired
     *
     * @return bool
     */
    public function isExpired()
    {
        $timestamp = $this->helper->getTokenTimestamp();
        return time() > $timestamp;
    }
    
    /**
     * Auth token expiry time
     *
     * @return string|null
     */
    public function getExpiryDate()
    {
        $timestamp = $this->helper->getTokenTimestamp();

        if ($timestamp) {
            $diff = $timestamp - time();
            $hours = floor($diff / 60 / 60);
            $days = floor($diff / 60 / 60 / 24 * 100) / 100;
            return "{$hours} hours ({$days} days)";
        }

        return null;
    }

    /**
     * Get instagram auth url
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return Data::INSTAGRAM_AUTH_URL . '?' . http_build_query([
            'client_id' => $this->helper->getAppId(),
            'scope' => 'user_profile,user_media',
            'response_type' => 'code',
            'redirect_uri' => $this->helper->getCallbackUrl()
        ]);
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'ig_auth_token',
                'label' => __('Fetch access token'),
            ]
        );

        return $button->toHtml();
    }
}
