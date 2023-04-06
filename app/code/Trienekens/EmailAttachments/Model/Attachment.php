<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class Attachment implements Api\AttachmentInterface
{
    private $content;
    private $mimeType;
    private $filename;
    private $disposition;
    private $encoding;

    /**
     * @param string $content
     * @param string $mimeType
     * @param string $fileName
     * @param string $disposition
     * @param string $encoding
     */
    public function __construct(
        $content,
        $mimeType,
        $fileName,
        $disposition = Api\AttachmentInterface::DISPOSITION_ATTACHMENT,
        $encoding = Api\AttachmentInterface::ENCODING_BASE64
    ) {
        $this->content = $content;
        $this->mimeType = $mimeType;
        $this->filename = $fileName;
        $this->disposition = $disposition;
        $this->encoding = $encoding;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param bool $encoded
     *
     * @return mixed
     */
    public function getFilename($encoded = false)
    {
        if ($encoded) {
            return sprintf('=?utf-8?B?%s?=', base64_encode($this->filename));
        }
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
}
