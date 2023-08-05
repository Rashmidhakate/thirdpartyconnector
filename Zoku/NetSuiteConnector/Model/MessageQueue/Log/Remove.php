<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\MessageQueue\Log;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Zoku\NetSuiteConnector\Logger\Logger;
use Zoku\NetSuiteConnector\Model\Service\StoreConfigManager;
use Zoku\NetSuiteConnector\Api\CustomerServiceInterface;
use Zoku\NetSuiteConnector\Api\ProductServiceInterface;
use Zoku\NetSuiteConnector\Api\OrderServiceInterface;

/**
 * Class Import
 */
class Remove extends ConsumerConfiguration
{

    public const TOPIC_NAME = "logRemoveTopic";

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var StoreConfigManager
     */
    private $storeConfigManager;

    /**
     * @var CustomerServiceInterface
     */
    private $customerService;

    /**
     * @var ProductServiceInterface
     */
    private $productService;

    /**
     * @var OrderServiceInterface
     */
    private $orderService;

    /**
     * Construct
     *
     * @param Logger $logger
     * @param StoreConfigManager $storeConfigManager
     * @param CustomerServiceInterface $customerService
     * @param ProductServiceInterface $productService
     * @param OrderServiceInterface $orderService
     */
    public function __construct(
        Logger $logger,
        StoreConfigManager $storeConfigManager,
        CustomerServiceInterface $customerService,
        ProductServiceInterface $productService,
        OrderServiceInterface $orderService
    ) {
        $this->logger = $logger;
        $this->storeConfigManager = $storeConfigManager;
        $this->customerService = $customerService;
        $this->productService = $productService;
        $this->orderService = $orderService;
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
        if (!$this->storeConfigManager->getLogConfig()) {
            return false;
        }
        $logger = $this->logger;
        $days = $this->storeConfigManager->getNoOfDayConfig();
        $logger->info($days);

        /****************** Remove customer log ************************/
        $this->customerService->deleteCustomerLog($days);

        /****************** Remove product log ************************/
        $this->productService->deleteProductLog($days);

        /****************** Remove order log ************************/
        $this->orderService->deleteOrderLog($days);

        $logger->info('All entities remove log executed');
    }
}
