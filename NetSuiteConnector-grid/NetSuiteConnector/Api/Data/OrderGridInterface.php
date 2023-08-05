<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

/**
 * Order Grid Interface defined
 */
interface OrderGridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    public const LOG_ID = 'log_id';
    public const EMAIL = 'email';
    public const INCREMENTID = 'increment_id';
    public const ORDERNETSUITEID = 'order_netsuite_id';
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
    * @return OrderGridInterface
    */
    public function setLogId($logId);

   /**
    * Get OrderEmail.
    *
    * @return string
    */
    public function getEmail();

   /**
    * Set OrderEmail.
    *
    * @param string $email
    * @return OrderGridInterface
    */
    public function setEmail($email);

    /**
     * Get Increament ID.
     *
     * @return int
     */
    public function getIncreamentId();

   /**
    * Set Increament ID.
    *
    * @param int $incrementId
    * @return OrderGridInterface
    */
    public function setIncreamentId($incrementId);

    /**
     * Get OrderNetsuiteId.
     *
     * @return int
     */
    public function getOrderNetsuiteId();

   /**
    * Set OrderNetsuiteId.
    *
    * @param int $orderNetsuiteId
    * @return OrderGridInterface
    */
    public function setOrderNetsuiteId($orderNetsuiteId);

    /**
     * Get RequestPayload.
     *
     * @return string|null
     */
    public function getRequestPayload();

   /**
    * Set RequestPayload.
    *
    * @param string $requestPayload
    * @return OrderGridInterface
    */
    public function setRequestPayload($requestPayload);

    /**
     * Get ResponsePayload.
     *
     * @return string|null
     */
    public function getResponsePayload();

   /**
    * Set ResponsePayload.
    *
    * @param string $responsePayload
    * @return OrderGridInterface
    */
    public function setResponsePayload($responsePayload);

    /**
     * Get CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt();

   /**
    * Set CreatedAt.
    *
    * @param string $createdAt
    * @return OrderGridInterface
    */
    public function setCreatedAt($createdAt);

    /**
     * Get UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt();

   /**
    * Set UpdatedAt.
    *
    * @param string $updatedAt
    * @return OrderGridInterface
    */
    public function setUpdatedAt($updatedAt);
}
