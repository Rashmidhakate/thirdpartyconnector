<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zoku\NetSuiteConnector\Model\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;

class StoreConfigManager
{
    /**
     * Path for zoku remove log enable
     */
    public const XML_PATH_LOGGING_ENABLED = 'netsuiteconnector/logs/enabled';

    /**
     * Path for zoku no of days enable
     */
    public const XML_PATH_NO_OF_DAYS = 'netsuiteconnector/logs/no_of_days';

    /**
     * Path for zoku loyalty points enabled
     */
    public const XML_PATH_LOYALTY_POINTS_ENABLED = 'netsuiteconnector/loyalty_points/enabled';

    /**
     * Core store config
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Log Enable configurations
     *
     * @return bool
     */
    public function getLogConfig()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_LOGGING_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get no of days value
     *
     * @return int
     */
    public function getNoOfDayConfig()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_NO_OF_DAYS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Loyalty Points Enable configurations
     *
     * @return bool
     */
    public function getLoyaltyPointsConfig()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_LOYALTY_POINTS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
