<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Sync Product Price Options
 */
class SyncProductPrice implements OptionSourceInterface
{
    /**
     * Options to Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            1 => __('Including Tax'),
            2 => __('Excluding Tax')
        ];
    }
}
