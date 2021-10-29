<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesAutoCancelRuleAdminUi\Ui\DataProvider\Rule;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\Data\RuleInterface;
use Eriocnemis\SalesAutoCancelRuleApi\Api\GetRuleListInterface;

/**
 * Data provider for admin grid
 *
 * @api
 */
class ListingDataProvider extends DataProvider
{
    /**
     * @var GetRuleListInterface
     */
    private $getRuleList;

    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var PoolInterface
     */
    private $modifierPool;

    /**
     * Initialize provider
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Context $context
     * @param GetRuleListInterface $getRuleList
     * @param SearchResultFactory $searchResultFactory
     * @param PoolInterface $modifierPool
     * @param mixed[] $meta
     * @param mixed[] $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Context $context,
        GetRuleListInterface $getRuleList,
        SearchResultFactory $searchResultFactory,
        PoolInterface $modifierPool,
        array $meta = [],
        array $data = []
    ) {
        $this->getRuleList = $getRuleList;
        $this->searchResultFactory = $searchResultFactory;
        $this->modifierPool = $modifierPool;

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
        $data = parent::getData();
        /** @var ModifierInterface $modifier */
        foreach ($this->modifierPool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }
        return $data;
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
     * Retrieve search result
     *
     * @return SearchResultInterface
     */
    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->getRuleList->execute($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            RuleInterface::RULE_ID
        );
    }
}
