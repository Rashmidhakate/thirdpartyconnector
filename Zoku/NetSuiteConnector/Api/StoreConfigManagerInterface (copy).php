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
interface ProductServiceInterface
{
    /**
     * @param $endpoint
     * @param string $request
     * @param string $response
     * @return bool
     * @throws Exception
     */
    public function saveProductLog($request, $response);
}
