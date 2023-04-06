<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Ui\Component\Listing\Column;

class OrderProcessActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    protected $urlBuilder;
    const URL_PATH_EXPORT = 'trienekens_orderprocess/orderprocess/export';
    const URL_PATH_DELETE = 'trienekens_orderprocess/orderprocess/delete';

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
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
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    if ($item['process_status'] != 1) {
                        $item[$this->getData('name')] = [
                            'export' => [
                                'href' => $this->urlBuilder->getUrl(
                                    static::URL_PATH_EXPORT,
                                    [
                                        'order_id' => $item['entity_id'],
                                        'export_rule' => 1,
                                    ]
                                ),
                                'label' => __('Export'),
                                'confirm' => [
                                    'title' => __('Export "${ $.$data.increment_id }"'),
                                    'message' => __('Are you sure you wan\'t to export a "${ $.$data.increment_id }" order?')
                                ]
                            ]
                        ];
                    }
//                    $item[$this->getData('name')]['delete'] = [
//                        'href' => $this->urlBuilder->getUrl(
//                            static::URL_PATH_DELETE,
//                            [
//                                'order_id' => $item['entity_id']
//                            ]
//                        ),
//                        'label' => __('Delete'),
//                        'confirm' => [
//                            'title' => __('Delete "${ $.$data.increment_id }"'),
//                            'message' => __('Are you sure you wan\'t to delete a "${ $.$data.increment_id }" order?')
//                        ]
//                    ];
                }
            }
        }
        
        return $dataSource;
    }
}

