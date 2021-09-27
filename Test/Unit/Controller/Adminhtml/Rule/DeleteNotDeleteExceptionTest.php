<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\Rule;

use Eriocnemis\SalesAutoCancelRuleApi\Api\DeleteRuleByIdInterface;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule\Delete;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\AbstractActionTestCase;
use Exception;

/**
 * Delete
 */
class DeleteNotDeleteExceptionTest extends AbstractActionTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $deleteRuleById;

    /**
     * This method is called before a test is executed
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->deleteRuleById = $this->createMock(DeleteRuleByIdInterface::class);

        $this->prepare(Delete::class);
    }

    /**
     * Test mass action
     *
     * @return void
     * @test
     */
    public function execute()
    {
        $this->request->expects($this->once())
            ->method('getPost')
            ->willReturn(1);

        $this->messageManager->expects($this->once())
            ->method('addErrorMessage')
            ->with((string)__('We can\'t delete the rule right now. Please review the log and try again.'));

        $this->deleteRuleById->expects($this->any())
            ->method('execute')
            ->willThrowException(new Exception);

        $this->resultRedirect->expects($this->any())
            ->method('setPath')
            ->with('*/*/edit')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->controller->execute());
    }

    /**
     * Retrieve arguments of action
     *
     * @return mixed[]
     */
    protected function getArguments()
    {
        return [
            'context' => $this->context,
            'deleteRuleById' => $this->deleteRuleById
        ];
    }
}
