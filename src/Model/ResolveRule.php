<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterfaceFactory;
use Eriocnemis\SalesAutoCancelRuleApi\Api\GetRuleByIdInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\ResolveRuleInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Model\Rule\FormatterInterface;

/**
 * Resolve rule data
 *
 * @api
 */
class ResolveRule implements ResolveRuleInterface
{
    /**
     * @var RuleInterfaceFactory
     */
    private $factory;

    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var GetRuleByIdInterface
     */
    private $getRuleById;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * Initialize provider
     *
     * @param RuleInterfaceFactory $factory
     * @param FormatterInterface $formatter
     * @param GetRuleByIdInterface $getRuleById
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RuleInterfaceFactory $factory,
        FormatterInterface $formatter,
        GetRuleByIdInterface $getRuleById,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->factory = $factory;
        $this->formatter = $formatter;
        $this->getRuleById = $getRuleById;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Resolve rule
     *
     * @param int|null $ruleId
     * @param mixed[] $data
     * @return RuleInterface
     * @throws NoSuchEntityException
     */
    public function execute($ruleId, array $data): RuleInterface
    {
        /** @var RuleInterface $rule */
        $rule = null !== $ruleId
            ? $this->getRuleById->execute((int)$ruleId)
            : $this->factory->create();

        $data = $this->formatter->format($data);
        $this->dataObjectHelper->populateWithArray($rule, $data, RuleInterface::class);

        return $rule;
    }
}
