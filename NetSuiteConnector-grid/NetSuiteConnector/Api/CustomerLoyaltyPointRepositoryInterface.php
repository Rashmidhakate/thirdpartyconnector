<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CustomerLoyaltyPointRepositoryInterface
 */
interface CustomerLoyaltyPointRepositoryInterface
{
    /**
     * Save Customer Loyalty Point.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint);

    /**
     * Delete Customer Loyalty Point.
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface $customerLoyaltyPoint);

    /**
     * Retrieve Customer Loyalty Point. Collection matching the specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Retrieve Customer Loyalty Point.
     *
     * @param int $id
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Delete Customer Loyalty Point by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
