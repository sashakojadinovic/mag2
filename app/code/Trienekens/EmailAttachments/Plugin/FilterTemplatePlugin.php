<?php

namespace Trienekens\EmailAttachments\Plugin;

class FilterTemplatePlugin
{
    /**
     * @param \Magento\Framework\Filter\Template $subject
     * @param $result
     * @return bool
     */
    public function afterIsStrictMode(
        \Magento\Framework\Filter\Template $subject,
                                           $result
    ) {
        return false;
    }
}
