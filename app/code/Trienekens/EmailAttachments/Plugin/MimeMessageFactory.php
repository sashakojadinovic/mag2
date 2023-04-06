<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Plugin;

use Trienekens\EmailAttachments\Model\Api\MailProcessorInterface;
use Trienekens\EmailAttachments\Model\Api\AttachmentContainerInterface;
use Trienekens\EmailAttachments\Model\AttachmentContainerFactory;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class MimeMessageFactory
{

    /**
     * @var \Trienekens\EmailAttachments\Model\EmailEventDispatcher
     */
    private $emailEventDispatcher;

    /**
     * @var AttachmentContainerFactory
     */
    private $attachmentContainerFactory;

    /**
     * @var MailProcessorInterface
     */
    private $mailProcessor;

    public function __construct(
        \Trienekens\EmailAttachments\Model\EmailEventDispatcher $emailEventDispatcher,
        AttachmentContainerFactory $attachmentContainer,
        MailProcessorInterface $mailProcessor
    ) {
        $this->emailEventDispatcher = $emailEventDispatcher;
        $this->attachmentContainerFactory = $attachmentContainer;
        $this->mailProcessor = $mailProcessor;
    }

    public function aroundCreate(
        \Magento\Framework\Mail\MimeMessageInterfaceFactory $subject,
        \Closure $proceed,
        array $data = []
    ) {
        if (isset($data['parts'])) {
            $attachmentContainer = $this->attachmentContainerFactory->create();
            $this->emailEventDispatcher->dispatch($attachmentContainer);
            $data['parts'] = $this->attachIfNeeded($data['parts'], $attachmentContainer);
        }
        return $proceed($data);
    }

    public function attachIfNeeded($existingParts, AttachmentContainerInterface $attachmentContainer)
    {
        if (!$attachmentContainer->hasAttachments()) {
            return $existingParts;
        }
        return $this->mailProcessor->createMultipartMessage($existingParts, $attachmentContainer);
    }
}
