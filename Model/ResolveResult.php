<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Model;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\ResolveResultInterface;

/**
 * Resolve action result
 *
 * @api
 */
class ResolveResult implements ResolveResultInterface
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * Initialize resolver
     *
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        RedirectFactory $redirectFactory
    ) {
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * Resolve result
     *
     * @param Action $action
     * @param int|null $ruleId
     * @return ResultInterface
     */
    public function execute(Action $action, int $ruleId = null): ResultInterface
    {
        /** @var Redirect $result */
        $result = $this->redirectFactory->create();
        if (null !== $ruleId) {
            if (null !== $action->getRequest()->getParam('back')) {
                return $result->setPath(
                    '*/*/edit',
                    ['_current' => true, RuleInterface::RULE_ID => $ruleId]
                );
            }
            if (null !== $action->getRequest()->getParam('redirect_to_new')) {
                return $result->setPath('*/*/new');
            }
            return $result->setPath('*/*/index');
        }

        $data = $action->getRequest()->getPost('rule');
        if (empty($data[RuleInterface::RULE_ID])) {
            return $result->setPath('*/*/new');
        }
        return $result->setPath(
            '*/*/edit',
            ['_current' => true, RuleInterface::RULE_ID => (int)$data[RuleInterface::RULE_ID]]
        );
    }
}
