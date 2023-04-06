<?php

namespace Lobbster\CustomMiniCart\Plugin\Checkout\CustomerData;

class DefaultItem
{
    public function aroundGetItemData(
        \Magento\Checkout\CustomerData\AbstractItem $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item $item
    ) {
        $data = $proceed($item);
        //$result['manufacturer'] = $item->getProduct()->getAttributeText('manufacturer');
        $result['product_qty'] = $item->getProduct()->getExtensionAttributes()->getStockItem()->getQty();
        if($result['product_qty'] > 50){
            $result['product_qty'] = '50+';
        }
        return \array_merge(
            $result,
            $data
        );
    }
}
