<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

use Trienekens\EmailAttachments\Model\Api\MailProcessorInterface;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class EmailEventDispatcher
{
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @var NextEmailInfo
     */
    private $nextEmailInfo;

    /**
     * @var EmailIdentifier
     */
    private $emailIdentifier;

    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager,
        NextEmailInfo $nextEmailInfo,
        EmailIdentifier $emailIdentifier
    ) {
        $this->eventManager = $eventManager;
        $this->nextEmailInfo = $nextEmailInfo;
        $this->emailIdentifier = $emailIdentifier;
    }

    public function dispatch(Api\AttachmentContainerInterface $attachmentContainer)
    {
        if ($this->nextEmailInfo->getTemplateIdentifier()) {
            $this->determineEmailAndDispatch($attachmentContainer);
        }
    }

    public function determineEmailAndDispatch(Api\AttachmentContainerInterface $attachmentContainer)
    {
        $emailType = $this->emailIdentifier->getType($this->nextEmailInfo);
        if ($emailType->getType()) {
            $this->eventManager->dispatch(
                'trienekens_emailattachments_before_send_' . $emailType->getType(),
                [

                    'attachment_container' => $attachmentContainer,
                    $emailType->getVarCode() => $this->nextEmailInfo->getTemplateVars()[$emailType->getVarCode()]
                ]
            );
        }
    }
}