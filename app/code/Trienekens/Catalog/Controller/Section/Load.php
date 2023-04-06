<?php
namespace Trienekens\Catalog\Controller\Section;

use Magento\Customer\CustomerData\Section\Identifier;
use Magento\Customer\CustomerData\SectionPoolInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Load extends \Magento\Customer\Controller\Section\Load
{
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $_objectManager
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Identifier $sectionIdentifier
     * @param SectionPoolInterface $sectionPool
     * @param \Magento\Framework\Escaper|null $escaper
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $_objectManager,
        Context $context, JsonFactory $resultJsonFactory, Identifier $sectionIdentifier, SectionPoolInterface $sectionPool, \Magento\Framework\Escaper $escaper = null)
    {
        $this->_objectManager = $_objectManager;
        $this->escaper = $escaper ?: $this->_objectManager->get(\Magento\Framework\Escaper::class);
        parent::__construct($context, $resultJsonFactory, $sectionIdentifier, $sectionPool, $escaper);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store', true);
        $resultJson->setHeader('Pragma', 'no-cache', true);
        try {
            $sectionNames = $this->getRequest()->getParam('sections');
            $sectionNames = $sectionNames ? array_unique(\explode(',', $sectionNames)) : null;

            $sectionNames[] = 'compare-products';

            $forceNewSectionTimestamp = $this->getRequest()->getParam('force_new_section_timestamp');
            if ('false' === $forceNewSectionTimestamp) {
                $forceNewSectionTimestamp = false;
            }
            $response = $this->sectionPool->getSectionsData($sectionNames, (bool)$forceNewSectionTimestamp);
        } catch (\Exception $e) {
            $resultJson->setStatusHeader(
                \Zend\Http\Response::STATUS_CODE_400,
                \Zend\Http\AbstractMessage::VERSION_11,
                'Bad Request'
            );
            $response = ['message' => $this->escaper->escapeHtml($e->getMessage())];
        }

        return $resultJson->setData($response);
    }
}
