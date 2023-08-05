<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Service;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Exception\LocalizedException;

/**
 * Authentication logic
 */
class Service
{
    /**
     * Path for zoku module enable
     */
    public const CONNECTOR_MODULE_ENABLE = 'netsuiteconnector/authenticate/enabled';

    /**
     * Path for zoku module environment
     */
    public const CONNECTOR_MODULE_ENVIRONMENT = 'netsuiteconnector/authenticate/environment';

    /**
     * Path for zoku username staging
     */
    public const CONNECTOR_MODULE_USERNAME_STAGING = 'netsuiteconnector/authenticate/username_staging';

    /**
     * Path for zoku password staging
     */
    public const CONNECTOR_MODULE_PASSWORD_STAGING = 'netsuiteconnector/authenticate/password_staging';

    /**
     * Path for zoku base url staging
     */
    public const CONNECTOR_MODULE_BASE_URL_STAGING = 'netsuiteconnector/authenticate/base_url_staging';

    /**
     * Path for zoku username production
     */
    public const CONNECTOR_MODULE_USERNAME_PRODUCTION = 'netsuiteconnector/authenticate/username_production';

    /**
     * Path for zoku password production
     */
    public const CONNECTOR_MODULE_PASSWORD_PRODUCTION = 'netsuiteconnector/authenticate/password_production';

    /**
     * Path for zoku base url production
     */
    public const CONNECTOR_MODULE_BASE_URL_PRODUCTION = 'netsuiteconnector/authenticate/base_url_production';

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
     * Scope Interface defined
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Serializer defined
     *
     * @var Json
     */
    protected $jsonHelper;

    /**
     * Curl defined
     *
     * @var Curl
     */
    protected $curl;

    /**
     * Constructor defined
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $jsonHelper
     * @param Curl $curl
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Json $jsonHelper,
        Curl $curl
    ) {
        $this->scopeConfig          = $scopeConfig;
        $this->jsonHelper           = $jsonHelper;
        $this->curl                 = $curl;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getAuthenticationToken()
    {

        $token = '';
        if ($this->getModuleEnable() == 1) {
            $environment = $this->getConfigValue(self::CONNECTOR_MODULE_ENVIRONMENT);

            $getEnvUser = ($environment == 1) ? self::CONNECTOR_MODULE_USERNAME_STAGING
                : self::CONNECTOR_MODULE_USERNAME_PRODUCTION;
            $getEnvPass = ($environment == 1) ? self::CONNECTOR_MODULE_PASSWORD_STAGING
                : self::CONNECTOR_MODULE_PASSWORD_PRODUCTION;
            $username = $this->getConfigValue($getEnvUser);
            $password = $this->getConfigValue($getEnvPass);

            $endpoint = 'api/login';
            $reqParams = [
                'username' => $username,
                'password' => $password
            ];
            $url = $this->createUrl($endpoint);
            if ($url) {
                $token = $this->postRequest($url, $reqParams);
            }
            return $token;
        } else {
            throw new LocalizedException(__("Module is Disabled"));
        }
    }

    /**
     * Get configuration value
     *
     * @param  string $param
     * @return string
     */
    public function getConfigValue($param)
    {
        return $this->scopeConfig->getValue(
            $param,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get module enable disbale
     *
     * @return string
     */
    public function getModuleEnable()
    {
        return $this->scopeConfig->getValue(
            self::CONNECTOR_MODULE_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Create URL for api call
     *
     * @param  string $endpoint
     * @return string
     */
    public function createUrl($endpoint)
    {
        $enabled = $this->getModuleEnable();

        $baseUrl = $getUrl = '';
        if ($enabled == 1 && !empty($endpoint)) {

            $getEnv = $this->scopeConfig->getValue(
                self::CONNECTOR_MODULE_ENVIRONMENT,
                ScopeInterface::SCOPE_STORE
            );

            $url = ($getEnv == 1) ? self::CONNECTOR_MODULE_BASE_URL_STAGING
                : self::CONNECTOR_MODULE_BASE_URL_PRODUCTION ;
            $getUrl = $this->scopeConfig->getValue(
                $url,
                ScopeInterface::SCOPE_STORE
            );
            $baseUrl = $getUrl . "/". $endpoint;
        }
        return $baseUrl;
    }

    /**
     * Curl Post Request
     *
     * @param string $url
     * @param array|null $data
     * @return bool
     * @throws Exception
     */
    public function postRequest($url, array $data = null)
    {
        $body = $this->jsonHelper->serialize($data);
        
        $authToken = $this->getAuthenticationToken();
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'Authorization :' . $authToken
        ];
        $this->curl->setConfig(["header" => false]);
        $this->curl->write('POST', $url, $http_ver = '1.1', $headers, $body);
        $response = $this->curl->read();

        if (!$this->curl->getError() && $response) {
            return $this->jsonHelper->unserialize($response);
        } else {
            throw new LocalizedException(__('Request failed'));
        }
    }

    /**
     * Add/update Customer
     *
     * @param array $details
     */
    public function upsertCustomer($details)
    {
        $response = '';
        if (!empty($details)) {
            $endpoint = '/web/customer';
            $getUrl = $this->createUrl($endpoint);
            if (!empty($getUrl)) {
                try {
                    $response = $this->postRequest($getUrl, $details);
                    return $response;
                } catch (LocalizedException $e) {
                    throw new LocalizedException($e->getMessage());
                }
            }
        }
    }

    /**
     * Curl Get Request
     *
     * @param  string $url
     * @return bool
     */
    public function getRequest($url)
    {
        $authToken = $this->getAuthenticationToken();
        $headers = [
            'Content-Type: application/json',
            'Authorization :' . $authToken
        ];

        $this->curl->setConfig(["header" => false]);
        $this->curl->write('GET', $url, $http_ver = '1.1', $headers, $body = '');
        $response = $this->curl->read();

        if (!$this->curl->getError() && $response) {
            return $this->jsonHelper->unserialize($response);
        } else {
            throw new LocalizedException(__('Request failed'));
        }
    }

    /**
     * Get the products added or updated
     *
     * @return string
     */
    public function getProductList()
    {

        $getTimeStamp = $this->getConfigValue('zoku/crons/sync_products_timestamp');
        if (isset($getTimeStamp)) {
            $endpoint = '/web/product/index/'.$getTimeStamp;
        }
        $getUrl = $this->createUrl($endpoint);

        try {
            $response = $this->getRequest($getUrl);
            return $response;
        } catch (LocalizedException $e) {
            throw new LocalizedException($e->getMessage());
        }
    }

    /**
     * Curl Get Customer Loyalty Path
     *
     * @return string
     */
    public function getCustomerLoyaltyPath()
    {
        return 'web/customer/loyalty/';
    }

    /**
     * Get Log Enable configurations
     *
     * @return bool
     */
    public function getLogConfig()
    {
        return $this->getConfigValue(self::XML_PATH_LOGGING_ENABLED);
    }

    /**
     * Get no of days value
     *
     * @return int
     */
    public function getNoOfDayConfig()
    {
        return $this->getConfigValue(self::XML_PATH_NO_OF_DAYS);
    }

    /**
     * Get Loyalty Points Enable configurations
     *
     * @return bool
     */
    public function getLoyaltyPointsConfig()
    {
        return $this->getConfigValue(self::XML_PATH_LOYALTY_POINTS_ENABLED);
    }

    /**
     * Curl Get Customer Loyalty History Path
     *
     * @return string
     */
    public function getCustomerLoyaltyHistoryPath()
    {
        return 'web/customer/history/';
    }
}
