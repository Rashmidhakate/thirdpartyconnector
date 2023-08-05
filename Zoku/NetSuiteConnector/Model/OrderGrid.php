<?php

/**
 * Order Grid Model.
 * @category  Zoku
 * @package   Zoku_NetSuiteConnector
 */
namespace Zoku\NetSuiteConnector\Model;

use Zoku\NetSuiteConnector\Api\Data\OrderGridInterface;

/**
 * Order Grid Model.
 */
class OrderGrid extends \Magento\Framework\Model\AbstractModel implements OrderGridInterface
{
    /**
     * Order log cache tag.
     */
    public const CACHE_TAG = 'zoku_connector_order_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'zoku_connector_order_log';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'zoku_connector_order_log';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Zoku\NetSuiteConnector\Model\ResourceModel\OrderGrid::class);
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
     * @return OrderGridInterface
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * Get OrderEmail.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set OrderEmail.
     *
     * @param string $email
     * @return OrderGridInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get Increament ID.
     *
     * @return int
     */
    public function getIncreamentId()
    {
        return $this->getData(self::INCREMENTID);
    }

    /**
     * Set Increament ID.
     *
     * @param int $incrementId
     * @return OrderGridInterface
     */
    public function setIncreamentId($incrementId)
    {
        return $this->setData(self::INCREMENTID, $incrementId);
    }

    /**
     * Get OrderNetsuiteId.
     *
     * @return int
     */
    public function getOrderNetsuiteId()
    {
        return $this->getData(self::ORDERNETSUITEID);
    }

    /**
     * Set OrderNetsuiteId.
     *
     * @param int $orderNetsuiteId
     * @return OrderGridInterface
     */
    public function setOrderNetsuiteId($orderNetsuiteId)
    {
        return $this->setData(self::ORDERNETSUITEID, $orderNetsuiteId);
    }

    /**
     * Get RequestPayload.
     *
     * @return string|null
     */
    public function getRequestPayload()
    {
        return $this->getData(self::REQUESTPAYLOAD);
    }

    /**
     * Set RequestPayload.
     *
     * @param string $requestPayload
     * @return OrderGridInterface
     */
    public function setRequestPayload($requestPayload)
    {
        return $this->setData(self::REQUESTPAYLOAD, $requestPayload);
    }

    /**
     * Get ResponsePayload.
     *
     * @return string|null
     */
    public function getResponsePayload()
    {
        return $this->getData(self::RESPONSEPAYLOAD);
    }

    /**
     * Set ResponsePayload.
     *
     * @param string $responsePayload
     * @return OrderGridInterface
     */
    public function setResponsePayload($responsePayload)
    {
        return $this->setData(self::RESPONSEPAYLOAD, $responsePayload);
    }

    /**
     * Get CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt.
     *
     * @param string $createdAt
     * @return OrderGridInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set UpdatedAt.
     *
     * @param string $updatedAt
     * @return OrderGridInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
