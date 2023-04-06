<?php
declare(strict_types=1);

namespace Trienekens\EmailAttachments\Model;

use Trienekens\EmailAttachments\Model\Api\AttachmentContainerInterface as ContainerInterface;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;

/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */
class TermsAndConditionsAttacher
{
    private $termsCollection;

    private $contentAttacher;

    public function __construct(
        \Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory $termsCollection,
        ContentAttacher $contentAttacher
    ) {
        $this->termsCollection = $termsCollection;
        $this->contentAttacher = $contentAttacher;
    }

    public function attachForStore($storeId, ContainerInterface $attachmentContainer)
    {
        /**
         * @var \Magento\CheckoutAgreements\Model\ResourceModel\Agreement\Collection $agreements
         */
        $agreements = $this->termsCollection->create();
        $agreements->addStoreFilter($storeId)->addFieldToFilter('is_active', 1);

        foreach ($agreements as $agreement) {
            $this->attachAgreement($agreement, $attachmentContainer);
        }
    }

    public function attachAgreement(AgreementInterface $agreement, ContainerInterface $attachmentContainer)
    {
        if ($agreement->getIsHtml()) {
            $this->contentAttacher->addHtml(
                $this->buildHtmlAgreement($agreement),
                $agreement->getName() . '.html',
                $attachmentContainer
            );
        } else {
            $this->contentAttacher->addText(
                $agreement->getContent(),
                $agreement->getName() . '.txt',
                $attachmentContainer
            );
        }
    }

    private function buildHtmlAgreement(AgreementInterface $agreement)
    {
        return sprintf(
            '<html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <title>%s</title>
                </head>
                <body>%s</body>
            </html>',
            $agreement->getName(),
            $agreement->getContent()
        );
    }
}
