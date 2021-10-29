<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\Rule;

use Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule\MassDelete;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\AbstractMassActionTestCase;

/**
 * Mass delete
 */
class MassDeleteTest extends AbstractMassActionTestCase
{
    /**
     * This method is called before a test is executed
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->prepare(MassDelete::class);
    }

    /**
     * Test mass action
     *
     * @param int $size
     * @param string $method
     * @param string $message
     * @param string $path
     * @return void
     * @dataProvider dataProviderMassDelete
     * @test
     */
    public function execute($size, $method, $message, $path)
    {
        $this->runControllerTest($size, $method, $message, $path);
    }

    /**
     * Data provider of mass delete test
     *
     * @return mixed[]
     */
    public function dataProviderMassDelete()
    {
        return [
            [3, 'addSuccessMessage', 'You deleted a total of 3 records.', '*/*/index'],
            [0, 'addErrorMessage', 'Please correct the rules you requested.', '*/*/index'],
        ];
    }
}
