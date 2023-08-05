<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api\Data;

/**
 * Customer Loyalty Point interface defined
 * @api
 * @since 100.0.2
 */
interface CustomerLoyaltyPointInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    public const ID = 'id';
    public const CUSTOMERID = 'customer_id';
    public const TRANSACTIONID = 'transaction_id';
    public const MEMO = 'memo';
    public const POINTS = 'points';
    public const DATE = 'date';

   /**
    * Get Id.
    *
    * @return int
    */
    public function getId();

   /**
    * Set Id.
    *
    * @param int $logId
    * @return CustomerLoyaltyPointInterface
    */
    public function setId($logId);

   /**
    * Get CustomerId.
    *
    * @return int
    */
    public function getCustomerId();

    /**
     * Set CustomerId.
     *
     * @param int $customerId
     * @return CustomerLoyaltyPointInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get TransactionId.
     *
     * @return int
     */
    public function getTransactionId();

    /**
     * Set TransactionId.
     *
     * @param int $transactionId
     * @return CustomerLoyaltyPointInterface
     */
    public function setTransactionId($transactionId);

    /**
     * Get Memo.
     *
     * @return int
     */
    public function getMemo();

    /**
     * Set Memo.
     *
     * @param int $memo
     * @return CustomerLoyaltyPointInterface
     */
    public function setMemo($memo);

    /**
     * Get Points.
     *
     * @return int
     */
    public function getPoints();

    /**
     * Set Points.
     *
     * @param int $points
     * @return CustomerLoyaltyPointInterface
     */
    public function setPoints($points);

    /**
     * Get Date.
     *
     * @return string|null
     */
    public function getDate();

    /**
     * Set Date.
     *
     * @param string $date
     * @return CustomerLoyaltyPointInterface
     */
    public function setDate($date);
}
