<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\MessageQueue;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Zoku\NetSuiteConnector\Service\Service;

/**
 * Consumer cron customer class defined
 */
class SyncCustomer extends ConsumerConfiguration
{
    /**
     * Topic name defined for cron
     */
    public const TOPIC_NAME = "syncCustomerTopic";

    /**
     * Service class defined
     *
     * @var Service
     */
    protected $service;

    /**
     * Constructor defined
     *
     * @param Service $service
     */
    public function __construct(
        Service $service
    ) {
        $this->service      = $service;
    }

    /**
     * Syncing customers
     *
     * @param  string $request
     */
    public function process($request)
    {
        /*Logic part is pending*/
        $data = [];
        $this->service->getUpsertCustomer($data);
    }
}
