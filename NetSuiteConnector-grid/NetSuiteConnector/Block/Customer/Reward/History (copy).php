<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer account reward history block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Zoku\NetSuiteConnector\Block\Customer\Reward;

use Magento\Reward\Block\Customer\Reward\History as RewardHistory;
use Zoku\NetSuiteConnector\Service\Service as BaseService;
use Magento\Framework\View\Element\Template\Context as Context;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Reward\Helper\Data as RewardData;
use Magento\Customer\Helper\Session\CurrentCustomer as CurrentCustomer;
use Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory as HistoryFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Api\Data\CustomerInterface;
use Zoku\NetSuiteConnector\Api\CustomerLoyaltyPointRepositoryInterface as CustomerLoyaltyPointRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Zoku\NetSuiteConnector\Logger\Logger;
use Zoku\NetSuiteConnector\Service\CustomerLoyaltyService as CustomerLoyaltyService;
/**
 * @api
 * @since 100.0.2
 */
class History extends RewardHistory
{
    /**
     * History records collection
     *
     * @var \Magento\Reward\Model\ResourceModel\Reward\History\Collection
     */
    protected $_collection = null;

    /**
     * Reward data
     *
     * @var \Magento\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory
     */
    protected $_historyFactory;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * Base Service defined
     *
     * @var BaseService
     */
    protected $baseService;

    /**
     * Search Criteria Builder Defined
     *
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Customer Loyalty Points Repository Interface Defined
     *
     * @var CustomerLoyaltyPointRepositoryInterface
     */
    protected $customerLoyaltyPointRepository;

    /**
     * Customer Repository Interface Defined
     *
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Filter Builder Defined
     *
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * Logger Defined
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Customer Loyalty Service Defined
     *
     * @var CustomerLoyaltyService
     */
    protected $customerLoyaltyService;

    /**
     * @param Context $context
     * @param PricingHelper $pricingHelper
     * @param RewardData $rewardData
     * @param CurrentCustomer $currentCustomer
     * @param HistoryFactory $historyFactory
     * @param BaseService $baseService
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CustomerLoyaltyPointRepositoryInterface $customerLoyaltyPointRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param FilterBuilder $filterBuilder
     * @param CustomerLoyaltyService $customerLoyaltyService
     * @param Logger $logger
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        PricingHelper $pricingHelper,
        RewardData $rewardData,
        CurrentCustomer $currentCustomer,
        HistoryFactory $historyFactory,
        BaseService $baseService,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerLoyaltyPointRepositoryInterface $customerLoyaltyPointRepository,
        CustomerRepositoryInterface $customerRepository,
        FilterBuilder $filterBuilder,
        CustomerLoyaltyService $customerLoyaltyService,
        Logger $logger,
        array $data = []
    ) {
        $this->pricingHelper = $pricingHelper;
        $this->_rewardData = $rewardData;
        $this->currentCustomer = $currentCustomer;
        $this->_historyFactory = $historyFactory;
        $this->baseService =  $baseService;
        $this->searchCriteriaBuilder =  $searchCriteriaBuilder;
        $this->customerLoyaltyPointRepository =  $customerLoyaltyPointRepository;
        $this->customerRepository =  $customerRepository;
        $this->filterBuilder =  $filterBuilder;
        $this->customerLoyaltyService =  $customerLoyaltyService;
        $this->logger = $logger;
        parent::__construct(
            $context,
            $pricingHelper,
            $rewardData,
            $currentCustomer,
            $historyFactory,
            $data
        );
    }

    /**
     * Whether the history is supposed to be rendered
     *
     * @return bool
     */
    public function checkEnabled()
    {
        $flag = true;
        if (!$this->baseService->getLoyaltyPointsConfig() || !$this->baseService->getModuleEnable()){
            $flag = false;
        }
        return $flag;
    }

    /**
     * Fetch current customer id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->currentCustomer->getCustomerId();
    }

    /**
     * Fetch current customer id
     *
     * @param int $customerId
     * @return int
     */
    public function getCustomerNetsuiteId($customerId)
    {
        $customer  = $this->customerRepository->getById($customerId);
        return $customer->getCustomAttribute('zoku_netsuite_customer_id')->getValue();
    }

    /**
     * Fetch current customer id
     *
     * @param int $customerId
     * @return int
     */
    public function getOfflinePointsHistory($customerId)
    {
        $customerNetsuiteId = $this->getCustomerNetsuiteId($customerId);
        $logger = $this->logger;
        $logger->info("Customer loyalty sync execution initiated from myaccount section");
        $url = $this->baseService->getCustomerLoyaltyHistoryPath().$customerNetsuiteId.'?fetch';
        $endpoint = $this->baseService->createUrl($url);
        $this->customerLoyaltyService->saveCustomerLoyaltyPointHistory($endpoint);
        $logger->info($endpoint);
        $filter = $this->filterBuilder
            ->setField('customer_id')
            ->setConditionType('eq')
            ->setValue($customerId)
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter]);
        $this->searchCriteriaBuilder->setPageSize(20);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $customersItems  = $this->customerLoyaltyPointRepository->getList($searchCriteria)->getItems();
        return $customersItems;
    }
}
