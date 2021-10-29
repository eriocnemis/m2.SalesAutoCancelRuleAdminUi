<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\GetRuleByIdInterface;

/**
 * Edit controller
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eriocnemis_AutoCancel::rule_edit';

    /**
     * @var GetRuleByIdInterface
     */
    private $getRuleById;

    /**
     * Initialize controller
     *
     * @param Context $context
     * @param GetRuleByIdInterface $getRuleById
     */
    public function __construct(
        Context $context,
        GetRuleByIdInterface $getRuleById
    ) {
        $this->getRuleById = $getRuleById;

        parent::__construct(
            $context
        );
    }

    /**
     * Edit model
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $ruleId = (int)$this->getRequest()->getParam(RuleInterface::RULE_ID);
        /** @var \Magento\Backend\Model\View\Result\Page $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $result->setActiveMenu('Eriocnemis_AutoCancel::rule');

        $title = $result->getConfig()->getTitle();
        $title->prepend((string)__('Settings'));
        $title->prepend((string)__('Order Auto Cancel Rules'));

        try {
            $rule = $this->getRuleById->execute($ruleId);
            $title->prepend((string)$rule->getName());
        } catch (NoSuchEntityException $e) {
            /** @var \Magento\Framework\Controller\Result\Redirect $result */
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                (string)__('The rule with id "%1" does not exist.', $ruleId)
            );
            $result->setPath('*/*');
        }

        return $result;
    }
}
