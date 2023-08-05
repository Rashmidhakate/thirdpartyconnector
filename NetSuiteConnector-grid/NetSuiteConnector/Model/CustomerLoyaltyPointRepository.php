<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Zoku\NetSuiteConnector\Model\Api\SearchCriteria\CustomerLoyaltyPointCollectionProcessor;
use Zoku\NetSuiteConnector\Api\CustomerLoyaltyPointRepositoryInterface as CustomerLoyaltyPointRepositoryInterface;
use Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointSearchResultInterfaceFactory;

/**
 * Customer Loyalty Point repository Defined
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerLoyaltyPointRepository implements CustomerLoyaltyPointRepositoryInterface
{
    /**
     * @var ResourceModel\CustomerLoyaltyPoint
     */
    private $customerLoyaltyPointResource;
    /**
     * @var CustomerLoyaltyPointFactory
     */
    private $customerLoyaltyPointFactory;
    /**
     * @var CustomerLoyaltyPointSearchResultInterfaceFactory
     */
    private $customerLoyaltyPointSearchResults;
    /**
     * @var ResourceModel\CustomerLoyaltyPoint\CollectionFactory
     */
    private $customerLoyaltyPointCollection;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * CustomerLoyaltyPointRepository constructor.
     *
     * @param ResourceModel\CustomerLoyaltyPoint $customerLoyaltyPointResource
     * @param CustomerLoyaltyPointFactory $customerLoyaltyPointFactory
     * @param CustomerLoyaltyPointSearchResultInterfaceFactory $customerLoyaltyPointSearchResults
     * @param ResourceModel\CustomerLoyaltyPoint\CollectionFactory $customerLoyaltyPointCollection
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        ResourceModel\CustomerLoyaltyPoint $customerLoyaltyPointResource,
        CustomerLoyaltyPointFactory $customerLoyaltyPointFactory,
        CustomerLoyaltyPointSearchResultInterfaceFactory $customerLoyaltyPointSearchResults,
        ResourceModel\CustomerLoyaltyPoint\CollectionFactory $customerLoyaltyPointCollection,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->customerLoyaltyPointResource = $customerLoyaltyPointResource;
        $this->customerLoyaltyPointFactory = $customerLoyaltyPointFactory;
        $this->customerLoyaltyPointSearchResults = $customerLoyaltyPointSearchResults;
        $this->customerLoyaltyPointCollection = $customerLoyaltyPointCollection;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Customer loyalty point save function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint
     * @return CustomerLoyaltyPoint
     * @throws CouldNotSaveException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint)
    {
        try {
            $this->customerLoyaltyPointResource->save($customerLoyaltyPoint);
            $customerLoyaltyPoint = $this->getById($customerLoyaltyPoint->getLogId());
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the CustomerLoyaltyPoint Contact: %1', $exception->getMessage()),
                $exception
            );
        }
        return $customerLoyaltyPoint;
    }
    /**
     * Customer loyalty point delete function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint)
    {
        try {
            $this->customerLoyaltyPointResource->delete($customerLoyaltyPoint);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete CustomerLoyaltyPoint Contact: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
    /**
     * Customer loyalty point get data by Id
     *
     * @param string $id
     * @return CustomerLoyaltyPoint
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $customerLoyaltyPoint = $this->customerLoyaltyPointFactory->create();
        $this->customerLoyaltyPointResource->load($customerLoyaltyPoint, $id);
        if (!$customerLoyaltyPoint->getLogId()) {
            throw new NoSuchEntityException(__(
                'The CustomerLoyaltyPoint Contact with the "%1" ID doesn\'t exist.',
                $id
            ));
            ;
        }
        return $customerLoyaltyPoint;
    }
    /**
     * Delete CustomerLoyaltyPoint by given log Identity
     *
     * @param string $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
    /**
     * Load CustomerLoyaltyPoint data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->customerLoyaltyPointCollection->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointSearchResultInterface $searchResults */
        $searchResults = $this->customerLoyaltyPointSearchResults->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @see CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                CustomerLoyaltyPointCollectionProcessor::class
            );
        }
        return $this->collectionProcessor;
    }
}
