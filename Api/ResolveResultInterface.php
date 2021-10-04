<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Api;

use Magento\Framework\Controller\ResultInterface;
use Magento\Backend\App\Action;

/**
 * Resolve action result
 *
 * @api
 */
interface ResolveResultInterface
{
    /**
     * Resolve result
     *
     * @param Action $action
     * @param int|null $ruleId
     * @return ResultInterface
     */
    public function execute(Action $action, int $ruleId = null): ResultInterface;
}
