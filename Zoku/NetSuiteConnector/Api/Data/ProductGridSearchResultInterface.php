<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

/**
 * Product Grid Serach Result Interface.
 */
interface ProductGridSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get Items
     *
     * @return \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface[]
     */
    public function getItems();

    /**
     * Set Items
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\ProductGridInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
