<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Cron;

use Zoku\NetSuiteConnector\Model\MessageQueue\SyncCustomer;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Json\Helper\Data;

/**
 * Sync customer to magento from netsuite
 */
class SyncCustomers
{
    /**
     * Publisher interface defined
     *
     * @var PublisherInterface
     */
    protected $publisher;
    
    /**
     * Json helper defined
     *
     * @var Data
     */
    protected $jsonHelper;

    /**
     * Constructor defined
     *
     * @param PublisherInterface $publisher
     * @param Data               $jsonHelper
     */
    public function __construct(
        PublisherInterface $publisher,
        Data $jsonHelper
    ) {
        $this->publisher            = $publisher;
        $this->jsonHelper           = $jsonHelper;
    }

    /**
     * Cron execution
     */
    public function execute()
    {
        $details[] = [
            "any_informatic_index" => "value",
        ];

        $this->publisher->publish(
            SyncCustomer::TOPIC_NAME,
            $this->jsonHelper->jsonEncode($details)
        );
    }
}
