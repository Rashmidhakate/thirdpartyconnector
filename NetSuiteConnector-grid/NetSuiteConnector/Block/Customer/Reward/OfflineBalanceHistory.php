<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer offline balance reward history block
 *
 */
namespace Zoku\NetSuiteConnector\Block\Customer\Reward;

use Magento\Framework\View\Element\Template as Template;
use Zoku\NetSuiteConnector\Service\Service as BaseService;
use Magento\Framework\View\Element\Template\Context as Context;
use Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint\CollectionFactory as CustomerLoyaltyPointHistoryFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Api\Data\CustomerInterface;
use Zoku\NetSuiteConnector\Api\CustomerLoyaltyPointRepositoryInterface as CustomerLoyaltyPointRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Zoku\NetSuiteConnector\Logger\Logger;
use Zoku\NetSuiteConnector\Service\CustomerLoyaltyService as CustomerLoyaltyService;
use Magento\Customer\Helper\Session\CurrentCustomer as CurrentCustomer;
/**
 * @api
 * @since 100.0.2
 */
class OfflineBalanceHistory extends Template
{
    /**
     * Customer Loyalty PointHistory records collection
     *
     * @var \Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint\Collection
     */
    protected $collection = null;

    /**
     * @var \Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint\CollectionFactory
     */
    protected $customerLoyaltyPointHistoryFactory;

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
     * Current Customer Session
     *
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param Context $context
     * @param CustomerLoyaltyPointHistoryFactory $customerLoyaltyPointHistoryFactory
     * @param BaseService $baseService
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CustomerLoyaltyPointRepositoryInterface $customerLoyaltyPointRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param FilterBuilder $filterBuilder
     * @param CustomerLoyaltyService $customerLoyaltyService
     * @param CurrentCustomer $currentCustomer
     * @param Logger $logger
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        CustomerLoyaltyPointHistoryFactory $customerLoyaltyPointHistoryFactory,
        BaseService $baseService,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerLoyaltyPointRepositoryInterface $customerLoyaltyPointRepository,
        CustomerRepositoryInterface $customerRepository,
        FilterBuilder $filterBuilder,
        CustomerLoyaltyService $customerLoyaltyService,
        Logger $logger,
        CurrentCustomer $currentCustomer,
        array $data = []
    ) {
        $this->customerLoyaltyPointHistoryFactory = $customerLoyaltyPointHistoryFactory;
        $this->baseService =  $baseService;
        $this->searchCriteriaBuilder =  $searchCriteriaBuilder;
        $this->customerLoyaltyPointRepository =  $customerLoyaltyPointRepository;
        $this->customerRepository =  $customerRepository;
        $this->filterBuilder =  $filterBuilder;
        $this->customerLoyaltyService =  $customerLoyaltyService;
        $this->logger = $logger;
        $this->currentCustomer = $currentCustomer;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Return reword points update history collection by customer and website
     *
     * @return \Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint\Collection
     * @codeCoverageIgnore
     */
    protected function getCollection()
    {
        if (!$this->collection) {
            $collectionFactory = $this->customerLoyaltyPointHistoryFactory->create()
                ->addFieldToFilter('customer_id', $this->currentCustomer->getCustomerId());;
            $this->collection = $collectionFactory;
        }
        return $this->collection;
    }

    /**
     * Instantiate Pagination
     *
     * @return $this
     * @codeCoverageIgnore
     */
    protected function _prepareLayout()
    {
        $pager = $this->getLayout()->createBlock(
            \Magento\Theme\Block\Html\Pager::class,
            'customer.loyalty.point.history.pager'
        )->setCollection(
            $this->getCollection()
        );
        $this->setChild('pager', $pager);
        return parent::_prepareLayout();
    }

    /**
     * Whether the history may show up
     *
     * @return string
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
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
     * Fetch current customer offline balance history
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
