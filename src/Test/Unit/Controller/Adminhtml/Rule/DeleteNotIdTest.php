<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\Rule;

use Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule\Delete;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\AbstractActionTestCase;

/**
 * Delete
 */
class DeleteNotIdTest extends AbstractActionTestCase
{
    /**
     * This method is called before a test is executed
     *
     * @return void
     */
    protected function setUp(): void
    {
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
            ->willReturn(null);

        $this->resultRedirect->expects($this->once())
            ->method('setPath')
            ->with('*/*')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->controller->execute());
    }
}
