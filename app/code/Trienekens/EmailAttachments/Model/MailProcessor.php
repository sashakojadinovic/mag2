<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

use Magento\Framework\Mail\MimePartInterfaceFactory;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class MailProcessor implements Api\MailProcessorInterface
{
    /**
     * @var MimePartInterfaceFactory
     */
    private $mimePartInterfaceFactory;

    public function __construct(
        MimePartInterfaceFactory $mimePartInterfaceFactory
    ) {
        $this->mimePartInterfaceFactory = $mimePartInterfaceFactory;
    }

    public function createMultipartMessage(
        array $existingParts,
        Api\AttachmentContainerInterface $attachmentContainer
    ) {

        foreach ($attachmentContainer->getAttachments() as $attachment) {
            $mimePart = $this->mimePartInterfaceFactory->create(
                [
                    'content' => $attachment->getContent(),
                    'fileName' => $attachment->getFilename(true),
                    'type' => $attachment->getMimeType(),
                    'encoding' => $attachment->getEncoding(),
                    'disposition' => $attachment->getDisposition()
                ]
            );

            $existingParts[] = $mimePart;
        }

        return $existingParts;
    }
}
