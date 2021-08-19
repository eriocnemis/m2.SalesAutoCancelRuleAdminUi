<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\DataProvider\Rule\Modifier\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Config\Model\Config\Source\Store as StoreSource;
use Eriocnemis\SalesAutoCancelRule\Model\System\Config\Source\CustomerGroup as CustomerGroupSource;

/**
 * Restrict modifier
 *
 * @api
 */
class Restrict implements ModifierInterface
{
    /**
     * @var StoreSource
     */
    private $storeSource;

    /**
     * @var CustomerGroupSource
     */
    private $customerGroupSource;

    /**
     * Initialize modifier
     *
     * @param StoreSource $storeSource
     * @param CustomerGroupSource $customerGroupSource
     */
    public function __construct(
        StoreSource $storeSource,
        CustomerGroupSource $customerGroupSource
    ) {
        $this->storeSource = $storeSource;
        $this->customerGroupSource = $customerGroupSource;
    }

    /**
     * Modify form data
     *
     * @param mixed[] $data
     * @return mixed[]
     */
    public function modifyData(array $data)
    {
        $data['store_ids'] = array_map('strval', $data['store_ids']);
        $data['customer_group_ids'] = array_map('strval', $data['customer_group_ids']);

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
        $meta['restrict']['children']['store_ids'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'options' => $this->storeSource->toOptionArray()
                    ]
                ]
            ]
        ];

        $meta['restrict']['children']['customer_group_ids'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'options' => $this->customerGroupSource->toOptionArray()
                    ]
                ]
            ]
        ];

        return $meta;
    }
}
