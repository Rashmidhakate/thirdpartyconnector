<?php
/**
 * Copyright © Zoku, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Logger;

/**
 * Class Handler
 */
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = \Monolog\Logger::INFO;

    /**
     * Log file name
     * @var string
     */
    protected $fileName = '/var/log/zoku_netsuiteconnector.log';
}
