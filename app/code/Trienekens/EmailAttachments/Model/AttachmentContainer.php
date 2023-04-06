<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class AttachmentContainer implements Api\AttachmentContainerInterface
{
    private $attachments = [];

    /**
     * @return bool
     */
    public function hasAttachments()
    {
        return !empty($this->attachments);
    }

    /**
     * @param Api\AttachmentInterface $attachment
     */
    public function addAttachment(Api\AttachmentInterface $attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return void
     */
    public function resetAttachments()
    {
        $this->attachments = [];
    }
}
