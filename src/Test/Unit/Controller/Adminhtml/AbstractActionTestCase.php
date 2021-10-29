<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Test\Unit\Controller\Adminhtml;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Request\Http as RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\App\Action\Context;

/**
 * Abstract test case from action
 */
abstract class AbstractActionTestCase extends TestCase
{
    /**
     * @var \Magento\Framework\App\ActionInterface
     */
    protected $controller;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $request;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $resultRedirect;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $messageManager;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * Prepare test
     *
     * @param string $className
     * @return void
     */
    protected function prepare(string $className)
    {
        $request = $this->createMock(RequestInterface::class);
        $messageManager = $this->createMock(ManagerInterface::class);
        $resultRedirect = $this->createMock(Redirect::class);
        $redirectFactory = $this->createMock(RedirectFactory::class);
        $redirectFactory->expects($this->any())->method('create')->willReturn($resultRedirect);

        $context = $this->createMock(Context::class);
        $context->expects($this->any())->method('getMessageManager')->willReturn($messageManager);
        $context->expects($this->any())->method('getResultRedirectFactory')->willReturn($redirectFactory);
        $context->expects($this->any())->method('getRequest')->willReturn($request);

        $this->context = $context;
        $this->request = $request;
        $this->resultRedirect = $resultRedirect;
        $this->messageManager = $messageManager;

        $objectManager = new ObjectManager($this);
        /** @var \Magento\Framework\App\ActionInterface $controller */
        $controller = $objectManager->getObject($className, $this->getArguments());
        $this->controller = $controller;
    }

    /**
     * Retrieve arguments of action
     *
     * @return mixed[]
     */
    protected function getArguments()
    {
        return [
            'context' => $this->context
        ];
    }
}
