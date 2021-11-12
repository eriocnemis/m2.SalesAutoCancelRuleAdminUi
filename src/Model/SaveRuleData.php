<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
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
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * Initialize controller
     *
     * @param SaveRuleInterface $saveRule
     * @param ResolveRuleInterface $resolveRule
     * @param DataPersistorInterface $dataPersistor
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        SaveRuleInterface $saveRule,
        ResolveRuleInterface $resolveRule,
        DataPersistorInterface $dataPersistor,
        MessageManagerInterface $messageManager
    ) {
        $this->saveRule = $saveRule;
        $this->resolveRule = $resolveRule;
        $this->dataPersistor = $dataPersistor;
        $this->messageManager = $messageManager;
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

        $rule = $this->saveRule->execute(
            $this->resolveRule->execute($ruleId, $data)
        );

        $this->messageManager->addSuccessMessage(
            (string)__('The Rule has been saved.')
        );

        return $rule;
    }
}
