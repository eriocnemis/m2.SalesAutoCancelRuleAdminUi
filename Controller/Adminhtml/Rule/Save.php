<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\GetExceptionInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\ResolveResultInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Api\SaveRuleDataInterface;

/**
 * Save controller
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eriocnemis_AutoCancel::rule_edit';

    /**
     * @var SaveRuleDataInterface
     */
    private $saveRuleData;

    /**
     * @var ResolveResultInterface
     */
    private $resolveResult;

    /**
     * @var GetExceptionInterface
     */
    private $getException;

    /**
     * Initialize controller
     *
     * @param Context $context
     * @param SaveRuleDataInterface $saveRuleData
     * @param ResolveResultInterface $resolveResult
     * @param GetExceptionInterface $getException
     */
    public function __construct(
        Context $context,
        SaveRuleDataInterface $saveRuleData,
        ResolveResultInterface $resolveResult,
        GetExceptionInterface $getException
    ) {
        $this->saveRuleData = $saveRuleData;
        $this->resolveResult = $resolveResult;
        $this->getException = $getException;

        parent::__construct(
            $context
        );
    }

    /**
     * Save rule
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        try {
            $rule = $this->saveRuleData->execute($this->getRequest());
            return $this->resolveResult->execute($this, $rule->getId());
        } catch (\Exception $e) {
            $this->getException->execute($e);
        }
        return $this->resolveResult->execute($this);
    }
}
