<?php


namespace Trienekens\SpecialCat\Observer\Backend;



use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\StoreManagerInterface;
use Trienekens\SpecialCat\Helper\Data;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Store\Model\Store;

class ConfigObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var State
     */
    private $_state;
    /**
     * @var CategoryFactory
     */
    private $_categoryFactory;
    /**
     * @var CategoryRepositoryInterface
     */
    private $_categoryRepositoryInterface;
    /**
     * @var Data
     */
    private $_helper;
    /**
     * @var CategoryRepository
     */
    private $_categoryRepository;
    protected $messageManager;
    protected $redirectFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private $_productCollection;
    /**
     * @var CategoryLinkManagementInterface
     */
    private $categoryLinkManagement;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $_request;

    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryRepository $categoryRepository,
        CategoryRepositoryInterface $categoryRepositoryInterface,
        Data $helper,
        MessageManagerInterface $messageManager,
        RedirectFactory $redirectFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        CategoryLinkManagementInterface $categoryLinkManagement,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\State $state
    )
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryRepository = $categoryRepository;
        $this->_categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->_helper = $helper;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->_productCollection = $productCollection;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->_request = $request;
        $this->state = $state;
    }

    public function execute(Observer $observer)
    {
        $storeID = (int) $this->_request->getParam('store', 0);
        $enable = $this->_helper->getGeneralConfig('enable',$storeID);
        $catName = $this->_helper->getGeneralConfig('display_text',$storeID);
        $parentID = $this->_helper->getGeneralConfig('parent_id',$storeID);
        $categoris = $this->_categoryFactory->create()->getCollection()->addAttributeToFilter('name',$catName)->addAttributeToFilter('parent_id',['eq'=>$parentID])->setPageSize(1);
        if($enable == 0 && $catName && $parentID){
            if($categoris->getSize()) {
                $categoris->getFirstItem()->setData('store_id', Store::DEFAULT_STORE_ID)->setIsActive(0)->save();
                $categoris->getFirstItem()->setData('store_id', $storeID)->setIsActive(0)->save();
                $message = "Category Disabled";
                $this->messageManager->addSuccessMessage($message);
                return $this->redirectFactory->create()->setPath('*/*/');
            }
        }else{
            if($enable && $catName !='' && $parentID !=''){
                if($categoris->getSize()) {
                    $categoris->getFirstItem()->setData('store_id', Store::DEFAULT_STORE_ID)->setIsActive(1)->save();
                    $categoris->getFirstItem()->setData('store_id', $storeID)->setIsActive(1)->save();
                    $catID = $categoris->getFirstItem()->getId();
                }else {
                    $categoryCreate = $this->_categoryFactory->create();
                    $categoryCreate->setName($catName);
                    $categoryCreate->setParentId($parentID);
                    $categoryCreate->setStoreId($storeID);
                    $categoryCreate->setIsActive(true);
                    $categoryCreate->setCustomAttributes([
                        'description' => 'Category for all sale products',
                    ]);
                    $cat = $this->_categoryRepositoryInterface->save($categoryCreate);
                    $catID = $cat->getId();
                }
                // Trienekens: Fource update missing website_id 5
                if ($storeID == 5) {
                    $websiteID = 4;
                } elseif ($storeID == 4) {
                    $websiteID = 3;
                } else {
                    $websiteID = $storeID;
                }
                $collectionProduct = $this->_productCollection->addAttributeToFilter('special_price', ['gt' => 0])->addCategoriesFilter(['nin' => [$catID]])->addWebsiteFilter($websiteID);
                if (!empty($collectionProduct->getData())) {
                    foreach ($collectionProduct as $product) {
                        $categoryIds = array_unique(
                            array_merge(
                                $product->getCategoryIds(),
                                [$catID]
                            )
                        );
                        $productSku = $product->getSku();
                        $this->assignedProductToCategory($productSku, $categoryIds);
                    }
                }
                $message = "Category Enabled";
                $this->messageManager->addSuccessMessage($message);
                return $this->redirectFactory->create()->setPath('*/*/');
            }else{
                $message = "Category is not saved. Please fill the fields";
                $this->messageManager->addErrorMessage($message);

                return $this->redirectFactory->create()->setPath('*/*/');
            }
        }
    }

    public function assignedProductToCategory(string $productSku, array $categoryIds)
    {
        $hasProductAssignedSuccess = false;
        try {
            $hasProductAssignedSuccess = $this->categoryLinkManagement->assignProductToCategories($productSku, $categoryIds);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $hasProductAssignedSuccess;
    }

}
