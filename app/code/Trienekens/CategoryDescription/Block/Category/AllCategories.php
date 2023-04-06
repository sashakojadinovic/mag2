<?php
namespace Trienekens\CategoryDescription\Block\Category;

use Magento\Framework\Exception\NoSuchEntityException;

class AllCategories extends \Magento\Framework\View\Element\Template
{

    protected $_storeManager;
    protected $_categoryCollection;
    protected $_categoryHelper;
    /**
     * Category factory
     *
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_categoryCollection = $categoryCollection;
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryHelper = $categoryHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getStoreCategories()
    {
        try {
            $currentStore = $this->_storeManager->getStore();
            $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
            return $this->_categoryCollection->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('is_active', 1)
                ->addAttributeToFilter('path', array('like' => "1/{$rootCategoryId}/%"))
                ->setStore($currentStore);
        } catch (NoSuchEntityException $e) {
            return [];
        }
    }
}