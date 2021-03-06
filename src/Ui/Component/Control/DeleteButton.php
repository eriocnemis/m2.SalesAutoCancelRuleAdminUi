<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\Component\Control;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Represents delete button with pre-configured options
 * Provide an ability to show confirmation message on click on the "Delete" button
 *
 * @api
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var string
     */
    private $confirmationMessage;

    /**
     * @var string
     */
    private $idFieldName;

    /**
     * @var string
     */
    private $deleteRoutePath;

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
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     * @param AuthorizationInterface $authorization
     * @param string $idFieldName
     * @param string $aclResource
     * @param string $confirmationMessage
     * @param string $deleteRoutePath
     * @param int $sortOrder
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder,
        Escaper $escaper,
        AuthorizationInterface $authorization,
        string $idFieldName,
        string $aclResource,
        string $confirmationMessage = 'Are you sure you want to do this?',
        string $deleteRoutePath = '*/*/delete',
        int $sortOrder = 0
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->escaper = $escaper;
        $this->authorization = $authorization;
        $this->confirmationMessage = $confirmationMessage;
        $this->idFieldName = $idFieldName;
        $this->deleteRoutePath = $deleteRoutePath;
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
        $fieldId = $this->escape($this->request->getParam($this->idFieldName));
        $data = [
            'label' => __('Delete'),
            'class' => 'delete',
            'sort_order' => $this->sortOrder,
            'disabled' => !$this->isAllowed() || empty($fieldId)
        ];

        if (!empty($fieldId)) {
            $url = $this->urlBuilder->getUrl($this->deleteRoutePath);
            $message = $this->escape(__($this->confirmationMessage));
            $data['on_click'] = "deleteConfirm('{$message}', '{$url}', {data:{{$this->idFieldName}:{$fieldId}}})";
        }
        return $data;
    }

    /**
     * Escape string
     *
     * @param Phrase|string $string
     * @return string
     */
    private function escape($string): string
    {
        $string = $this->escaper->escapeHtml((string)$string);
        return is_string($string) ? $this->escaper->escapeJs($string) : '';
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
