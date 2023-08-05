<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\Service;

use Zoku\NetSuiteConnector\Model\Service\StoreConfigManager;
use Zoku\NetSuiteConnector\Logger\Logger;
use Zoku\NetSuiteConnector\Api\ProductGridRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Product Service Class defined
 */
class ProductService implements \Zoku\NetSuiteConnector\Api\ProductServiceInterface
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
     * ProductGridRepositoryInterface
     *
     * @var ProductGridRepositoryInterface
     */
    protected $productRepository;

    /**
     * Product Grid CollectionFactory
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
     * @param ProductGridRepositoryInterface $productRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        StoreConfigManager $scopeConfig,
        Logger $logger,
        Json $jsonHelper,
        ResourceConnection $resource,
        TimezoneInterface $date,
        ProductGridRepositoryInterface $productRepository,
        CollectionFactory $collectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
        $this->date =  $date;
        $this->productRepository =  $productRepository;
        $this->collectionFactory =  $collectionFactory;
    }

    /**
     * Save product log
     *
     * @param string $requestPayload
     * @param string $responsePayload
     * @return bool
     * @throws Exception
     */
    public function saveProductLog($requestPayload, $responsePayload)
    {
        if (!$this->scopeConfig->getLogConfig()) {
            return false;
        }
        $request = $this->jsonHelper->unserialize($responsePayload);
        $logger = $this->logger;
        $table = 'zoku_connector_products_log';
        if (!empty($request['body'] && !empty($request['body']['list']))) {
            $productList = $request['body']['list'];
            $productArray = [];
            foreach ($productList as $key => $value) {
                $productArray[] = [
                    'sku' => $productList[$key]['sku'],
                    'price' => $productList[$key]['price'],
                    'qty' => $productList[$key]['qty'],
                    'product_netsuite_id' => $productList[$key]['product_netsuite_id'],
                    'request_payload' => $requestPayload,
                    'response_payload' => $responsePayload
                ];
            }
            try {
                $tableName = $this->resource->getTableName($table);
                $this->connection->insertMultiple($tableName, $productArray);
                $logger->info('Product Log saved');
            } catch (\Exception $e) {
                $logger->info($e->getMessage());
            }
        } else {
            $logger->info('Response is empty');
        }
        return true;
    }

    /**
     * Delete product log
     *
     * @param int $days
     * @return bool
     * @throws Exception
     */
    public function deleteProductLog($days)
    {
        $logger = $this->logger;
        if ($days) {
            $days = "-".$days." DAYS";
        }
        $expire = strtotime($days);
        $date = $this->date->date($expire)->format('Y-m-d');
        $logger->info('Expiry Date :- '.$date);
        $productCollection = $this->collectionFactory->create();
        $productCollection->addFieldToFilter(
            'updated_at',
            ['lt'=>$date]
        );
        $logger->info('Count :- '.$productCollection->getSize());
        if ($productCollection->getSize() > 0) {
            $count = 0;
            foreach ($productCollection as $product) {
                $this->productRepository->deleteById($product->getLogId());
                $count++;
            }
            $logger->info('Product remove log executed');
            $logger->info('Product remove log count :- '.$count);
        } else {
            $logger->info('Product remove data is empty');
        }
    }
}
