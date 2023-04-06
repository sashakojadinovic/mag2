<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class AbstractSendOrderObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/order/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/order/attachagreement';

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * @var \Magento\Sales\Api\Data\OrderInterface $order
         */
        $order = $observer->getOrder();
        if ($this->pdfRenderer->canRender()
            && $this->scopeConfig->getValue(
                static::XML_PATH_ATTACH_PDF,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $order->getStoreId()
            )
        ) {
//            $this->contentAttacher->addPdf(
//                $this->pdfRenderer->getPdfAsString([$order]),
//                $this->pdfRenderer->getFileName(__('Order') . $order->getIncrementId()),
//                $observer->getAttachmentContainer()
//            );
            foreach ($order->getInvoiceCollection() as $invoice) {
                $this->contentAttacher->addPdf(
                    $this->pdfRenderer->getPdfAsString([$invoice]),
                    $this->pdfRenderer->getFileName(__('Invoice') . $invoice->getIncrementId()),
                    $observer->getAttachmentContainer()
                );
            }
        }

        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_AGREEMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $order->getStoreId()
        )
        ) {
            $this->attachTermsAndConditions($order->getStoreId(), $observer->getAttachmentContainer());
        }
    }
}
