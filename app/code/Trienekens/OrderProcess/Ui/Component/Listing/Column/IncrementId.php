<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */

namespace Trienekens\OrderProcess\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\App\RequestInterface;

class IncrementId extends Column
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->_request = $request;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if ( isset($dataSource['data']['items']) ) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $url = $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $item['entity_id']]);
                    $item[$this->getData('name')] = html_entity_decode('<a href="' . $url . '" target="_blank">' . $item['increment_id'] . '</a>');
                }
            }
        }

        return $dataSource;
    }
}
