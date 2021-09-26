<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Mass status controller
 */
class MassStatus extends AbstractMassAction
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eriocnemis_AutoCancel::rule_edit';

    /**
     * Change specified statuses
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (true !== $this->getRequest()->isPost()) {
            $this->messageManager->addErrorMessage(
                (string)__('Wrong request.')
            );
            return $this->resultRedirectFactory->create()->setPath('*/*');
        }

        try {
            $collection = $this->filter->getCollection(
                $this->collectionFactory->create()
            );

            $size = $collection->getSize();
            if (!$size) {
                $this->messageManager->addError(
                    (string)__('Please correct the rules you requested.')
                );
                return $this->resultRedirectFactory->create()->setPath('*/*/index');
            }

            $status = (int)$this->getRequest()->getParam('status');
            $collection->setDataToAll('status', $status);
            $collection->walk('save');

            $this->messageManager->addSuccess(
                (string)__('A total of %1 record(s) have been modified.', $size)
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addError(
                $e->getMessage()
            );
        } catch (\Exception $e) {
            $this->messageManager->addError(
                (string)__('We can\'t change status these rules right now. Please review the log and try again.')
            );
            $this->logger->critical($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
