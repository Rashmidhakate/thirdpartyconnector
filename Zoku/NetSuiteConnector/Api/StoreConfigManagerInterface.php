<?php
/**
 * Copyright © Zoku, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

/**
 * Store config manager interface
 *
 * @api
 * @since 100.0.2
 */
interface StoreConfigManagerInterface
{
    /**
     * Get Log Config
     *
     * @return int
     */
    public function getLogConfig();

    /**
     * Get No Of Day Config
     *
     * @return int
     */
    public function getNoOfDayConfig();
}
