<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class EmailType
{
    private $type;
    private $varCode;

    public function __construct(
        $type,
        $varCode
    ) {
        $this->type = $type;
        $this->varCode = $varCode;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getVarCode()
    {
        return $this->varCode;
    }
}
