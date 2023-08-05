<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\Service;

use Zoku\NetSuiteConnector\Model\Service\StoreConfigManager;
use Zoku\NetSuiteConnector\Logger\Logger;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Zoku\NetSuiteConnector\Api\CustomerGridRepositoryInterface;
use Zoku\NetSuiteConnector\Model\ResourceModel\CustomerGrid\CollectionFactory;

/**
 * Customer Service Class defined
 *
 */
class CustomerService implements \Zoku\NetSuiteConnector\Api\CustomerServiceInterface
{
    /**
     * Core store config
     *
     * @var StoreConfigManager
     */
    protected $scopeConfig;

    /**
     * Connection with DB
     *
     * @var connection
     */
    protected $connection;

    /**
     * ResourceConnection
     *
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * TimezoneInterface
     *
     * @var TimezoneInterface
     */
    protected $date;

    /**
     * CustomerGridRepositoryInterface
     *
     * @var CustomerGridRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Customer CollectionFactory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param StoreConfigManager $scopeConfig
     * @param Logger $logger
     * @param Json $jsonHelper
     * @param ResourceConnection $resource
     * @param TimezoneInterface $date
     * @param CustomerGridRepositoryInterface $customerRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        StoreConfigManager $scopeConfig,
        Logger $logger,
        Json $jsonHelper,
        ResourceConnection $resource,
        TimezoneInterface $date,
        CustomerGridRepositoryInterface $customerRepository,
        CollectionFactory $collectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->resource = $resource;
        $this->date =  $date;
        $this->customerRepository =  $customerRepository;
        $this->collectionFactory =  $collectionFactory;
        $this->connection = $resource->getConnection();
    }

    /**
     * Save customer log
     *
     * @param string $requestPayload
     * @param string $responsePayload
     * @return bool
     * @throws Exception
     */
    public function saveCustomerLog($requestPayload, $responsePayload)
    {
        if (!$this->scopeConfig->getLogConfig()) {
            return false;
        }
        $request = $this->jsonHelper->unserialize($responsePayload);
        $logger = $this->logger;
        $table = 'zoku_connector_customer_log';
        if (!empty($request['body'] && !empty($request['body']['fields']))) {
            $customerList = $request['body']['fields'];
            $customerArray = [];
            foreach ($customerList as $key => $value) {
                $customerArray[] = [
                    'customer_email' => $customerList[$key]['customer_email'],
                    'flag' => $customerList[$key]['flag'],
                    'customer_netsuite_id' => $customerList[$key]['customer_netsuite_id'],
                    'request_payload' => $requestPayload,
                    'response_payload' => $responsePayload
                ];
            }
            try {
                $tableName = $this->resource->getTableName($table);
                $this->connection->insertMultiple($tableName, $customerArray);
                $logger->info('Customer Log saved');
            } catch (\Exception $e) {
                $logger->info($e->getMessage());
            }
        } else {
            $logger->info('Response is empty');
        }
        return true;
    }

    /**
     * Delete customer log
     *
     * @param int $days
     * @return bool
     * @throws Exception
     */
    public function deleteCustomerLog($days)
    {
        $logger = $this->logger;
        if ($days) {
            $days = "-".$days." DAYS";
        }
        $expire = strtotime($days);
        $date = $this->date->date($expire)->format('Y-m-d');
        $logger->info('Expiry Date :- '.$date);
        $customerCollection = $this->collectionFactory->create();
        $customerCollection->addFieldToFilter(
            'updated_at',
            ['lt'=>$date]
        );
        $logger->info('Count :- '.$customerCollection->getSize());
        if ($customerCollection->getSize() > 0) {
            $count = 0;
            foreach ($customerCollection as $customer) {
                $this->customerRepository->deleteById($customer->getLogId());
                $count++;
            }
            $logger->info('Customer remove log executed');
            $logger->info('Customer remove log count :- '.$count);
        } else {
            $logger->info('Customer remove data is empty');
        }
    }
}
