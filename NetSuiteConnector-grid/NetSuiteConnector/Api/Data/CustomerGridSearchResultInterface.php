<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

use Magento\Framework\Api\SearchResultsInterface as SearchResultsInterface;

/**
 * Customer Grid Serach Result Interface.
 */
interface CustomerGridSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
