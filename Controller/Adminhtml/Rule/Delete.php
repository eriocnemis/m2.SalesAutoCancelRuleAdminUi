<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule;

use Psr\Log\LoggerInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\DeleteRuleByIdInterface;

/**
 * Delete controller
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eriocnemis_AutoCancel::rule_delete';

    /**
     * @var DeleteRuleByIdInterface
     */
    private $deleteRuleById;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Initialize controller
     *
     * @param Context $context
     * @param DeleteRuleByIdInterface $deleteRuleById
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        DeleteRuleByIdInterface $deleteRuleById,
        LoggerInterface $logger
    ) {
        $this->deleteRuleById = $deleteRuleById;
        $this->logger = $logger;

        parent::__construct(
            $context
        );
    }

    /**
     * Delete specified rule
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $result */
        $result = $this->resultRedirectFactory->create();

        $ruleId = (int)$this->getRequest()->getPost(RuleInterface::RULE_ID);
        if (!$ruleId) {
            $this->messageManager->addErrorMessage(
                (string)__('Wrong request.')
            );
            return $result->setPath('*/*');
        }

        try {
            $this->deleteRuleById->execute($ruleId);
            $this->messageManager->addSuccessMessage(
                (string)__('The Rule has been deleted.')
            );
            return $result->setPath('*/*/index');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
            );
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->messageManager->addErrorMessage(
                (string)__('We can\'t delete the rule right now. Please review the log and try again.')
            );
        }
        return $result->setPath('*/*/edit', [
                RuleInterface::RULE_ID => $ruleId,
                '_current' => true,
            ]);
        ;
    }
}
