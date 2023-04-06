<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class NextEmailInfo
{
    private $templateVars;
    private $templateIdentifier;

    public function setTemplateVars($templateVars)
    {
        $this->templateVars = $templateVars;
    }

    public function getTemplateVars()
    {
        return $this->templateVars;
    }

    public function setTemplateIdentifier($templateIdentifier)
    {
        $this->templateIdentifier = $templateIdentifier;
    }

    public function getTemplateIdentifier()
    {
        return $this->templateIdentifier;
    }
}
