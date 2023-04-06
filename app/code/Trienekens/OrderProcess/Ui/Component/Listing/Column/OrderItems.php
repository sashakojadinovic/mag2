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

class OrderItems extends Column
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

    protected $orderFactory;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->_request = $request;
        $this->orderFactory = $orderFactory;

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
                    $item[$this->getData('name')] = '';
                    $orderId = $item['entity_id'];
                    /** @var $order \Magento\Sales\Model\Order */
                    $order = $this->orderFactory->create()->load($orderId);
                    $html = '<table class="data-grid data-grid-draggable order-process-product-grid">
                               <thead>
                                  <tr>
                                     <th class="data-grid-th">
                                        <span class="data-grid-cell-content">Name</span>
                                     </th>
                                     <th class="data-grid-th">
                                        <span class="data-grid-cell-content">SKU</span>
                                     </th>
                                     <th class="data-grid-th">
                                        <span class="data-grid-cell-content">QTY</span>
                                     </th>
                                  </tr>
                               </thead>
                               <tbody>';
                    $cnt = 0;
                    foreach ($order->getAllItems() as $product) {
                        if ($product->getProductType() == 'configurable') continue; // check only simple product
                        if ($cnt % 2 == 0) {
                            $oddClass = '';
                        } else {
                            $oddClass = '_odd-row';
                        }
                        $html .= '<tr class="data-row ' . $oddClass . '">
                                     <td>
                                        <div class="data-grid-cell-content">' . $product->getName() . '</div>
                                     </td>
                                     <td>
                                        <div class="data-grid-cell-content">' . $product->getSku() . '</div>
                                     </td>
                                     <td>
                                        <div class="data-grid-cell-content">' . (int) $product->getQtyOrdered() . '</div>
                                     </td>
                                  </tr>';
                        $cnt++;
                    }

                    if ($order->getGrandTotal() > 99) {
                        if ($cnt % 2 == 0) {
                            $oddClass = '';
                        } else {
                            $oddClass = '_odd-row';
                        }
                        $html .= '<tr class="' . $oddClass . '">
                                     <td>
                                        <div class="data-grid-cell-content" style="text-align: right">
                                            <div class="admin__field admin__field-option" style="padding-top: 0">
                                                <input name="export_rule" type="checkbox" id="export_rule_'. $orderId . '" class="admin__control-checkbox export_rule" checked />
                                                <label class="admin__field-label" for="export_rule_'. $orderId . '">Branding</label>
                                            </div>
                                        </div>
                                     </td>
                                     <td>
                                        <div class="data-grid-cell-content">8720299521704</div>
                                     </td>
                                     <td>
                                        <div class="data-grid-cell-content">1</div>
                                     </td>
                                  </tr>';
                    }
                    $html .= '</tbody>
                            </table>';
                    $item[$this->getData('name')] = html_entity_decode($html);
                }
            }
        }

        return $dataSource;
    }
}
