<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

use Magento\Framework\Api\SearchResultsInterface as SearchResultsInterface;

/**
 * Customer Loyalty Point Serach Result Interface.
 */
interface CustomerLoyaltyPointSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get Items
     *
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface[]
     */
    public function getItems();

    /**
     * Set Items
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
