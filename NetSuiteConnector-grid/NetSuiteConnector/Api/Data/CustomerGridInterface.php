<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

/**
 * Customer Grid Interface defined
 */
interface CustomerGridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    public const LOG_ID = 'log_id';
    public const CUSTOMEREMAIL = 'customer_email';
    public const FLAG = 'flag';
    public const CUSTOMERNETSUITEID = 'customer_netsuite_id';
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
    * @return CustomerGridInterface
    */
    public function setLogId($logId);

   /**
    * Get CustomerEmail.
    *
    * @return string
    */
    public function getCustomerEmail();

   /**
    * Set CustomerEmail.
    *
    * @param string $email
    * @return CustomerGridInterface
    */
    public function setCustomerEmail($email);

    /**
     * Get Flag.
     *
     * @return int
     */
    public function getFlag();

   /**
    * Set Flag.
    *
    * @param int $flag
    * @return CustomerGridInterface
    */
    public function setFlag($flag);

    /**
     * Get CustomerNetsuiteId.
     *
     * @return int
     */
    public function getCustomerNetsuiteId();

   /**
    * Set CustomerNetsuiteId.
    *
    * @param int $customerNetsuiteId
    * @return CustomerGridInterface
    */
    public function setCustomerNetsuiteId($customerNetsuiteId);

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
    * @return CustomerGridInterface
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
    * @return CustomerGridInterface
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
    * @return CustomerGridInterface
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
    * @return CustomerGridInterface
    */
    public function setUpdatedAt($updatedAt);
}
