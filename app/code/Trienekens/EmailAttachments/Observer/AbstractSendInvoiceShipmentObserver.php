<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class AbstractSendInvoiceShipmentObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/shipment/attachinvoicepdf';

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * @var \Magento\Sales\Model\Order\Shipment $shipment
         */
        $shipment = $observer->getShipment();

        if ($this->pdfRenderer->canRender()
            && $shipment->getOrder()->hasInvoices()
            && $this->scopeConfig->getValue(
                static::XML_PATH_ATTACH_PDF,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $shipment->getStoreId()
            )
        ) {
            foreach ($shipment->getOrder()->getInvoiceCollection() as $invoice) {
                $this->contentAttacher->addPdf(
                    $this->pdfRenderer->getPdfAsString([$invoice]),
                    $this->pdfRenderer->getFileName(__('Invoice') . $invoice->getIncrementId()),
                    $observer->getAttachmentContainer()
                );
            }
        }
    }
}
