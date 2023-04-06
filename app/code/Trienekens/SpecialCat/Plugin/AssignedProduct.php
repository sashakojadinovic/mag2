<?php


namespace Trienekens\SpecialCat\Plugin;



use Magento\Catalog\Model\CategoryFactory;
use Trienekens\SpecialCat\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class AssignedProduct
{
    private $_categoryFactory;
    private $_helper;
    private $_storeManager;

    public function __construct(
        CategoryFactory $categoryFactory,
        Data $helper,
        StoreManagerInterface $storeManager
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
    }

    public function afterGetProductCollection(\Magento\Catalog\Model\Layer $subject, $collection)
    {

        $storeID = (int) $this->_storeManager->getStore()->getId();

        $catName = $this->_helper->getGeneralConfig('display_text',$storeID);
        $categoris = $this->_categoryFactory->create()->getCollection()->setStoreId($storeID)->addAttributeToFilter('name', $catName)->setPageSize(1);

        if ($categoris->getSize()) {
            $salesCatId = $categoris->getFirstItem()->getId();

            if ($subject->getCurrentCategory()->getId() == $salesCatId) {
                $now = date('Y-m-d');

                $collection->addAttributeToFilter(
                    'special_from_date',
                    array('or'=> array(
                        0 => array('date' => true, 'lteq' => $now),
                        1 => array('is' => new \Zend_Db_Expr('null'))
                        )
                    ),
                    'left'
                );
                $collection  ->addAttributeToFilter(
                    'special_to_date',
                    array('or'=> array(
                        0 => array('date' => true, 'gteq' => $now),
                        1 => array('is' => new \Zend_Db_Expr('null'))
                        )
                    ),
                    'left'
                );
                $collection->addAttributeToFilter('is_saleable', 1, 'left');
                $collection->addCategoriesFilter(['in' => [$salesCatId]]);
                $collection->addAttributeToFilter('special_price', ['gt' => 0]);
            }
        }
        return $collection;
    }
}
