<?php
/**
 * Copyright © Zoku, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

/**
 * Customer service interface defined
 *
 * @api
 * @since 100.0.2
 */
interface CustomerServiceInterface
{
    /**
     * Save Customer Log
     *
     * @param string $requestPayload
     * @param string $responsePayload
     * @return bool
     * @throws Exception
     */
    public function saveCustomerLog($requestPayload, $responsePayload);

    /**
     * Delete Customer Log
     *
     * @param int $days
     * @return bool
     * @throws Exception
     */
    public function deleteCustomerLog($days);
}
