<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class PdfRenderer implements Api\PdfRendererInterface
{
    protected $pdfRenderer;

    public function __construct(
        \Magento\Sales\Model\Order\Pdf\AbstractPdf $pdfRenderer
    ) {
        $this->pdfRenderer = $pdfRenderer;
    }

    public function getPdfAsString(array $salesObject)
    {
        return $this->pdfRenderer->getPdf($salesObject)->render();
    }

    public function getFileName($input = '')
    {
        return sprintf('%s.pdf', $input);
    }

    public function canRender()
    {
        return true;
    }
}
