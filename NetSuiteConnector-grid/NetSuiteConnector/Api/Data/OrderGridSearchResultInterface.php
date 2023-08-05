<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

use Magento\Framework\Api\SearchResultsInterface as SearchResultsInterface;

/**
 * Order Grid Serach Result Interface.
 */
interface OrderGridSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \Zoku\NetSuiteConnector\Api\Data\OrderGridInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
