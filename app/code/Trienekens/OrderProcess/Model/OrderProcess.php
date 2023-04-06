<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Model;

use Magento\Framework\Api\DataObjectHelper;
use Trienekens\OrderProcess\Api\Data\OrderProcessInterface;
use Trienekens\OrderProcess\Api\Data\OrderProcessInterfaceFactory;

class OrderProcess extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $orderprocessDataFactory;

    protected $_eventPrefix = 'trienekens_orderprocess_orderprocess';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param OrderProcessInterfaceFactory $orderprocessDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Trienekens\OrderProcess\Model\ResourceModel\OrderProcess $resource
     * @param \Trienekens\OrderProcess\Model\ResourceModel\OrderProcess\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        OrderProcessInterfaceFactory $orderprocessDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Trienekens\OrderProcess\Model\ResourceModel\OrderProcess $resource,
        \Trienekens\OrderProcess\Model\ResourceModel\OrderProcess\Collection $resourceCollection,
        array $data = []
    ) {
        $this->orderprocessDataFactory = $orderprocessDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve orderprocess model with orderprocess data
     * @return OrderProcessInterface
     */
    public function getDataModel()
    {
        $orderprocessData = $this->getData();
        
        $orderprocessDataObject = $this->orderprocessDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $orderprocessDataObject,
            $orderprocessData,
            OrderProcessInterface::class
        );
        
        return $orderprocessDataObject;
    }
}

