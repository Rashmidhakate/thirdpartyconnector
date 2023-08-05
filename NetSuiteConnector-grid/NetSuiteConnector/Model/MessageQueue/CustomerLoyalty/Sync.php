<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\MessageQueue\CustomerLoyalty;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Zoku\NetSuiteConnector\Logger\Logger;
use Zoku\NetSuiteConnector\Service\CustomerLoyaltyService;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Zoku\NetSuiteConnector\Service\Service as BaseService;

/**
 * Customer Loyalty Sync Consumer
 */
class Sync extends ConsumerConfiguration
{

    public const TOPIC_NAME = "customerLoyaltySyncTopic";

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CustomerLoyaltyService
     */
    private $customerLoyaltyService;

    /**
     * @var CustomerCollectionFactory
     */
    private $customerCollectionFactory;

    /**
     * @var BaseService
     */
    private $baseService;

    /**
     * Construct
     *
     * @param Logger $logger
     * @param CustomerLoyaltyService $customerLoyaltyService
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param BaseService $baseService
     */
    public function __construct(
        Logger $logger,
        CustomerLoyaltyService $customerLoyaltyService,
        CustomerCollectionFactory $customerCollectionFactory,
        BaseService $baseService
    ) {
        $this->logger = $logger;
        $this->customerLoyaltyService = $customerLoyaltyService;
        $this->customerCollectionFactory =  $customerCollectionFactory;
        $this->baseService =  $baseService;
    }

    /**
     * Consumer process start
     *
     * @param mixed $request
     * @return null
     * @throws \Zend_Log_Exception
     */
    public function process($request)
    {
        if (!$this->baseService->getLoyaltyPointsConfig()) {
            return false;
        }
        $logger = $this->logger;
        $logger->info("Customer loyalty sync execution initiated");
        $collectionFactory = $this->customerCollectionFactory->create();
        $collectionFactory = $collectionFactory->addAttributeToSelect('zoku_netsuite_customer_id')
                ->load();
        foreach ($collectionFactory as $collection) {
            $customerNetsuiteId = $collection->getZokuNetsuiteCustomerId();
            $url = $this->baseService->getCustomerLoyaltyPath().$customerNetsuiteId.'?fetch';
            $endpoint = $this->baseService->createUrl($url);
            $this->customerLoyaltyService->saveCustomerLoyaltyById($endpoint);
            $logger->info($endpoint);
        }
    }
}
