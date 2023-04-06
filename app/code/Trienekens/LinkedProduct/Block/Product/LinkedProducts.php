<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_LinkedProduct
 */

namespace Trienekens\LinkedProduct\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class LinkedProducts extends \Magento\Catalog\Block\Product\View
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $stockState;
    /**
     * @var ProductFactory
     */
    protected $productFactory;
    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @param Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface|\Magento\Framework\Pricing\PriceCurrencyInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param Image $imageHelper
     * @param ProductFactory $productFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param array $data
     * @codingStandardsIgnoreStart
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        Image $imageHelper,
        ProductFactory $productFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->stockState = $stockState;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    public function getLinkedProducts()
    {
        $groupId = $this->getProduct()->getData('pro_group_id');
        $result = [];

        if ($groupId) {
            /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
            $collection = $this->_collectionFactory->create();
            $result = $collection->addAttributeToSelect('id, sku, name')
                ->addAttributeToFilter('pro_group_id', $groupId)
                ->getAllIds();
        }
        return $result;
    }

    public function getProductById($id)
    {
        try {
            $product = $this->productFactory->create()->load($id);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        return $product;
    }

    public function getProductImageUrl($product)
    {
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }

    public function getProductStocks($product)
    {
        $stockQty = $this->stockState->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
        if ($stockQty > 50) {
            $stockQty = '50+';
        }

        return $stockQty;
    }

    public function getCurrencyWithFormat($price)
    {
        return $this->priceCurrency->format($price,true,2);
    }

}
