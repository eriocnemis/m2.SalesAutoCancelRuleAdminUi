<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\DataProvider\Rule\Modifier\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Eriocnemis\SalesAutoCancelRule\Model\System\Config\Source\MethodsAccess as MethodsAccessSource;

/**
 * Payment modifier
 *
 * @api
 */
class Payment implements ModifierInterface
{
    /**
     * Form name
     */
    const FORM = 'eriocnemis_sales_autocancel_rule_form';

    /**
     * @var PaymentHelper
     */
    private $paymentHelper;

    /**
     * @var MethodsAccessSource
     */
    private $methodsAccessSource;

    /**
     * Initialize modifier
     *
     * @param PaymentHelper $paymentHelper
     * @param MethodsAccessSource $methodsAccessSource
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        MethodsAccessSource $methodsAccessSource
    ) {
        $this->paymentHelper = $paymentHelper;
        $this->methodsAccessSource = $methodsAccessSource;
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
        $meta['payment']['children'] = [
            'methods_access' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'options' => $this->methodsAccessSource->toOptionArray(),
                            'switcherConfig' => $this->getSwitcherConfig()
                        ]
                    ]
                ]
            ],
            'payment_method' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'options' => $this->paymentHelper->getPaymentMethodList(true, true)
                        ]
                    ]
                ]
            ]
        ];
        return $meta;
    }

    /**
     * Retrieve switcher config
     *
     * @return mixed[]
     */
    private function getSwitcherConfig()
    {
        return [
            'rules' => [
                $this->getPaymentMethodRule('0', 'show'),
                $this->getPaymentMethodRule('1', 'hide')
            ],
            'enabled' => true
        ];
    }

    /**
     * Retrieve payment method rule
     *
     * @param string $value
     * @param string $callback
     * @return mixed[]
     */
    private function getPaymentMethodRule($value, $callback)
    {
        return [
            'value' => $value,
            'actions' => [
                $this->getPaymentMethodAction($callback)
            ]
        ];
    }

    /**
     * Retrieve payment method action
     *
     * @param string $callback
     * @return mixed[]
     */
    private function getPaymentMethodAction($callback)
    {
        return [
            'target' => $this->getPaymentTarget('payment_method'),
            'callback' => $callback
        ];
    }

    /**
     * Retrieve payment target field path
     *
     * @param string $field
     * @return string
     */
    private function getPaymentTarget($field)
    {
        return self::FORM . '.' . self::FORM . '.payment.' . $field;
    }
}
