<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */
namespace Trienekens\CustomFee\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddFeeToOrderObserver implements ObserverInterface
{
    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $CustomFeeFee = $quote->getFee();
        $CustomFeeBaseFee = $quote->getBaseFee();
        if (!$CustomFeeFee || !$CustomFeeBaseFee) {
            return $this;
        }
        //Set fee data to order
        $order = $observer->getOrder();
        $order->setData('fee', $CustomFeeFee);
        $order->setData('base_fee', $CustomFeeBaseFee);

        return $this;
    }
}
