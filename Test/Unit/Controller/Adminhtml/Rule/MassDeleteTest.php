<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml\Rule;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Ui\Component\MassAction\Filter;
use Eriocnemis\SalesAutoCancelRule\Model\ResourceModel\Rule\Collection;
use Eriocnemis\SalesAutoCancelRule\Model\ResourceModel\Rule\CollectionFactory;
use Eriocnemis\SalesAutoCancelRuleAdminUi\Controller\Adminhtml\Rule\MassDelete;

/**
 * Mass delete
 */
class MassDeleteTest extends TestCase
{
    /**
     * @var MassDelete
     */
    private $massDelete;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $collection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $resultRedirect;

    /**
     * This method is called before a test is executed
     *
     * @return void
     */
    protected function setUp(): void
    {
        $messageManager = $this->createMock(ManagerInterface::class);

        $resultRedirect = $this->createMock(Redirect::class);
        $redirectFactory = $this->createMock(RedirectFactory::class);
        $redirectFactory->expects($this->any())->method('create')->willReturn($resultRedirect);

        $context = $this->createMock(Context::class);
        $context->expects($this->any())->method('getMessageManager')->willReturn($messageManager);
        $context->expects($this->any())->method('getResultRedirectFactory')->willReturn($redirectFactory);

        $collection = $this->createMock(Collection::class);
        $collectionFactory = $this->createMock(CollectionFactory::class);
        $collectionFactory->expects($this->once())->method('create')->willReturn($collection);

        $filter = $this->createMock(Filter::class);

        $objectManager = new ObjectManager($this);
        /** @var MassDelete $massDelete */
        $massDelete = $objectManager->getObject(
            MassDelete::class,
            [
                'context' => $context,
                'collectionFactory' => $collectionFactory,
                'filter' => $filter

            ]
        );

        $this->massDelete = $massDelete;
        $this->resultRedirect = $resultRedirect;
        $this->collection = $collection;
        $this->filter = $filter;
    }

    /**
     * Test mass action
     *
     * @return void
     * @test
     */
    public function execute()
    {
        $this->collection->expects($this->once())
            ->method('getSize')
            ->willReturn(3);

        $this->filter->expects($this->once())
            ->method('getCollection')
            ->with($this->collection)
            ->willReturn($this->collection);

        $this->resultRedirect->expects($this->any())
            ->method('setPath')
            ->with('*/*')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->massDelete->execute());
    }
}
