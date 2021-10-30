<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model\Rule;

/**
 * Extension point for base formatter of rule data
 *
 * @api
 */
interface FormatterInterface
{
    /**
     * Format rule attribute values
     *
     * @param mixed[] $data
     * @return mixed[]
     */
    public function format(array $data): array;
}
