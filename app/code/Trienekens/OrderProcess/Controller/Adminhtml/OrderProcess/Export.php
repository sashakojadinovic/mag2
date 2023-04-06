<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Controller\Adminhtml\OrderProcess;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Trienekens\OrderProcess\Helper\Data;
use Trienekens\OrderProcess\Model\OrderProcessFactory;

class Export extends \Magento\Backend\App\Action
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Trienekens_OrderProcess::OrderProcess_export';

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var OrderProcessFactory
     */
    protected $orderProcess;

    /**
     * @param Context $context
     * @param Data $orderProcessHelper
     * @param OrderProcessFactory $orderProcess
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        Data $orderProcessHelper,
        OrderProcessFactory $orderProcess,
        DataPersistorInterface $dataPersistor
    ) {
        $this->helper = $orderProcessHelper;
        $this->orderProcess = $orderProcess;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('order_id');
        $exportRule = (bool) $this->getRequest()->getParam('export_rule');

        if (!$id) {
            $this->messageManager->addErrorMessage(__('Order ID is missing.'));
            return $resultRedirect->setPath('*/*/');
        }

        /** @var \Trienekens\OrderProcess\Model\OrderProcess $model */
        $model = $this->orderProcess->create()->load($id);
        try {
            // generate & upload xml file
            $this->helper->generateFile($id, $exportRule);
            $isSucceed = false;
            //$isSucceed = $this->helper->uploadOrder($id);
            if ($isSucceed) {
                $processStatus = Data::STATUS_SUCCESS;
                $this->messageManager->addSuccessMessage(__('You exported the Order successfully.'));
            } else {
                $processStatus = Data::STATUS_FAILURE;
                $this->messageManager->addSuccessMessage(__('FTP upload went wrong while exporting orders.'));
            }

            // update if exist
            if ($model->getId()) {
                $model->setId($id);
                $model->setData('process_status', $processStatus);
                $model->save();
            }
            // add if non-exist
            else {
                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
                    ->get('Magento\Framework\App\ResourceConnection');
                $connection= $this->_resources->getConnection();
                $data = [
                    'order_id' => $id,
                    'process_status' => $processStatus
                ];
                $connection->insert('trienekens_orderprocess_orderprocess', $data);
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while exporting orders.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}

