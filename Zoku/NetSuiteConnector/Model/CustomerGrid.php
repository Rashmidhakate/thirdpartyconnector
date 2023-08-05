<?php

/**
 * Customer Grid Model.
 * @category  Zoku
 * @package   Zoku_NetSuiteConnector
 */
namespace Zoku\NetSuiteConnector\Model;

use Zoku\NetSuiteConnector\Api\Data\CustomerGridInterface;

/**
 * Customer Grid Model.
 */
class CustomerGrid extends \Magento\Framework\Model\AbstractModel implements CustomerGridInterface
{
    /**
     * Customer log cache tag.
     */
    public const CACHE_TAG = 'zoku_connector_customer_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'zoku_connector_customer_log';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'zoku_connector_customer_log';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Zoku\NetSuiteConnector\Model\ResourceModel\CustomerGrid::class);
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
     * @return CustomerGridInterface
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * Get CustomerEmail.
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMEREMAIL);
    }

    /**
     * Set CustomerEmail.
     *
     * @param string $email
     * @return CustomerGridInterface
     */
    public function setCustomerEmail($email)
    {
        return $this->setData(self::CUSTOMEREMAIL, $email);
    }

    /**
     * Get Flag.
     *
     * @return int
     */
    public function getFlag()
    {
        return $this->getData(self::FLAG);
    }

    /**
     * Set Flag.
     *
     * @param int $flag
     * @return CustomerGridInterface
     */
    public function setFlag($flag)
    {
        return $this->setData(self::FLAG, $flag);
    }

    /**
     * Get CustomerNetsuiteId.
     *
     * @return int
     */
    public function getCustomerNetsuiteId()
    {
        return $this->getData(self::CUSTOMERNETSUITEID);
    }

    /**
     * Set CustomerNetsuiteId.
     *
     * @param int $customerNetsuiteId
     * @return CustomerGridInterface
     */
    public function setCustomerNetsuiteId($customerNetsuiteId)
    {
        return $this->setData(self::CUSTOMERNETSUITEID, $customerNetsuiteId);
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
     * @return CustomerGridInterface
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
     * @return CustomerGridInterface
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
     * @return CustomerGridInterface
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
     * @return CustomerGridInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
