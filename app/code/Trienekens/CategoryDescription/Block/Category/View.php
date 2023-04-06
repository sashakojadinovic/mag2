<?php
namespace Trienekens\CategoryDescription\Block\Category;

class View extends \Magento\Catalog\Block\Category\View
{

    protected $categories;
    protected $categoryRepository;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        $this->categories = $collectionFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $layerResolver, $registry, $categoryHelper, $data);
    }


    /**
     * @param $id
     * @return \Magento\Catalog\Api\Data\CategoryInterface|mixed|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategory($id) {
        $category = $this->categoryRepository->get($id, $this->_storeManager->getStore()->getId());
        return $category;
    }

    /**
     * @param $id
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategoryImage($id)
    {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $category = $_objectManager->create('Magento\Catalog\Model\Category')->load($id);
        return $category->getImageUrl();
    }
}