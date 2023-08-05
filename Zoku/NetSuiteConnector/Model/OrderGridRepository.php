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
use Zoku\NetSuiteConnector\Model\Api\SearchCriteria\OrderGridCollectionProcessor;

/**
 * Order Grid repository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderGridRepository implements \Zoku\NetSuiteConnector\Api\OrderGridRepositoryInterface
{
    /**
     * @var ResourceModel\OrderGrid
     */
    private $orderGridResource;
    /**
     * @var OrderGridFactory
     */
    private $orderGridFactory;
    /**
     * @var \Zoku\NetSuiteConnector\Api\Data\OrderGridSearchResultInterfaceFactory
     */
    private $orderGridSearchResults;
    /**
     * @var ResourceModel\OrderGrid\CollectionFactory
     */
    private $orderGridCollection;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * OrderGridRepository constructor.
     *
     * @param ResourceModel\OrderGrid $orderGridResource
     * @param OrderGridFactory $orderGridFactory
     * @param \Zoku\NetSuiteConnector\Api\Data\OrderGridSearchResultInterfaceFactory $orderGridSearchResults
     * @param ResourceModel\OrderGrid\CollectionFactory $orderGridCollection
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        \Zoku\NetSuiteConnector\Model\ResourceModel\OrderGrid $orderGridResource,
        OrderGridFactory $orderGridFactory,
        \Zoku\NetSuiteConnector\Api\Data\OrderGridSearchResultInterfaceFactory $orderGridSearchResults,
        \Zoku\NetSuiteConnector\Model\ResourceModel\OrderGrid\CollectionFactory $orderGridCollection,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->orderGridResource = $orderGridResource;
        $this->orderGridFactory = $orderGridFactory;
        $this->orderGridSearchResults = $orderGridSearchResults;
        $this->orderGridCollection = $orderGridCollection;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Order grid save function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid
     * @return OrderGrid
     * @throws CouldNotSaveException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid)
    {
        try {
            $this->orderGridResource->save($orderGrid);
            $orderGrid = $this->getById($orderGrid->getLogId());
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the OrderGrid Contact: %1', $exception->getMessage()),
                $exception
            );
        }
        return $orderGrid;
    }
    /**
     * Order grid delete function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid)
    {
        try {
            $this->orderGridResource->delete($orderGrid);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete OrderGrid Contact: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
    /**
     * Order grid get data by Id
     *
     * @param string $logId
     * @return OrderGrid
     * @throws NoSuchEntityException
     */
    public function getById($logId)
    {
        $orderGrid = $this->orderGridFactory->create();
        $this->orderGridResource->load($orderGrid, $logId);
        if (!$orderGrid->getLogId()) {
            throw new NoSuchEntityException(__('The OrderGrid Contact with the "%1" ID doesn\'t exist.', $logId));
            ;
        }
        return $orderGrid;
    }
    /**
     * Delete OrderGrid by given log Identity
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
     * Load OrderGrid data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Zoku\NetSuiteConnector\Api\Data\OrderGridSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->orderGridCollection->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Zoku\NetSuiteConnector\Api\Data\OrderGridSearchResultInterface $searchResults */
        $searchResults = $this->orderGridSearchResults->create();
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
                OrderGridCollectionProcessor::class
            );
        }
        return $this->collectionProcessor;
    }
}
