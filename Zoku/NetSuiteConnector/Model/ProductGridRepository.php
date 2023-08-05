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
use Zoku\NetSuiteConnector\Model\Api\SearchCriteria\ProductGridCollectionProcessor;

/**
 * Product Grid repository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductGridRepository implements \Zoku\NetSuiteConnector\Api\ProductGridRepositoryInterface
{
    /**
     * @var ResourceModel\ProductGrid
     */
    private $productGridResource;
    /**
     * @var ProductGridFactory
     */
    private $productGridFactory;
    /**
     * @var \Zoku\NetSuiteConnector\Api\Data\ProductGridSearchResultInterfaceFactory
     */
    private $productGridSearchResults;
    /**
     * @var ResourceModel\ProductGrid\CollectionFactory
     */
    private $productGridCollection;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * ProductGridRepository constructor.
     * @param ResourceModel\ProductGrid $productGridResource
     * @param ProductGridFactory $productGridFactory
     * @param \Zoku\NetSuiteConnector\Api\Data\ProductGridSearchResultInterfaceFactory $productGridSearchResults
     * @param ResourceModel\ProductGrid\CollectionFactory $productGridCollection
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        \Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid $productGridResource,
        ProductGridFactory $productGridFactory,
        \Zoku\NetSuiteConnector\Api\Data\ProductGridSearchResultInterfaceFactory $productGridSearchResults,
        \Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid\CollectionFactory $productGridCollection,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->productGridResource = $productGridResource;
        $this->productGridFactory = $productGridFactory;
        $this->productGridSearchResults = $productGridSearchResults;
        $this->productGridCollection = $productGridCollection;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Product grid save function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid
     * @return ProductGrid
     * @throws CouldNotSaveException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid)
    {
        try {
            $this->productGridResource->save($productGrid);
            $productGrid = $this->getById($productGrid->getLogId());
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the ProductGrid Contact: %1', $exception->getMessage()),
                $exception
            );
        }
        return $productGrid;
    }
    /**
     * Product grid delete function
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid)
    {
        try {
            $this->productGridResource->delete($productGrid);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete ProductGrid Contact: %1', $exception->getMessage()));
        }
        return true;
    }
    /**
     * Product grid get data by ID
     *
     * @param string $logId
     * @return ProductGrid
     * @throws NoSuchEntityException
     */
    public function getById($logId)
    {
        $productGrid = $this->productGridFactory->create();
        $this->productGridResource->load($productGrid, $logId);
        if (!$productGrid->getLogId()) {
            throw new NoSuchEntityException(__('The ProductGrid Contact with the "%1" ID doesn\'t exist.', $logId));
            ;
        }
        return $productGrid;
    }
    /**
     * Product grid delete data by ID
     *
     * Delete ProductGrid by given ProductGrid Identity
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
     * Load ProductGrid data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Zoku\NetSuiteConnector\Api\Data\ProductGridSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->productGridCollection->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Zoku\NetSuiteConnector\Api\Data\ProductGridSearchResultInterface $searchResults */
        $searchResults = $this->productGridSearchResults->create();
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
                ProductGridCollectionProcessor::class
            );
        }
        return $this->collectionProcessor;
    }
}
