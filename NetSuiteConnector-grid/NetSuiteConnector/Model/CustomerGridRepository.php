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
use Zoku\NetSuiteConnector\Model\Api\SearchCriteria\CustomerGridCollectionProcessor;
use Zoku\NetSuiteConnector\Api\CustomerGridRepositoryInterface as CustomerGridRepositoryInterface;

/**
 * Customer Log Grid repository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerGridRepository implements CustomerGridRepositoryInterface
{
    /**
     * @var ResourceModel\CustomerGrid
     */
    private $customerGridResource;
    /**
     * @var CustomerGridFactory
     */
    private $customerGridFactory;
    /**
     * @var \Zoku\NetSuiteConnector\Api\Data\CustomerGridSearchResultInterfaceFactory
     */
    private $customerGridSearchResults;
    /**
     * @var ResourceModel\CustomerGrid\CollectionFactory
     */
    private $customerGridCollection;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * CustomerGridRepository constructor.
     *
     * @param ResourceModel\CustomerGrid $customerGridResource
     * @param CustomerGridFactory $customerGridFactory
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerGridSearchResultInterfaceFactory $customerGridSearchResults
     * @param ResourceModel\CustomerGrid\CollectionFactory $customerGridCollection
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        \Zoku\NetSuiteConnector\Model\ResourceModel\CustomerGrid $customerGridResource,
        CustomerGridFactory $customerGridFactory,
        \Zoku\NetSuiteConnector\Api\Data\CustomerGridSearchResultInterfaceFactory $customerGridSearchResults,
        \Zoku\NetSuiteConnector\Model\ResourceModel\CustomerGrid\CollectionFactory $customerGridCollection,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->customerGridResource = $customerGridResource;
        $this->customerGridFactory = $customerGridFactory;
        $this->customerGridSearchResults = $customerGridSearchResults;
        $this->customerGridCollection = $customerGridCollection;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Customer grid save function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid
     * @return CustomerGrid
     * @throws CouldNotSaveException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid)
    {
        try {
            $this->customerGridResource->save($customerGrid);
            $customerGrid = $this->getById($customerGrid->getLogId());
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the CustomerGrid Contact: %1', $exception->getMessage()),
                $exception
            );
        }
        return $customerGrid;
    }
    /**
     * Customer grid delete function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid)
    {
        try {
            $this->customerGridResource->delete($customerGrid);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete CustomerGrid Contact: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
    /**
     * Customer grid get data by Id
     *
     * @param string $logId
     * @return CustomerGrid
     * @throws NoSuchEntityException
     */
    public function getById($logId)
    {
        $customerGrid = $this->customerGridFactory->create();
        $this->customerGridResource->load($customerGrid, $logId);
        if (!$customerGrid->getLogId()) {
            throw new NoSuchEntityException(__('The CustomerGrid Contact with the "%1" ID doesn\'t exist.', $logId));
            ;
        }
        return $customerGrid;
    }
    /**
     * Delete CustomerGrid by given log Identity
     *
     * @param string $logId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($logId)
    {
        return $this->delete($this->getById($logId));
    }
    /**
     * Load CustomerGrid data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerGridSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->customerGridCollection->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Zoku\NetSuiteConnector\Api\Data\CustomerGridSearchResultInterface $searchResults */
        $searchResults = $this->customerGridSearchResults->create();
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
                CustomerGridCollectionProcessor::class
            );
        }
        return $this->collectionProcessor;
    }
}
