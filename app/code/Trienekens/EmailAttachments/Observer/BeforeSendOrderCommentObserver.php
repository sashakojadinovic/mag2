<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class BeforeSendOrderCommentObserver extends AbstractSendOrderObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/order_comment/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/order_comment/attachagreement';
}
