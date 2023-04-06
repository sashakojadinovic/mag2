<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */

namespace Trienekens\OrderProcess\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Export extends Column
{
    const URL_PATH_EXPORT = 'trienekens_orderprocess/orderprocess/export';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;

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
                    if ($item['process_status'] != 1) {
                        $item[$this->getData('name')] = '';
                        $url = $this->urlBuilder->getUrl(
                            static::URL_PATH_EXPORT,
                            [
                                'order_id' => $item['entity_id'],
                                'export_rule' => 1,
                            ]
                        );;
                        $html = '<a class="action-menu-item export-link" target="_self" href="' . $url . '">Export</a>';
                        $item[$this->getData('name')] = html_entity_decode($html);
                    }
                }
            }
        }

        return $dataSource;
    }
}
