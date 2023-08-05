<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ProductGridRepositoryInterface
 */
interface ProductGridRepositoryInterface
{
    /**
     * Save Product Log.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid
     * @return \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid);

    /**
     * Delete Product Log.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid
     * @return \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\ProductGridInterface $productGrid);

    /**
     * Retrieve Product Log. Collection matching the specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return \Zoku\NetSuiteConnector\Api\Data\ProductGridSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Retrieve Product Log.
     *
     * @param int $logId
     * @return \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($logId);

    /**
     * Delete Product Log. by ID.
     *
     * @param int $logId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($logId);
}
