<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CustomerGridRepositoryInterface
 */
interface CustomerGridRepositoryInterface
{
    /**
     * Save Customer Log.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid);

    /**
     * Delete Customer Log.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface $customerGrid);

    /**
     * Retrieve Customer Log. Collection matching the specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerGridSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Retrieve Customer Log.
     *
     * @param int $logId
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($logId);

    /**
     * Delete Customer Log. by ID.
     *
     * @param int $logId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($logId);
}
