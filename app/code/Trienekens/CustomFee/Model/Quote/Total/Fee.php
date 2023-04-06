<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */
namespace Trienekens\CustomFee\Model\Quote\Total;

use Magento\Store\Model\ScopeInterface;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    protected $helperData;

    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $quoteValidator = null;

    public function __construct(\Magento\Quote\Model\QuoteValidator $quoteValidator,
                                \Trienekens\CustomFee\Helper\Data $helperData)
    {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $enabled = $this->helperData->isModuleEnabled();
        if ($enabled) {
            // calculate custom fee
            $current_fee = $quote->getFee();
            $new_fee = $this->helperData->getCustomFee($quote);
            $fee = $new_fee - $current_fee;

            // set custom fee
            $total->setTotalAmount('fee', $fee);
            $total->setBaseTotalAmount('fee', $fee);

            $total->setFee($fee);
            $total->setBaseFee($fee);

            $total->setGrandTotal($total->getGrandTotal());
            $total->setBaseGrandTotal($total->getBaseGrandTotal());

            // re-calculate tax
            $curTax = $quote->getShippingAddress()->getTaxAmount();
            $curTotal = $quote->getSubtotal() + $quote->getShippingAddress()->getShippingAmount();
            if ($curTotal) {
                $rate = $curTax / $curTotal;

                $updatedTotal = $curTotal + $fee;
                $updatedTax = $updatedTotal * $rate;
                // TODO: Update tax with new value
            }
        }
        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $enabled = $this->helperData->isModuleEnabled();
        $fee = $quote->getFee();

        $result = [];
        if ($enabled  && $fee) {
            $result = [
                'code' => 'fee',
                'title' => $this->helperData->getFeeLabel(),
                'value' => $fee
            ];
        }
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return $this->helperData->getFeeLabel();
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     */
    protected function clearValues(\Magento\Quote\Model\Quote\Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }
}
