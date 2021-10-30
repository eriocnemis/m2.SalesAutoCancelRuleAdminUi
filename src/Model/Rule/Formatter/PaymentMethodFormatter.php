<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model\Rule\Formatter;

use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Model\Rule\FormatterInterface;

/**
 * Format payment method value
 */
class PaymentMethodFormatter implements FormatterInterface
{
    /**
     * Format rule attribute values
     *
     * @param mixed[] $data
     * @return mixed[]
     */
    public function format(array $data): array
    {
        if (empty($data[RuleInterface::PAYMENT_METHOD])) {
            $data[RuleInterface::PAYMENT_METHOD] = [];
        }
        return $data;
    }
}
