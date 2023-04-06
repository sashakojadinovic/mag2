<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CustomFee
 */
namespace Trienekens\CustomFee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class CustomFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Trienekens\CustomFee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Trienekens\CustomFee\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Trienekens\CustomFee\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger

    )
    {
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $customFeeConfig = [];
        $enabled = $this->dataHelper->isModuleEnabled();
        $quote = $this->checkoutSession->getQuote();
        $costLabel = $this->dataHelper->getFeeLabelDetails($quote);
        $customFeeConfig['fee_label'] = $this->dataHelper->getFeeLabel() . ' ' . $costLabel;
        $customFeeConfig['custom_fee_amount'] = $this->dataHelper->getCustomFee($quote);
        $customFeeConfig['show_hide_customfee_block'] = $enabled && $quote->getFee();
        $customFeeConfig['show_hide_customfee_shipblock'] = (bool)$enabled;
        return $customFeeConfig;
    }
}
