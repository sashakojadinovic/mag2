<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Observer;

use Trienekens\EmailAttachments\Model\Api\AttachmentContainerInterface as ContainerInterface;
use Trienekens\EmailAttachments\Model\ContentAttacher;
use Trienekens\EmailAttachments\Model\TermsAndConditionsAttacher;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
abstract class AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $scopeConfig;

    protected $pdfRenderer;

    protected $termsAttacher;

    protected $contentAttacher;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Trienekens\EmailAttachments\Model\Api\PdfRendererInterface $pdfRenderer,
        TermsAndConditionsAttacher $termsAttacher,
        ContentAttacher $contentAttacher
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->pdfRenderer = $pdfRenderer;
        $this->termsAttacher = $termsAttacher;
        $this->contentAttacher = $contentAttacher;
    }

    public function attachContent($content, $pdfFilename, $mimeType, ContainerInterface $attachmentContainer)
    {
        $this->contentAttacher->addGeneric($content, $pdfFilename, $mimeType, $attachmentContainer);
    }

    /**
     * @param string             $pdfString
     * @param string             $pdfFilename
     * @param ContainerInterface $attachmentContainer
     *
     * @deprecated see \Trienekens\EmailAttachments\Model\ContentAttacher::addPdf()
     */
    public function attachPdf($pdfString, $pdfFilename, ContainerInterface $attachmentContainer)
    {
        $this->contentAttacher->addPdf($pdfString, $pdfFilename, $attachmentContainer);
    }

    /**
     * @param string             $text
     * @param string             $filename
     * @param ContainerInterface $attachmentContainer
     *
     * @deprecated see \Trienekens\EmailAttachments\Model\ContentAttacher::addText()
     */
    public function attachTxt($text, $filename, ContainerInterface $attachmentContainer)
    {
        $this->contentAttacher->addText($text, $filename, $attachmentContainer);
    }

    /**
     * @param string             $html
     * @param string             $filename
     * @param ContainerInterface $attachmentContainer
     *
     * @deprecated see \Trienekens\EmailAttachments\Model\ContentAttacher::addHtml()
     */
    public function attachHtml($html, $filename, ContainerInterface $attachmentContainer)
    {
        $this->contentAttacher->addHtml($html, $filename, $attachmentContainer);
    }

    public function attachTermsAndConditions($storeId, ContainerInterface $attachmentContainer)
    {
        $this->termsAttacher->attachForStore($storeId, $attachmentContainer);
    }
}
