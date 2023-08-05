<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface OrderGridRepositoryInterface
 */
interface OrderGridRepositoryInterface
{
    /**
     * Save Order Log.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid
     * @return \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid);

    /**
     * Delete Order Log.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid
     * @return \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\OrderGridInterface $orderGrid);

    /**
     * Retrieve Order Log. Collection matching the specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return \Zoku\NetSuiteConnector\Api\Data\OrderGridSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Retrieve Order Log.
     *
     * @param int $logId
     * @return \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($logId);

    /**
     * Delete Order Log by ID.
     *
     * @param int $logId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($logId);
}
