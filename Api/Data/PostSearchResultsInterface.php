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
namespace Magebit\InstagramFeed\Api\Data;

/**
 * @package Magebit\InstagramFeed\Api\Data
 */
interface PostSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Post list.
     * @return \Magebit\InstagramFeed\Api\Data\PostInterface[]
     */
    public function getItems();

    /**
     * Set media list.
     * @param \Magebit\InstagramFeed\Api\Data\PostInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
