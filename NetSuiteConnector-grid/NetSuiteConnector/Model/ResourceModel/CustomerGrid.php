<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb as AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Grid Grid mysql resource.
 */
class CustomerGrid extends AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'log_id';
    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * Construct.
     *
     * @param Context $context
     * @param DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('zoku_connector_customer_log', 'log_id');
    }
}
