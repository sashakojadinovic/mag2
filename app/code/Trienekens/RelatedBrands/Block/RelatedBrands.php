<?php
/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_RelatedBrands
 */

namespace Trienekens\RelatedBrands\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Mageplaza\Shopbybrand\Api\BrandRepositoryInterface;
use Mageplaza\Shopbybrand\Block\Brand;
use Mageplaza\Shopbybrand\Helper\Data as BrandHelper;

class RelatedBrands extends \Magento\Catalog\Block\Product\ListProduct {

    protected $_collection;

    protected $categoryRepository;

    protected $brandRepository;

    protected $brandHelper;

    protected $_resource;

    protected $_registry;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        BrandRepositoryInterface $brandRepository,
        BrandHelper $brandHelper,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->brandHelper = $brandHelper;
        $this->_collection = $collection;
        $this->_resource = $resource;
        $this->_registry = $registry;

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    protected function _getProductCollection() {
        return $this->getProducts();
    }

    public function getProducts() {
        $count = $this->getProductCount();
        $category_ids = $this->getCategoryIds();
        $collection = clone $this->_collection;
        $collection->clear()->getSelect()->reset(\Magento\Framework\DB\Select::WHERE)->reset(\Magento\Framework\DB\Select::ORDER)->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)->reset(\Magento\Framework\DB\Select::GROUP);

        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addUrlRewrite()
            ->addAttributeToSort('created_at','desc')
            ->addCategoriesFilter(['in' => $category_ids]);

        $brandId = $this->getCurrentBrandId();
        if ($brandId) {
            $collection->addAttributeToFilter($this->brandHelper->getAttributeCode(), $brandId);
        }

        $collection->getSelect()
            ->order('created_at','desc')
            ->limit($count);

        return $collection;
    }

    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    public function getCategoryIds()
    {
        return $this->getCurrentProduct()->getCategoryIds();
    }

    public function getCurrentBrandId()
    {
        return $this->brandRepository->getBrandBySku($this->getCurrentProduct()->getSku())->getOptionId();
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getProductCount() {
        $limit = $this->getData("product_count");
        if(!$limit)
            $limit = 10;
        return $limit;
    }
}
