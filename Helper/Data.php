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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;

/**
 * @package Magebit\InstagramFeed\Helper
 */
class Data extends AbstractHelper
{
    const MEDIA_DIR = 'instagramfeed';

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @param Context $context
     * @param DirectoryList $curl
     * @return void
     */
    public function __construct(Context $context, DirectoryList $directoryList)
    {
        parent::__construct($context);
        $this->directoryList = $directoryList;
    }

    /**
     * Get directory of saved instagram images
     *
     * @return string
     * @throws FileSystemException
     */
    public function getMediaDir()
    {
        return $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . self::MEDIA_DIR . DIRECTORY_SEPARATOR;
    }
}
