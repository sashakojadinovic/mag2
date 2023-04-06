<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Trienekens\OrderProcess\Api\Data\OrderProcessInterfaceFactory;
use Trienekens\OrderProcess\Api\Data\OrderProcessSearchResultsInterfaceFactory;
use Trienekens\OrderProcess\Api\OrderProcessRepositoryInterface;
use Trienekens\OrderProcess\Model\ResourceModel\OrderProcess as ResourceOrderProcess;
use Trienekens\OrderProcess\Model\ResourceModel\OrderProcess\CollectionFactory as OrderProcessCollectionFactory;

class OrderProcessRepository implements OrderProcessRepositoryInterface
{

    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $orderProcessCollectionFactory;

    protected $searchResultsFactory;

    protected $orderProcessFactory;

    protected $dataOrderProcessFactory;

    private $storeManager;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;


    /**
     * @param ResourceOrderProcess $resource
     * @param OrderProcessFactory $orderProcessFactory
     * @param OrderProcessInterfaceFactory $dataOrderProcessFactory
     * @param OrderProcessCollectionFactory $orderProcessCollectionFactory
     * @param OrderProcessSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceOrderProcess $resource,
        OrderProcessFactory $orderProcessFactory,
        OrderProcessInterfaceFactory $dataOrderProcessFactory,
        OrderProcessCollectionFactory $orderProcessCollectionFactory,
        OrderProcessSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->orderProcessFactory = $orderProcessFactory;
        $this->orderProcessCollectionFactory = $orderProcessCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOrderProcessFactory = $dataOrderProcessFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Trienekens\OrderProcess\Api\Data\OrderProcessInterface $orderProcess
    ) {
        /* if (empty($orderProcess->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $orderProcess->setStoreId($storeId);
        } */
        
        $orderProcessData = $this->extensibleDataObjectConverter->toNestedArray(
            $orderProcess,
            [],
            \Trienekens\OrderProcess\Api\Data\OrderProcessInterface::class
        );
        
        $orderProcessModel = $this->orderProcessFactory->create()->setData($orderProcessData);
        
        try {
            $this->resource->save($orderProcessModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orderProcess: %1',
                $exception->getMessage()
            ));
        }
        return $orderProcessModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($orderProcessId)
    {
        $orderProcess = $this->orderProcessFactory->create();
        $this->resource->load($orderProcess, $orderProcessId);
        if (!$orderProcess->getId()) {
            throw new NoSuchEntityException(__('OrderProcess with id "%1" does not exist.', $orderProcessId));
        }
        return $orderProcess->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->orderProcessCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Trienekens\OrderProcess\Api\Data\OrderProcessInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Trienekens\OrderProcess\Api\Data\OrderProcessInterface $orderProcess
    ) {
        try {
            $orderProcessModel = $this->orderProcessFactory->create();
            $this->resource->load($orderProcessModel, $orderProcess->getOrderprocessId());
            $this->resource->delete($orderProcessModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the OrderProcess: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($orderProcessId)
    {
        return $this->delete($this->get($orderProcessId));
    }
}

