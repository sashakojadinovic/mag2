<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Api\Data;

interface OrderProcessSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get OrderProcess list.
     * @return OrderProcessInterface[]
     */
    public function getItems();

    /**
     * Set order_id list.
     * @param OrderProcessInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

