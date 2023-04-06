<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */

namespace Trienekens\CustomFee\Block\Sales\Totals;


class Fee extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Trienekens\CustomFee\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Trienekens\CustomFee\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
         \Trienekens\CustomFee\Helper\Data $dataHelper,
        array $data = []
    )
    {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * @return mixed
     */
    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        $order = $this->_order;
        // $store = $this->getStore();

        $fee = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $this->_order->getGrandTotal() - $this->_order->getTaxAmount() - $this->_order->getShippingAmount() - $this->_order->getSubTotal(),
                'label' => $this->_dataHelper->getFeeLabel(),
            ]
        );

        $parent->addTotal($fee, 'fee');

        return $this;
    }

}
