<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class BeforeSendInvoiceCommentObserver extends AbstractSendInvoiceObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/invoice_comment/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/invoice_comment/attachagreement';
}
