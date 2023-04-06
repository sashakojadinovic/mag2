<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */

namespace Trienekens\CustomFee\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Data extends AbstractHelper
{
    /**
     * Custom fee config path
     */
    const CONFIG_CUSTOM_IS_ENABLED = 'customfee/customfee/status';
    const CONFIG_FEE_LABEL = 'customfee/customfee/name';

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        ProductRepositoryInterface $productRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function isModuleEnabled()
    {

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::CONFIG_CUSTOM_IS_ENABLED, $storeScope);
    }

    /**
     * Get custom fee
     *
     * @return mixed
     */
    public function getCustomFee($quote)
    {
        // get quote items
        $customFee = 0;
        try {
            //$quote = $this->checkoutSession->getQuote();
            $items = $quote->getAllItems();

            if ($items) {
                foreach ($items as $item) {
                    $productId = $item->getProductId();
                    $qty = $item->getQty();
                    $product = $this->productRepository->getById($productId);
                    $additionalCost = $product->getAttributeText('additional_cost');
                    if ($additionalCost) {
                        $customFee += $this->getCost($additionalCost) * $qty;
                    }
                }
            }
        } catch (NoSuchEntityException $e) {
            // TODO: add exception case
            return $customFee;
        } catch (LocalizedException $e) {
            // TODO: add exception case
            return $customFee;
        }

        return $customFee;
    }

    /**
     * Get custom fee
     *
     * @return mixed
     */
    public function getFeeLabel()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::CONFIG_FEE_LABEL, $storeScope);
    }

    /**
     * Get custom fee
     *
     * @return mixed
     */
    public function getFeeLabelDetails($quote)
    {
        // get quote items
        $details = [];
        try {
            $items = $quote->getAllItems();

            if ($items) {
                foreach ($items as $item) {
                    $productId = $item->getProductId();
                    $product = $this->productRepository->getById($productId);
                    $additionalCost = $product->getAttributeText('additional_cost');
                    if ($additionalCost) {
                        $details[] = $this->getCostLabel($additionalCost);
                    }
                }
            }
        } catch (NoSuchEntityException $e) {
            // TODO: add exception case
            return '';
        } catch (LocalizedException $e) {
            // TODO: add exception case
            return '';
        }

        return '(' . implode(',', $details) . ')';
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getCost($value)
    {
        $costs = array(
            'Toeslag Pakket L (Regulier)' => 6.80,
            'Toeslag Pakket XL (Regulier)' => 26.80,
            'Toeslag Pakket XXL (Regulier)' => 47.90,
            'Toeslag Pakket XXXL (Wegtransport)' => 49.90,
        );

        return isset($costs[$value]) ? $costs[$value] : 0;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getCostLabel($value)
    {
        $costLabels = array(
            'Toeslag Pakket L (Regulier)' => 'Pakket L',
            'Toeslag Pakket XL (Regulier)' => 'Pakket XL',
            'Toeslag Pakket XXL (Regulier)' => 'Pakket XXL',
            'Toeslag Pakket XXXL (Wegtransport)' => 'Pakket XXXL',
        );

        return isset($costLabels[$value]) ? $costLabels[$value] : 0;
    }
}
