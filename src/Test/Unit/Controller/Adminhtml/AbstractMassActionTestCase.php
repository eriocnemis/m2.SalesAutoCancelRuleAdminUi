<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml;

use Magento\Ui\Component\MassAction\Filter;
use Eriocnemis\SalesAutoCancelRule\Model\ResourceModel\Rule\Collection;
use Eriocnemis\SalesAutoCancelRule\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Abstract test case from mass action
 */
abstract class AbstractMassActionTestCase extends AbstractActionTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $collection;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $filter;

    /**
     * Prepare test
     *
     * @param string $className
     * @return void
     */
    protected function prepare(string $className)
    {
        $collectionFactory = $this->createMock(CollectionFactory::class);
        $collection = $this->createMock(Collection::class);
        $filter = $this->createMock(Filter::class);

        $this->collectionFactory = $collectionFactory;
        $this->collection = $collection;
        $this->filter = $filter;

        parent::prepare($className);
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
            'collectionFactory' => $this->collectionFactory,
            'filter' => $this->filter
        ];
    }

    /**
     * Test mass action
     *
     * @param int $size
     * @param string $method
     * @param string $message
     * @param string $path
     * @return void
     */
    protected function runControllerTest($size, $method, $message, $path)
    {
        $this->messageManager->expects($this->once())
            ->method($method)
            ->with($message);

        $this->collectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->collection);

        $this->filter->expects($this->once())
            ->method('getCollection')
            ->with($this->collection)
            ->willReturn($this->collection);

        $this->collection->expects($this->any())
            ->method('getSize')
            ->willReturn($size);

        $this->resultRedirect->expects($this->once())
            ->method('setPath')
            ->with($path)
            ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->controller->execute());
    }
}
