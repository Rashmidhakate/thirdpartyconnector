<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model;

use Zoku\NetSuiteConnector\Api\Data\CustomerLoyaltyPointInterface;
use Magento\Framework\Model\AbstractModel as AbstractModel;

/**
 * Customer Loyalty Point Model.
 */
class CustomerLoyaltyPoint extends AbstractModel implements CustomerLoyaltyPointInterface
{
    /**
     * Customer log cache tag.
     */
    public const CACHE_TAG = 'zoku_loyalty_points_history';

    /**
     * @var string
     */
    protected $_cacheTag = 'zoku_loyalty_points_history';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'zoku_loyalty_points_history';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Zoku\NetSuiteConnector\Model\ResourceModel\CustomerLoyaltyPoint::class);
    }
    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Set ID.
     *
     * @param int $id
     * @return CustomerLoyaltyPointInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Get CustomerId.
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMERID);
    }

    /**
     * Set CustomerId.
     *
     * @param int $customerId
     * @return CustomerLoyaltyPointInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMERID, $customerId);
    }

    /**
     * Get TransactionId.
     *
     * @return int
     */
    public function getTransactionId()
    {
        return $this->getData(self::TRANSACTIONID);
    }

    /**
     * Set TransactionId.
     *
     * @param int $transactionId
     * @return CustomerLoyaltyPointInterface
     */
    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTIONID, $transactionId);
    }

    /**
     * Get Memo.
     *
     * @return int
     */
    public function getMemo()
    {
        return $this->getData(self::MEMO);
    }

    /**
     * Set Memo.
     *
     * @param int $memo
     * @return CustomerLoyaltyPointInterface
     */
    public function setMemo($memo)
    {
        return $this->setData(self::MEMO, $memo);
    }

    /**
     * Get Points.
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->getData(self::POINTS);
    }

    /**
     * Set Points.
     *
     * @param int $points
     * @return CustomerLoyaltyPointInterface
     */
    public function setPoints($points)
    {
        return $this->setData(self::POINTS, $points);
    }

    /**
     * Get Date.
     *
     * @return string|null
     */
    public function getDate()
    {
        return $this->getData(self::DATE);
    }

    /**
     * Set Date.
     *
     * @param string $date
     * @return CustomerLoyaltyPointInterface
     */
    public function setDate($date)
    {
        return $this->setData(self::DATE, $date);
    }
}
