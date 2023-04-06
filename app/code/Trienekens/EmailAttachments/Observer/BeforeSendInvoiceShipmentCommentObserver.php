<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class BeforeSendInvoiceShipmentCommentObserver extends AbstractSendInvoiceShipmentObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/shipment_comment/attachinvoicepdf';
}
