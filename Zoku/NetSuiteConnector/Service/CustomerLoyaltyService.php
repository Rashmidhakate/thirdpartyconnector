<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Service;

use Zoku\NetSuiteConnector\Model\Service\StoreConfigManager;
use Zoku\NetSuiteConnector\Logger\Logger;
use Magento\Framework\Serialize\Serializer\Json;
use Zoku\NetSuiteConnector\Service\Service as BaseService;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Reward\Model\RewardFactory;
use Magento\Reward\Model\Reward\HistoryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Zoku\NetSuiteConnector\Api\CustomerServiceInterface;

/**
 * Customer Loyalty Service Class
 *
 */
class CustomerLoyaltyService
{
    /**
     * Core store config
     *
     * @var StoreConfigManager
     */
    protected $scopeConfig;

    /**
     * BaseService
     *
     * @var BaseService
     */
    protected $baseService;

    /**
     * CustomerCollectionFactory
     *
     * @var CustomerCollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * RewardFactory
     *
     * @var RewardFactory
     */
    protected $rewardFactory;

    /**
     * HistoryFactory
     *
     * @var HistoryFactory
     */
    protected $historyFactory;

    /**
     * Store Manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Customer Repository Interface
     *
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Customer Service Interface
     *
     * @var CustomerServiceInterface
     */
    protected $customerServiceInterface;

    /**
     * @param StoreConfigManager $scopeConfig
     * @param Logger $logger
     * @param Json $jsonHelper
     * @param BaseService $baseService
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param RewardFactory $rewardFactory
     * @param HistoryFactory $historyFactory
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerServiceInterface $customerServiceInterface
     */
    public function __construct(
        StoreConfigManager $scopeConfig,
        Logger $logger,
        Json $jsonHelper,
        BaseService $baseService,
        CustomerCollectionFactory $customerCollectionFactory,
        RewardFactory $rewardFactory,
        HistoryFactory $historyFactory,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        CustomerServiceInterface $customerServiceInterface
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->baseService =  $baseService;
        $this->customerCollectionFactory =  $customerCollectionFactory;
        $this->rewardFactory =  $rewardFactory;
        $this->historyFactory =  $historyFactory;
        $this->storeManager =  $storeManager;
        $this->customerRepository =  $customerRepository;
        $this->customerServiceInterface =  $customerServiceInterface;
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
        if (!$this->scopeConfig->getLoyaltyPointsConfig()) {
            return false;
        }
        $responsePayload = $this->baseService->getRequest($endPoint);
        if (!empty($responsePayload)) {
            if (!empty($responsePayload['body'] && !empty($responsePayload['body']['points']))) {
                $customerPoints = $responsePayload['body']['points'];
                $url = parse_url($endPoint);
                $urlPath = explode($this->baseService->getCustomerLoyaltyPath(), $url['path']);
                $customerNetSuiteID = $urlPath[1];
                $customerId = $this->getCustomerObject($customerNetSuiteID);
                $websiteId = $this->storeManager->getWebsite()->getId();
                $customerObj = $this->customerRepository->getById($customerId);
                $reward = $this->rewardFactory->create()->setCustomer($customerObj);
                $reward->setWebsiteId($websiteId);
                $reward->loadByCustomer();
                $reward->setPointsBalance($responsePayload['body']['points']);
                $reward->setAction(\Magento\Reward\Model\Reward::REWARD_ACTION_ADMIN)
                    ->setComment(__('Additional Information Reward'))
                    ->updateRewardPoints();
                $history = $this->historyFactory->create();
                $history->setReward($reward)->prepareFromReward()->save();
                $responsePayload = [
                    'body' => [
                        'fields' => [
                            [
                                'customer_email' => $customerObj->getEmail(),
                                'flag' => '',
                                'customer_netsuite_id' => $customerNetSuiteID,
                                'points' => 300
                            ]
                        ]
                    ]
                ];
                $responseData = $this->jsonHelper->serialize($responsePayload);
                $this->customerServiceInterface->saveCustomerLog($endPoint, $responseData);
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
        $collectionFactory = $collectionFactory->addAttributeToSelect('*')
                ->addAttributeToFilter('zoku_netsuite_customer_id', $customerNetSuiteID)
                ->load();
        foreach ($collectionFactory as $collection) {
            $id = $collection->getEntityId();
        }
        return $id;
    }
}
