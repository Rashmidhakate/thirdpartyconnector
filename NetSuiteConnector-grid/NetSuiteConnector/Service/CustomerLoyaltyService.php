<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Service;

use Zoku\NetSuiteConnector\Logger\Logger;
use Magento\Framework\Serialize\Serializer\Json;
use Zoku\NetSuiteConnector\Service\Service as BaseService;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Reward\Model\RewardFactory;
use Magento\Reward\Model\Reward\HistoryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Zoku\NetSuiteConnector\Api\CustomerServiceInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\ResourceConnection;
use Zend\Uri\Http;

/**
 * Customer Loyalty Service Class defined
 *
 */
class CustomerLoyaltyService
{
    /**
     * Base Service defined
     *
     * @var BaseService
     */
    protected $baseService;

    /**
     * Customer Collection Factory defined
     *
     * @var CustomerCollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * Reward Factory defined
     *
     * @var RewardFactory
     */
    protected $rewardFactory;

    /**
     * History Factory defined
     *
     * @var HistoryFactory
     */
    protected $historyFactory;

    /**
     * Store Manager defined
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Customer Repository Interface defined
     *
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Customer Service Interface defined
     *
     * @var CustomerServiceInterface
     */
    protected $customerServiceInterface;

    /**
     * Timezone Interface defined
     *
     * @var TimezoneInterface
     */
    protected $timezoneInterface;

    /**
     * ResourceConnection defined
     *
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * Connection with DB
     *
     * @var connection
     */
    protected $connection;

    /**
     * @param Logger $logger
     * @param Json $jsonHelper
     * @param BaseService $baseService
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param RewardFactory $rewardFactory
     * @param HistoryFactory $historyFactory
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerServiceInterface $customerServiceInterface
     * @param TimezoneInterface $timezoneInterface
     * @param ResourceConnection $resource
     * @param Http $zendUri
     */
    public function __construct(
        Logger $logger,
        Json $jsonHelper,
        BaseService $baseService,
        CustomerCollectionFactory $customerCollectionFactory,
        RewardFactory $rewardFactory,
        HistoryFactory $historyFactory,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        CustomerServiceInterface $customerServiceInterface,
        TimezoneInterface $timezoneInterface,
        ResourceConnection $resource,
        Http $zendUri
    ) {
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->baseService =  $baseService;
        $this->customerCollectionFactory =  $customerCollectionFactory;
        $this->rewardFactory =  $rewardFactory;
        $this->historyFactory =  $historyFactory;
        $this->storeManager =  $storeManager;
        $this->customerRepository =  $customerRepository;
        $this->customerServiceInterface =  $customerServiceInterface;
        $this->timezoneInterface =  $timezoneInterface;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->zendUri = $zendUri;
    }

    /**
     * Save customer log
     *
     * @param string $endPoint
     * @return bool
     * @throws Exception
     */
    public function saveCustomerLoyaltyById($endPoint)
    {
        $logger = $this->logger;
        if (!$this->baseService->getLoyaltyPointsConfig() || !$this->baseService->getModuleEnable()) {
            return false;
        }
        $responsePayload = $this->baseService->getRequest($endPoint);
        if (!empty($responsePayload)) {
            if (!empty($responsePayload['body'] && !empty($responsePayload['body']['points']))) {
                $customerPoints = $responsePayload['body']['points'];
                $url = $this->zendUri->parse($endPoint);
                $urlPath = explode($this->baseService->getCustomerLoyaltyPath(), $url->getPath());
                $customerNetSuiteID = $urlPath[1];
                $customerId = $this->getCustomerObject($customerNetSuiteID);
                $websiteId = $this->storeManager->getWebsite()->getId();
                $customerObj = $this->customerRepository->getById($customerId);
                $reward = $this->rewardFactory->create()->setCustomer($customerObj);
                $reward->setWebsiteId($websiteId);
                $reward->loadByCustomer();
                $reward->setPointsBalance($responsePayload['body']['points']);
                $reward->setAction(\Magento\Reward\Model\Reward::REWARD_ACTION_ADMIN)
                    ->setComment(__('Updated from NetSuite'))
                    ->updateRewardPoints();
                $history = $this->historyFactory->create();
                $history->setReward($reward)->prepareFromReward()->save();
                $responsePayloadData = [
                    'body' => [
                        'fields' => [
                            [
                                'customer_email' => $customerObj->getEmail(),
                                'flag' => '',
                                'customer_netsuite_id' => $customerNetSuiteID,
                                'request_payload' => $endPoint,
                                'response_payload' => $this->jsonHelper->serialize($responsePayload)
                            ]
                        ]
                    ]
                ];
                $responseData = $this->jsonHelper->serialize($responsePayloadData);
                $this->customerServiceInterface->saveCustomerLoyaltyLog($endPoint, $responseData);
                $logger->info('Rewards updated');
            }
        } else {
            $logger->info('Response is empty');
        }
        return true;
    }

