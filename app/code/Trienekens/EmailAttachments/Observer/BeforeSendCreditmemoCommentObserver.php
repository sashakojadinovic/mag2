<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class BeforeSendCreditmemoCommentObserver extends AbstractSendCreditmemoObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/creditmemo_comment/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/creditmemo_comment/attachagreement';
}
