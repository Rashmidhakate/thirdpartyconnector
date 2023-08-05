<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

/**
 * Product grid interface defined
 * @api
 * @since 100.0.2
 */
interface ProductGridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    public const LOG_ID = 'log_id';
    public const SKU = 'sku';
    public const PRICE = 'price';
    public const QTY = 'qty';
    public const PRODUCTNETSUITEID = 'product_netsuite_id';
    public const REQUESTPAYLOAD = 'request_payload';
    public const RESPONSEPAYLOAD = 'response_payload';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

   /**
    * Get LogId.
    *
    * @return int
    */
    public function getLogId();

   /**
    * Set LogId.
    *
    * @param int $logId
    * @return ProductGridInterface
    */
    public function setLogId($logId);

   /**
    * Get Sku.
    *
    * @return varchar
    */
    public function getSku();

   /**
    * Set Sku.
    *
    * @param string $sku
    * @return ProductGridInterface
    */
    public function setSku($sku);

    /**
     * Get price.
     *
     * @return int
     */
    public function getPrice();

   /**
    * Set price.
    *
    * @param int $price
    * @return ProductGridInterface
    */
    public function setPrice($price);

    /**
     * Get qty.
     *
     * @return int
     */
    public function getQty();

   /**
    * Set qty.
    *
    * @param int $qty
    * @return ProductGridInterface
    */
    public function setQty($qty);

    /**
     * Get ProductNetsuiteId.
     *
     * @return int
     */
    public function getProductNetsuiteId();

   /**
    * Set ProductNetsuiteId.
    *
    * @param int $productNetsuiteId
    * @return ProductGridInterface
    */
    public function setProductNetsuiteId($productNetsuiteId);

    /**
     * Get RequestPayload.
     *
     * @return varchar
     */
    public function getRequestPayload();

   /**
    * Set RequestPayload.
    *
    * @param string $requestPayload
    * @return ProductGridInterface
    */
    public function setRequestPayload($requestPayload);

    /**
     * Get ResponsePayload.
     *
     * @return varchar
     */
    public function getResponsePayload();

   /**
    * Set ResponsePayload.
    *
    * @param string $responsePayload
    * @return ProductGridInterface
    */
    public function setResponsePayload($responsePayload);

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt();

   /**
    * Set CreatedAt.
    *
    * @param string $createdAt
    * @return ProductGridInterface
    */
    public function setCreatedAt($createdAt);

    /**
     * Get UpdatedAt.
     *
     * @return varchar
     */
    public function getUpdatedAt();

   /**
    * Set UpdatedAt.
    *
    * @param string $updatedAt
    * @return ProductGridInterface
    */
    public function setUpdatedAt($updatedAt);
}