    /**
     * Get customer object using zoku_netsuite_customer_id
     *
     * @param int $customerNetSuiteID
     * @return int
     * @throws Exception
     */
    public function getCustomerObject($customerNetSuiteID)
    {
        $id = '';
        $collectionFactory = $this->customerCollectionFactory->create();
        $collectionFactory = $collectionFactory->addAttributeToSelect(['zoku_netsuite_customer_id','entity_id'])
                ->addAttributeToFilter('zoku_netsuite_customer_id', $customerNetSuiteID)
                ->load();
        foreach ($collectionFactory as $collection) {
            $id = $collection->getEntityId();
        }
        return $id;
    }

    /**
     * Save customer loyalty points history
     *
     * @param string $endPoint
     * @return bool
     * @throws Exception
     */
    public function saveCustomerLoyaltyPointHistory($endPoint)
    {
        if (!$this->baseService->getModuleEnable() || !$this->baseService->getLoyaltyPointsConfig()) {
            return false;
        }
        $logger = $this->logger;
        //$responsePayload = $this->baseService->getRequest($endPoint);

        $responsePayload = [
            'body' =>[
                'list' => [
                    [
                        'date' => '03-07-2023',
                        'memo' => 'MRC-007',
                        'points' => 100
                    ],
                    [
                        'date' => '03-07-2023',
                        'memo' => 'MRC-008',
                        'points' => 200
                    ],
                ]
            ]
        ];
        if (!empty($responsePayload)) {
            if (!empty($responsePayload['body'] && !empty($responsePayload['body']['list']))) {
                $table = 'zoku_loyalty_points_history';
                $url = $this->zendUri->parse($endPoint);
                $urlPath = explode($this->baseService->getCustomerLoyaltyHistoryPath(), $url->getPath());
                $customerNetSuiteID = $urlPath[1];
                $customerLoyaltyPoint = $responsePayload['body']['list'];
                $customerId = $this->getCustomerObject($customerNetSuiteID);
                $customerObj = $this->customerRepository->getById($customerId);
                $customerLoyaltyPointArray = [];
                $responsePayloadData = [];
                $responsePayloadData['body']['fields'] = [];
                foreach ($customerLoyaltyPoint as $key => $value) {
                    $date = $this->timezoneInterface->date($customerLoyaltyPoint[$key]['date'])->format('Y-m-d H:i:s');
                    $transactionId = 0;
                    if (isset($customerLoyaltyPoint[$key]['transaction_id'])) {
                        $transactionId = $customerLoyaltyPoint[$key]['transaction_id'];
                    }
                    $customerLoyaltyPointArray[] = [
                        'customer_id' => $customerId,
                        'transaction_id' => $transactionId,
                        'memo' => $customerLoyaltyPoint[$key]['memo'],
                        'points' => $customerLoyaltyPoint[$key]['points'],
                        'date' => $date
                    ];
                    $responsePayloadData['body']['fields'][] = [
                        'customer_email' => $customerObj->getEmail(),
                        'flag' => '',
                        'customer_netsuite_id' => $customerNetSuiteID,
                        'request_payload' => $endPoint,
                        'response_payload' => $this->jsonHelper->serialize($responsePayload)
                    ];
                }
                try {
                    $tableName = $this->resource->getTableName($table);
                    $this->connection->insertMultiple($tableName, $customerLoyaltyPointArray);
                    $logger->info('Customer Loyalty Point Saved');
                    $responseData = $this->jsonHelper->serialize($responsePayloadData);
                    $this->customerServiceInterface->saveCustomerLoyaltyLog($endPoint, $responseData);
                } catch (\Exception $e) {
                    $logger->info($e->getMessage());
                }
            }
        } else {
            $logger->info('Response is empty');
        }
        return true;
    }
}
