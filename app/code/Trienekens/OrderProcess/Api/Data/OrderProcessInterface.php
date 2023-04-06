<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Api\Data;

interface OrderProcessInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PROCESS_STATUS = 'process_status';
    const ORDER_ID = 'order_id';
    const PROCESS_AT = 'process_at';
    const ORDER_KEY = 'order_key';

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $orderId
     * @return OrderProcessInterface
     */
    public function setOrderId($orderId);

    /**
     * Get process_status
     * @return string|null
     */
    public function getProcessStatus();

    /**
     * Set process_status
     * @param string $processStatus
     * @return OrderProcessInterface
     */
    public function setProcessStatus($processStatus);

    /**
     * Get process_at
     * @return string|null
     */
    public function getProcessAt();

    /**
     * Set process_at
     * @param string $processAt
     * @return OrderProcessInterface
     */
    public function setProcessAt($processAt);

    /**
     * Get order_key
     * @return string|null
     */
    public function getOrderKey();

    /**
     * Set order_key
     * @param string $orderKey
     * @return OrderProcessInterface
     */
    public function setOrderKey($orderKey);
}

