<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model\Api;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
interface AttachmentContainerInterface
{
    /**
     * @return bool
     */
    public function hasAttachments();

    /**
     * @param AttachmentInterface $attachment
     */
    public function addAttachment(AttachmentInterface $attachment);

    /**
     * @return AttachmentInterface[]
     */
    public function getAttachments();

    /**
     * @return void
     */
    public function resetAttachments();
}
