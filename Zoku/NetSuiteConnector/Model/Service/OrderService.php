<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\Service;

use Zoku\NetSuiteConnector\Model\Service\StoreConfigManager;
use Zoku\NetSuiteConnector\Logger\Logger;
use Zoku\NetSuiteConnector\Api\OrderGridRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Zoku\NetSuiteConnector\Model\ResourceModel\OrderGrid\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Order Service Class defined
 */
class OrderService implements \Zoku\NetSuiteConnector\Api\OrderServiceInterface
{
    /**
     * Core store config
     *
     * @var StoreConfigManager
     */
    protected $scopeConfig;

    /**
     * Resource Connection
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
     * OrderGridRepositoryInterface
     *
     * @var OrderGridRepositoryInterface
     */
    protected $orderRepository;

    /**
     * Order CollectionFactory
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
     * @param OrderGridRepositoryInterface $orderRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        StoreConfigManager $scopeConfig,
        Logger $logger,
        Json $jsonHelper,
        ResourceConnection $resource,
        TimezoneInterface $date,
        OrderGridRepositoryInterface $orderRepository,
        CollectionFactory $collectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
        $this->date =  $date;
        $this->orderRepository =  $orderRepository;
        $this->collectionFactory =  $collectionFactory;
    }

    /**
     * Save order log
     *
     * @param string $requestPayload
     * @param string $responsePayload
     * @return bool
     * @throws Exception
     */
    public function saveOrderLog($requestPayload, $responsePayload)
    {
        if (!$this->scopeConfig->getLogConfig()) {
            return false;
        }
        $request = $this->jsonHelper->unserialize($responsePayload);
        $logger = $this->logger;
        $table = 'zoku_connector_order_log';
        if (!empty($request['body'] && !empty($request['body']['fields']))) {
            $orderList = $request['body']['fields'];
            $orderArray = [];
            foreach ($orderList as $key => $value) {
                $orderArray[] = [
                    'email' => $orderList[$key]['email'],
                    'increment_id' => $orderList[$key]['increment_id'],
                    'order_netsuite_id' => $orderList[$key]['order_netsuite_id'],
                    'request_payload' => $requestPayload,
                    'response_payload' => $responsePayload
                ];
            }
            try {
                $tableName = $this->resource->getTableName($table);
                $this->connection->insertMultiple($tableName, $orderArray);
                $logger->info('Order Log saved');
            } catch (\Exception $e) {
                $logger->info($e->getMessage());
            }
        } else {
            $logger->info('Response is empty');
        }
        return true;
    }

    /**
     * Delete order log
     *
     * @param int $days
     * @return bool
     * @throws Exception
     */
    public function deleteOrderLog($days)
    {
        $logger = $this->logger;
        if ($days) {
            $days = "-".$days." DAYS";
        }
        $expire = strtotime($days);
        $date = $this->date->date($expire)->format('Y-m-d');
        $logger->info('Expiry Date :- '.$date);
        $orderCollection = $this->collectionFactory->create();
        $orderCollection->addFieldToFilter(
            'updated_at',
            ['lt'=>$date]
        );
        $logger->info('Count :- '.$orderCollection->getSize());
        if ($orderCollection->getSize() > 0) {
            $count = 0;
            foreach ($orderCollection as $order) {
                $this->orderRepository->deleteById($order->getLogId());
                $count++;
            }
            $logger->info('Order remove log executed');
            $logger->info('Order remove log count :- '.$count);
        } else {
            $logger->info('Order remove data is empty');
        }
    }
}
