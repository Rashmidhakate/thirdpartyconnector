<?php
/**
 * Copyright © Zoku, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Api;

/**
 * Product service interface defined
 *
 * @api
 * @since 100.0.2
 */
interface ProductServiceInterface
{
    /**
     * Save Product Log
     *
     * @param string $requestPayload
     * @param string $responsePayload
     * @return bool
     * @throws Exception
     */
    public function saveProductLog($requestPayload, $responsePayload);

    /**
     * Delete Product Log
     *
     * @param int $days
     * @return bool
     * @throws Exception
     */
    public function deleteProductLog($days);
}
