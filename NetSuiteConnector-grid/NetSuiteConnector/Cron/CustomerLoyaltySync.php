<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Cron;

use Zoku\NetSuiteConnector\Model\MessageQueue\CustomerLoyalty\Sync;
use Zoku\NetSuiteConnector\Logger\Logger;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\MessageQueue\PublisherInterface;

/**
 * Customer Loyalty Sync class defined
 */
class CustomerLoyaltySync
{
    /**
     * @var Data
     */
    private $jsonHelper;

    /**
     * @var PublisherInterface
     */
    private $publisher;

     /**
      * @var Logger
      */
    private $logger;

    /**
     * @param Data $jsonHelper
     * @param PublisherInterface $publisher
     * @param Logger $logger
     */
    public function __construct(
        Data $jsonHelper,
        PublisherInterface $publisher,
        Logger $logger
    ) {
        $this->publisher = $publisher;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $details[] = [
            "any_informatic_index" => "value",
        ];

        $this->publisher->publish(
            Sync::TOPIC_NAME,
            $this->jsonHelper->jsonEncode($details)
        );
        $logger = $this->logger;
        $logger->info('Customer loyalty points initiated');
    }
}
