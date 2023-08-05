<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as AbstractCollection;

/**
 * Customer Loyalty Point Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Zoku\NetSuiteConnector\Model\CustomerLoyaltyPoint::class,
            \Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint::class
        );
    }
}
