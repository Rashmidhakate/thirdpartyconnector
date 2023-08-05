<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model;

use Zoku\NetSuiteConnector\Api\Data\ProductGridInterface;
use Magento\Framework\Model\AbstractModel as AbstractModel;

/**
 * Product Grid Model
 */
class ProductGrid extends AbstractModel implements ProductGridInterface
{
    /**
     * Product log cache tag.
     */
    public const CACHE_TAG = 'zoku_connector_products_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'zoku_connector_products_log';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'zoku_connector_products_log';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid::class);
    }
    /**
     * Get LogId.
     *
     * @return int
     */
    public function getLogId()
    {
        return $this->getData(self::LOG_ID);
    }

    /**
     * Set LogId.
     *
     * @param int $logId
     * @return ProductGridInterface
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * Get SKU.
     *
     * @return varchar
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * Set SKU.
     *
     * @param string $sku
     * @return ProductGridInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Get Price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * Set Price.
     *
     * @param int $price
     * @return ProductGridInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Get Qty.
     *
     * @return int
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * Set Qty.
     *
     * @param int $qty
     * @return ProductGridInterface
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * Get ProductNetsuiteId  .
     *
     * @return int
     */
    public function getProductNetsuiteId()
    {
        return $this->getData(self::PRODUCTNETSUITEID);
    }

    /**
     * Set ProductNetsuiteId.
     *
     * @param int $productNetsuiteId
     * @return ProductGridInterface
     */
    public function setProductNetsuiteId($productNetsuiteId)
    {
        return $this->setData(self::PRODUCTNETSUITEID, $productNetsuiteId);
    }

    /**
     * Get RequestPayload.
     *
     * @return varchar
     */
    public function getRequestPayload()
    {
        return $this->getData(self::REQUESTPAYLOAD);
    }

    /**
     * Set RequestPayload.
     *
     * @param string $requestPayload
     * @return ProductGridInterface
     */
    public function setRequestPayload($requestPayload)
    {
        return $this->setData(self::REQUESTPAYLOAD, $requestPayload);
    }

    /**
     * Get ResponsePayload.
     *
     * @return string
     */
    public function getResponsePayload()
    {
        return $this->getData(self::RESPONSEPAYLOAD);
    }

    /**
     * Set ResponsePayload.
     *
     * @param string $responsePayload
     * @return ProductGridInterface
     */
    public function setResponsePayload($responsePayload)
    {
        return $this->setData(self::RESPONSEPAYLOAD, $responsePayload);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt.
     *
     * @param string $createdAt
     * @return ProductGridInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get UpdatedAt.
     *
     * @return varchar
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set UpdatedAt.
     *
     * @param string $updatedAt
     * @return ProductGridInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
