<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Request\DataPersistorInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\SaveRuleInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\ResolveRuleInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\SaveRuleDataInterface;

/**
 * Save rule data
 *
 * @api
 */
class SaveRuleData implements SaveRuleDataInterface
{
    /**
     * @var SaveRuleInterface
     */
    private $saveRule;

    /**
     * @var ResolveRuleInterface
     */
    private $resolveRule;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Initialize controller
     *
     * @param SaveRuleInterface $saveRule
     * @param ResolveRuleInterface $resolveRule
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        SaveRuleInterface $saveRule,
        ResolveRuleInterface $resolveRule,
        DataPersistorInterface $dataPersistor
    ) {
        $this->saveRule = $saveRule;
        $this->resolveRule = $resolveRule;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save data
     *
     * @param RequestInterface $request
     * @return RuleInterface
     * @throws LocalizedException
     */
    public function execute(RequestInterface $request): RuleInterface
    {
        $data = $request->getPost('rule');
        if (empty($data)) {
            throw new LocalizedException(
                __('Wrong request.')
            );
        }

        $ruleId = $data[RuleInterface::RULE_ID] ?? null;
        $this->dataPersistor->set('eriocnemis_sales_autocancel_rule', $data);

        return $this->saveRule->execute(
            $this->resolveRule->execute($ruleId, $data)
        );
    }
}
