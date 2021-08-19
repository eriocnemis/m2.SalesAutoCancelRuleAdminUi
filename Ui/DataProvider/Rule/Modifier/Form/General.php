<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\DataProvider\Rule\Modifier\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Eriocnemis\SalesAutoCancelRule\Model\System\Config\Source\DurationUnit as DurationUnitSource;

/**
 * General modifier
 *
 * @api
 */
class General implements ModifierInterface
{
    /**
     * @var DurationUnitSource
     */
    private $durationUnitSource;

    /**
     * Initialize modifier
     *
     * @param DurationUnitSource $durationUnitSource
     */
    public function __construct(
        DurationUnitSource $durationUnitSource
    ) {
        $this->durationUnitSource = $durationUnitSource;
    }

    /**
     * Modify form data
     *
     * @param mixed[] $data
     * @return mixed[]
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Modify form meta
     *
     * @param mixed[] $meta
     * @return mixed[]
     */
    public function modifyMeta(array $meta)
    {
        $meta['general']['children']['status'] = [
            'arguments' => $this->getArguments($this->getCheckboxArguments())
        ];

        $meta['general']['children']['customer_notified'] = [
            'arguments' => $this->getArguments($this->getCheckboxArguments())
        ];

        $meta['general']['children']['visible_on_front'] = [
            'arguments' => $this->getArguments($this->getCheckboxArguments())
        ];

        $meta['general']['children']['duration_unit'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'options' => $this->durationUnitSource->toOptionArray()
                    ]
                ]
            ]
        ];

        return $meta;
    }

    /**
     * Retrieve arguments data
     *
     * @param mixed[] $config
     * @return mixed[]
     */
    private function getArguments(array $config)
    {
        return ['data' => ['config' => $config]];
    }

    /**
     * Retrieve checkbox arguments
     *
     * @return mixed[]
     */
    private function getCheckboxArguments()
    {
        return [
            'prefer' => 'toggle',
            'valueMap' => ['false' => 0, 'true' => 1]
        ];
    }
}
