<?php


namespace Trienekens\SpecialCat\Observer;


use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Trienekens\SpecialCat\Helper\Data;

class ProductSaveBefore implements ObserverInterface
{

    /**
     * @var Data
     */
    private $_helper;

    public function __construct(CategoryFactory $categoryFactory, Data $helper)
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_helper = $helper;
    }

    public function execute(Observer $observer)
    {
        $currentProduct = $observer->getProduct();
        $productStatus = $currentProduct->getStatus();
        $alowedWebsites = $currentProduct->getWebsiteIds();
        if(!empty($alowedWebsites) && $productStatus == 1){
            foreach ($alowedWebsites as $alowedWebsite){
                // Trienekens: Fource update missing website_id 5
                if ($alowedWebsite == 3) {
                    $storeId = 4;
                } elseif ($alowedWebsite == 4) {
                    $storeId = 5;
                } else {
                    $storeId = $alowedWebsite;
                }
                $enable = $this->_helper->getGeneralConfig('enable', $storeId);
                $catName = $this->_helper->getGeneralConfig('display_text', $storeId);
                $parentID = $this->_helper->getGeneralConfig('parent_id', $storeId);
                $categoris = $this->_categoryFactory->create()->getCollection()->addAttributeToFilter('name',$catName)->addAttributeToFilter('parent_id',['eq'=>$parentID])->setPageSize(1);
                if($enable) {
                    if ($categoris->getSize()) {
                        $salesOfferCategoryID = $categoris->getFirstItem()->getId();
                        $categoryIds = array_unique(
                            array_merge(
                                $currentProduct->getCategoryIds(),
                                [$salesOfferCategoryID]
                            )
                        );
                        if ($currentProduct->getSpecialPrice() > 0 ) {
                            $currentProduct->setCategoryIds($categoryIds);
                        } else {
                            if (in_array($salesOfferCategoryID, $categoryIds)) {
                                $unsetCat = array_search($salesOfferCategoryID, $categoryIds);
                                unset($categoryIds[$unsetCat]);
                                $currentProduct->setCategoryIds($categoryIds);
                            }
                        }
                    }
                }else{
                    $salesOfferCategoryID = $categoris->getFirstItem()->getId();
                    $categoryIds = array_unique(
                        array_merge(
                            $currentProduct->getCategoryIds(),
                            [$salesOfferCategoryID]
                        )
                    );
                    if (in_array($salesOfferCategoryID, $categoryIds)) {
                        $unsetCat = array_search($salesOfferCategoryID, $categoryIds);
                        unset($categoryIds[$unsetCat]);
                        $currentProduct->setCategoryIds($categoryIds);
                    }
                }
            }
        }
    }
}
