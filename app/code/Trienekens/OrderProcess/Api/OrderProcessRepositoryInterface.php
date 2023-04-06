<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Trienekens\OrderProcess\Api\Data\OrderProcessInterface;
use Trienekens\OrderProcess\Api\Data\OrderProcessSearchResultsInterface;

interface OrderProcessRepositoryInterface
{

    /**
     * Save OrderProcess
     * @param OrderProcessInterface $orderProcess
     * @return OrderProcessInterface
     * @throws LocalizedException
     */
    public function save(
        OrderProcessInterface $orderProcess
    );

    /**
     * Retrieve OrderProcess
     * @param string $orderprocessId
     * @return OrderProcessInterface
     * @throws LocalizedException
     */
    public function get($orderprocessId);

    /**
     * Retrieve OrderProcess matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return OrderProcessSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete OrderProcess
     * @param OrderProcessInterface $orderProcess
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        OrderProcessInterface $orderProcess
    );

    /**
     * Delete OrderProcess by ID
     * @param string $orderprocessId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($orderprocessId);
}

