<?php
/**
 * Copyright © Zoku, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

/**
 * Order service interface defined
 *
 * @api
 * @since 100.0.2
 */
interface OrderServiceInterface
{
    /**
     * Save Order Log
     *
     * @param string $requestPayload
     * @param string $responsePayload
     * @return bool
     * @throws Exception
     */
    public function saveOrderLog($requestPayload, $responsePayload);

    /**
     * Delete Order Log
     *
     * @param int $days
     * @return bool
     * @throws Exception
     */
    public function deleteOrderLog($days);
}
