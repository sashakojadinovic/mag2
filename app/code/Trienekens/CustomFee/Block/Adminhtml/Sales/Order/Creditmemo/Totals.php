<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */

namespace Trienekens\CustomFee\Block\Adminhtml\Sales\Order\Creditmemo;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * Order invoice
     *
     * @var \Magento\Sales\Model\Order\Creditmemo|null
     */
    protected $_creditmemo = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @var \Trienekens\CustomFee\Helper\Data
     */
    protected $_dataHelper;

    /**
     * OrderFee constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Trienekens\CustomFee\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
         \Trienekens\CustomFee\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return mixed
     */
    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        // TODO: Get fee from order not work
//        if(!$this->getSource()->getFee()) {
//            return $this;
//        }
        $fee = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $this->getSource()->getFee(),
                'label' => $this->_dataHelper->getFeeLabel(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }
}
