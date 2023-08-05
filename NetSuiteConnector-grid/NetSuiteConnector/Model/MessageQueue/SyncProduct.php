<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\MessageQueue;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Zoku\NetSuiteConnector\Model\Service\SyncProductSave;

class SyncProduct extends ConsumerConfiguration
{
    /**
     * Topic name defined for cron
     */
    public const TOPIC_NAME = "syncProductTopic";

    /**
     * Constructor defined
     *
     * @param SyncProductSave $syncProductSave
     */
    public function __construct(
        SyncProductSave $syncProductSave
    ) {
        $this->syncProductSave          = $syncProductSave;
    }

    /**
     * Update/Add products
     *
     * @param  string $request
     */
    public function process($request)
    {
        $data = $this->syncProductSave->saveProduct();
    }
}
