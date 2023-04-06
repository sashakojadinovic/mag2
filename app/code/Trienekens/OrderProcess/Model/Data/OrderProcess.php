<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Model\Data;

use Trienekens\OrderProcess\Api\Data\OrderProcessInterface;

class OrderProcess extends \Magento\Framework\Api\AbstractExtensibleObject implements OrderProcessInterface
{

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId()
    {
        return $this->_get(self::ORDER_ID);
    }

    /**
     * Set order_id
     * @param string $orderId
     * @return OrderProcessInterface
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get process_status
     * @return string|null
     */
    public function getProcessStatus()
    {
        return $this->_get(self::PROCESS_STATUS);
    }

    /**
     * Set process_status
     * @param string $processStatus
     * @return OrderProcessInterface
     */
    public function setProcessStatus($processStatus)
    {
        return $this->setData(self::PROCESS_STATUS, $processStatus);
    }

    /**
     * Get process_at
     * @return string|null
     */
    public function getProcessAt()
    {
        return $this->_get(self::PROCESS_AT);
    }

    /**
     * Set process_at
     * @param string $processAt
     * @return OrderProcessInterface
     */
    public function setProcessAt($processAt)
    {
        return $this->setData(self::PROCESS_AT, $processAt);
    }

    /**
     * Get order_key
     * @return string|null
     */
    public function getOrderKey()
    {
        return $this->_get(self::ORDER_KEY);
    }

    /**
     * Set order_key
     * @param string $orderKey
     * @return OrderProcessInterface
     */
    public function setOrderKey($orderKey)
    {
        return $this->setData(self::ORDER_KEY, $orderKey);
    }
}

