<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model\Api;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
interface PdfRendererInterface
{
    public function getPdfAsString(array $salesObjects);

    public function getFileName($input = '');

    public function canRender();
}
