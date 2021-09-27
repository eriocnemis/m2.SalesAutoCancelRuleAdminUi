<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\Rule;

use Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule\MassStatus;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\AbstractMassActionTestCase;

/**
 * Mass status
 */
class MassStatusTest extends AbstractMassActionTestCase
{
    /**
     * This method is called before a test is executed
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->prepare(MassStatus::class);
    }

    /**
     * Test mass action
     *
     * @param int $size
     * @param string $method
     * @param string $message
     * @param string $path
     * @return void
     * @dataProvider dataProviderMassStatus
     * @test
     */
    public function execute($size, $method, $message, $path)
    {
        $this->runControllerTest($size, $method, $message, $path);
    }

    /**
     * Data provider of mass status test
     *
     * @return mixed[]
     */
    public function dataProviderMassStatus()
    {
        return [
            [3, 'addSuccessMessage', 'A total of 3 record(s) have been modified.', '*/*/index'],
            [0, 'addErrorMessage', 'Please correct the rules you requested.', '*/*/index'],
        ];
    }
}
