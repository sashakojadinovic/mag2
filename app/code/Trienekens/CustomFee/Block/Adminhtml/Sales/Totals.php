<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */

namespace Trienekens\CustomFee\Block\Adminhtml\Sales;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Trienekens\CustomFee\Helper\Data
     */
    protected $_dataHelper;
   

    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currency;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Trienekens\CustomFee\Helper\Data $dataHelper
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Trienekens\CustomFee\Helper\Data $dataHelper,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_dataHelper = $dataHelper;
        $this->_currency = $currency;
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $order = $this->getOrder();
        $this->getSource();

        // TODO: Get fee from order not work
//        if(!$this->getSource()->getFee()) {
//            return $this;
//        }
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'value' => $order->getGrandTotal() - $order->getTaxAmount() - $order->getShippingAmount() - $order->getSubTotal(),
                'label' => $this->_dataHelper->getFeeLabel(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
