<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Controller\Adminhtml\OrderProcess;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends \Trienekens\OrderProcess\Controller\Adminhtml\OrderProcess implements HttpPostActionInterface
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Trienekens_OrderProcess::OrderProcess_export';


    /**
     * Mass actions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $orderProcessIds = $collection->getAllIds();

        try {
            foreach ($orderProcessIds as $orderId) {
                $model = $this->_objectManager->create(\Trienekens\OrderProcess\Model\OrderProcess::class)->load($orderId);
                /** TODO: Export code here */
                // update if exist
                if ($model->getId()) {
                    $model->setId($orderId);
                    $model->setData('process_status', -1);
                    $model->save();
                }
                // add if non-exist
                else {
                    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
                        ->get('Magento\Framework\App\ResourceConnection');
                    $connection= $this->_resources->getConnection();
                    $data = [
                        'order_id' => $orderId,
                        'process_status' => -1
                    ];
                    $connection->insert('trienekens_orderprocess_orderprocess', $data);
                }
            }
            $this->messageManager->addSuccessMessage(__('You removed the %1 Order(s) from order process listing successfully.', $collectionSize));

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting orders from order process listing.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}

