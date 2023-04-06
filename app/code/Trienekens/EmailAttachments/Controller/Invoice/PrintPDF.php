<?php
namespace Trienekens\EmailAttachments\Controller\Invoice;

use Magento\Framework\App\Filesystem\DirectoryList;

class PrintPDF extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    protected $resultForwardFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    )
    {
        parent::__construct($context);
        $this->_fileFactory = $fileFactory;
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Zend_Pdf_Exception
     */
    public function execute()
    {
        $invoiceId = $this->getRequest()->getParam('invoice_id');
        if ($invoiceId) {
            $invoice = $this->_objectManager->create(
                \Magento\Sales\Api\InvoiceRepositoryInterface::class
            )->get($invoiceId);
            if ($invoice) {
                $pdf = $this->_objectManager->create(\Magento\Sales\Model\Order\Pdf\Invoice::class)->getPdf([$invoice]);
                $fileContent = ['type' => 'string', 'value' => $pdf->render(), 'rm' => true];

                return $this->_fileFactory->create(
                    'invoice_' . $invoice->getIncrementId() . '.pdf',
                    $fileContent,
                    DirectoryList::VAR_DIR,
                    'application/pdf'
                );
            }
        } else {
            return $this->resultForwardFactory->create()->forward('sales/order/invoice');
        }
    }
}