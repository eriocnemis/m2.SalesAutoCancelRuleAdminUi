<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Api;

use Exception;

/**
 * Get exception handler
 *
 * @api
 */
interface GetExceptionInterface
{
    /**
     * Execute exception handler
     *
     * @param Exception $e
     * @return void
     */
    public function execute(Exception $e): void;
}
