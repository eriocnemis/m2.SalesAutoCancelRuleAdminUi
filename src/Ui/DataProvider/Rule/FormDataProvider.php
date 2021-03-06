<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\DataProvider\Rule;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\EntityManager\HydratorInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\GetRuleByIdInterface;

/**
 * Data provider for admin export job form
 *
 * @api
 */
class FormDataProvider extends DataProvider
{
    /**
     * @var GetRuleByIdInterface
     */
    private $getRuleById;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var PoolInterface
     */
    private $modifierPool;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Initialize provider
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Context $context
     * @param GetRuleByIdInterface $getRuleById
     * @param HydratorInterface $hydrator
     * @param PoolInterface $modifierPool
     * @param mixed[] $meta
     * @param mixed[] $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Context $context,
        GetRuleByIdInterface $getRuleById,
        HydratorInterface $hydrator,
        PoolInterface $modifierPool,
        array $meta = [],
        array $data = []
    ) {
        $this->dataPersistor = $context->getDataPersistor();
        $this->modifierPool = $modifierPool;
        $this->getRuleById = $getRuleById;
        $this->hydrator = $hydrator;

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $context->getReporting(),
            $context->getSearchCriteriaBuilder(),
            $context->getRequest(),
            $context->getFilterBuilder(),
            $meta,
            $data
        );
    }

    /**
     * Retrieve data
     *
     * @return mixed[]
     */
    public function getData()
    {
        $ruleId = $this->getRuleId();
        if (!isset($this->data[$ruleId])) {
            $this->data[$ruleId]['rule'] = $this->modifyData($this->loadData($ruleId));
        }
        return $this->data;
    }

    /**
     * Retrieve meta data
     *
     * @return mixed[]
     */
    public function getMeta()
    {
        $meta = parent::getMeta();
        /** @var ModifierInterface $modifier */
        foreach ($this->modifierPool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }
        return $meta;
    }

    /**
     * Retrieve rule id
     *
     * @return int|null
     */
    private function getRuleId(): ?int
    {
        $ruleId = $this->request->getParam($this->getRequestFieldName());
        return $ruleId ? (int)$ruleId : null;
    }

    /**
     * Retrieve rule data
     *
     * @param int|null $ruleId
     * @return mixed[]
     */
    private function loadData($ruleId): array
    {
        $data = $this->dataPersistor->get('eriocnemis_sales_autocancel_rule') ?: [];
        if (null !== $ruleId) {
            $rule = $this->getRuleById->execute($ruleId);
            $data = $this->hydrator->extract($rule);
        }
        $this->dataPersistor->clear('eriocnemis_sales_autocancel_rule');

        return $data;
    }

    /**
     * Retrieve modifier data
     *
     * @param  mixed[] $data
     * @return mixed[]
     */
    private function modifyData(array $data): array
    {
        /** @var ModifierInterface $modifier */
        foreach ($this->modifierPool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }
        return $data;
    }
}
