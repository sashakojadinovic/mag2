<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Plugin;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class TransportBuilder
{

    private $nextEmail;

    public function __construct(
        \Trienekens\EmailAttachments\Model\NextEmailInfo $nextEmailInfo
    ) {
        $this->nextEmail = $nextEmailInfo;
    }

    public function beforeSetTemplateIdentifier(
        \Magento\Framework\Mail\Template\TransportBuilder $subject,
        $templateIdentifier
    ) {
        $this->nextEmail->setTemplateIdentifier($templateIdentifier);
    }

    public function beforeSetTemplateVars(
        \Magento\Framework\Mail\Template\TransportBuilder $subject,
        $templateVars
    ) {
        $this->nextEmail->setTemplateVars($templateVars);
    }

    public function aroundGetTransport(
        \Magento\Framework\Mail\Template\TransportBuilder $subject,
        \Closure $proceed
    ) {
        $mailTransport = $proceed();
        $this->reset();
        return $mailTransport;
    }

    private function reset()
    {
        $this->nextEmail->setTemplateIdentifier(null);
        $this->nextEmail->setTemplateVars(null);
    }
}
