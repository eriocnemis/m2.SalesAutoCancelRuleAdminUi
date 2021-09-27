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
 * Mass delete controller
 */
class MassDelete extends AbstractMassAction
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eriocnemis_AutoCancel::rule_delete';

    /**
     * Delete specified rules
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        try {
            $collection = $this->filter->getCollection(
                $this->collectionFactory->create()
            );

            $size = $collection->getSize();
            if (!$size) {
                $this->messageManager->addError(
                    (string)__('Please correct the rules you requested.')
                );
                return $this->resultRedirectFactory->create()->setPath('*/*');
            }

            $collection->walk('delete');

            $this->messageManager->addSuccess(
                (string)__('You deleted a total of %1 records.', $size)
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(
                (string)__('We can\'t delete these rules right now. Please review the log and try again.')
            );
            $this->logger->critical($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}
