<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class AbstractSendInvoiceObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/invoice/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/invoice/attachagreement';

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * @var \Magento\Sales\Api\Data\InvoiceInterface $invoice
         */
        $invoice = $observer->getInvoice();
        if ($this->pdfRenderer->canRender()
            && $this->scopeConfig->getValue(
                static::XML_PATH_ATTACH_PDF,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $invoice->getStoreId()
            )
        ) {
            $this->contentAttacher->addPdf(
                $this->pdfRenderer->getPdfAsString([$invoice]),
                $this->pdfRenderer->getFileName(__('Invoice') . $invoice->getIncrementId()),
                $observer->getAttachmentContainer()
            );
        }

        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_AGREEMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $invoice->getStoreId()
        )
        ) {
            $this->attachTermsAndConditions($invoice->getStoreId(), $observer->getAttachmentContainer());
        }
    }
}
