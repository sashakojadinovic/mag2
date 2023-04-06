<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */
declare(strict_types=1);

namespace Trienekens\OrderProcess\Model\ResourceModel\OrderProcess;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'order_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'orderprocess_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'events_entity_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Trienekens\OrderProcess\Model\OrderProcess::class,
            \Trienekens\OrderProcess\Model\ResourceModel\OrderProcess::class
        );
    }
}

