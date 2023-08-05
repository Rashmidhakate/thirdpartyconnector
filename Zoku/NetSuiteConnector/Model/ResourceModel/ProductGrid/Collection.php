<?php

/**
 * Grid Grid Collection.
 * @category    Zoku
 */
namespace Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid;

/**
 * Product grid collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
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
