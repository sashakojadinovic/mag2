<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class NoneRenderer implements Api\PdfRendererInterface
{

    public function getPdfAsString(array $salesObject)
    {
        return '';
    }

    public function getFileName($input = '')
    {
        return sprintf('%s.pdf', $input);
    }

    public function canRender()
    {
        return false;
    }
}
