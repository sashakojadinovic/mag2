<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model\Api;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
interface AttachmentInterface
{
    const ENCODING_BASE64          = 'base64';
    const DISPOSITION_ATTACHMENT   = 'attachment';

    public function getMimeType();

    public function getFilename($encoded = false);

    public function getDisposition();

    public function getEncoding();

    public function getContent();
}
