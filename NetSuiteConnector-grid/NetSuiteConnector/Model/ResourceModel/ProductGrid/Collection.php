<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as AbstractCollection;

/**
 * Product grid collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'log_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Zoku\NetSuiteConnector\Model\ProductGrid::class,
            \Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid::class
        );
    }
}
