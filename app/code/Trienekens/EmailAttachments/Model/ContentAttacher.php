<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

use Trienekens\EmailAttachments\Model\Api\AttachmentContainerInterface as ContainerInterface;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class ContentAttacher
{
    const MIME_PDF = 'application/pdf';
    const TYPE_OCTETSTREAM = 'application/octet-stream';
    const MIME_TXT = 'text/plain';
    const MIME_HTML = 'text/html';

    private $attachmentFactory;

    public function __construct(
        AttachmentFactory $attachmentFactory
    ) {
        $this->attachmentFactory = $attachmentFactory;
    }

    public function addGeneric($content, $filename, $mimeType, ContainerInterface $attachmentContainer)
    {
        $attachment = $this->attachmentFactory->create(
            [
                'content' => $content,
                'mimeType' => $mimeType,
                'fileName' => $filename
            ]
        );
        $attachmentContainer->addAttachment($attachment);
    }

    public function addPdf($pdfString, $pdfFilename, ContainerInterface $attachmentContainer)
    {
        $this->addGeneric($pdfString, $pdfFilename, self::MIME_PDF, $attachmentContainer);
    }

    public function addText($text, $filename, ContainerInterface $attachmentContainer)
    {
        $this->addGeneric($text, $filename, self::MIME_TXT, $attachmentContainer);
    }

    public function addHtml($html, $filename, ContainerInterface $attachmentContainer)
    {
        $this->addGeneric($html, $filename, self::MIME_HTML, $attachmentContainer);
    }
}
