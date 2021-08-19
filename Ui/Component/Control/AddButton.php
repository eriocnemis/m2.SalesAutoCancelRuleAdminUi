<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\Component\Control;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Represents add button with pre-configured options
 *
 * @api
 */
class AddButton implements ButtonProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var string
     */
    private $addRoutePath;

    /**
     * @var int
     */
    private $sortOrder;

    /**
     * @var string
     */
    private $aclResource;

    /**
     * Initialize button provider
     *
     * @param UrlInterface $urlBuilder
     * @param AuthorizationInterface $authorization
     * @param string $aclResource
     * @param string $addRoutePath
     * @param int $sortOrder
     */
    public function __construct(
        UrlInterface $urlBuilder,
        AuthorizationInterface $authorization,
        string $aclResource,
        string $addRoutePath = '*/*/new',
        int $sortOrder = 0
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->authorization = $authorization;
        $this->addRoutePath = $addRoutePath;
        $this->aclResource = $aclResource;
        $this->sortOrder = $sortOrder;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return mixed
     */
    public function getButtonData()
    {
        $data = [
            'label' => __('Add New'),
            'class' => 'primary',
            'url' => $this->urlBuilder->getUrl($this->addRoutePath),
            'sort_order' => $this->sortOrder,
            'disabled' => !$this->isAllowed()
        ];
        return $data;
    }

    /**
     * Check current user permission on resource and privilege
     *
     * @return  bool
     */
    private function isAllowed(): bool
    {
        return $this->authorization->isAllowed($this->aclResource);
    }
}
