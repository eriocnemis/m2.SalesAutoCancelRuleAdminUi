<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model\Rule;

use Magento\Framework\Exception\LocalizedException;

/**
 * Rule composite formatter
 */
class Formatter implements FormatterInterface
{
    /**
     * @var FormatterInterface[]
     */
    protected $formatters;

    /**
     * Initialize formatter
     *
     * @param FormatterInterface[] $formatters
     */
    public function __construct(
        array $formatters = []
    ) {
        foreach ($formatters as $formatter) {
            if (!$formatter instanceof FormatterInterface) {
                throw new LocalizedException(
                    __('Rule formatter must implement %1.', FormatterInterface::class)
                );
            }
        }
        $this->formatters = $formatters;
    }

    /**
     * Format rule attribute values
     *
     * @param mixed[] $data
     * @return mixed[]
     */
    public function format(array $data): array
    {
        foreach ($this->formatters as $formatter) {
            $data = $formatter->format($data);
        }
        return $data;
    }
}
