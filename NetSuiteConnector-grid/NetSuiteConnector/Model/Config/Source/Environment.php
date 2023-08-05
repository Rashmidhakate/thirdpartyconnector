<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Environment modes defined
 */
class Environment implements OptionSourceInterface
{
    /**
     * Options to Array
     *
     * @return array
     */
    public function toOptionArray()
    {

        return [
            0 => __('Please Select'),
            1 => __('Staging'),
            2 => __('Production')
        ];
    }
}
