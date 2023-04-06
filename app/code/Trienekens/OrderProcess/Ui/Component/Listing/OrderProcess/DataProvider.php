<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */

namespace Trienekens\OrderProcess\Ui\Component\Listing\OrderProcess;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollection;

/**
 * Custom DataProvider for order process listing
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var  $__flag,
     */
    protected $__flag = false;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param OrderCollection $collection
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        OrderCollection $collection,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection->create();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'items' => array_values($this->collection->getData()),
            'totalRecords' => $this->collection->getSize(),
        ];
        return $data;
    }

    /**
     *
     * get collection()
     */
    public function getCollection()
    {
        // Get events_entity collection
        if (!$this->collection->isLoaded() && !$this->__flag) {
            $this->collection->load();
            // Get order_process joined data
            $this->collection->getSelect()->joinLeft(
                ['order_process' => $this->collection->getTable('trienekens_orderprocess_orderprocess')],
                'main_table.entity_id = order_process.order_id',
                [
                    'order_key',
                    'process_status',
                    'process_at'
                ]
            );
            // filter deleted orders from order listing
            $this->collection->addFieldToFilter(
                ['order_process.process_status', 'order_process.process_status'],
                [
                    ['neq' => -1],
                    ['null' => true],
                ]
            );

        }

        $this->__flag = true;

        return $this->collection;
    }

}
