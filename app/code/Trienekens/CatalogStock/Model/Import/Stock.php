<?php
/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_CatalogStock
 */
namespace Trienekens\CatalogStock\Model\Import;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class Stock extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{

    const SKU = 'sku';
    const QTY = 'qty';
    const MIN_SALE_QTY = 'min_sale_qty';
    const QTY_INCREMENTS = 'qty_increments';

    protected $_permanentAttributes = [self::SKU];
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    protected $stockRegistry;
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::SKU,
        self::MIN_SALE_QTY,
        self::QTY_INCREMENTS,
        self::QTY,
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;

    protected $_validators = [];

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->errorAggregator = $errorAggregator;
        $this->stockRegistry = $stockRegistry;
    }
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'trienekens_stock';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {

        $title = false;

        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;
        // BEHAVIOR_DELETE use specific validation logic
        // if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
        if (!isset($rowData[self::SKU]) || empty($rowData[self::SKU])) {
            $this->addRowError("SKU is empty", $rowNum);
            return false;
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }


    /**
     * Create Advanced price data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }
    /**
     * Save newsletter subscriber
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Replace newsletter subscriber
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Save and replace newsletter subscriber
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError("SKU is empty", $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowTitle= $rowData[self::SKU];
                $entityList[$rowTitle] = [
                    self::QTY => $rowData[self::QTY],
                    self::MIN_SALE_QTY => isset($rowData[self::MIN_SALE_QTY]) ? $rowData[self::MIN_SALE_QTY] : null,
                    self::QTY_INCREMENTS => isset($rowData[self::QTY_INCREMENTS]) ? $rowData[self::QTY_INCREMENTS] : null,
                ];
            }
            $this->saveEntityFinish($entityList);
        }
        return $this;
    }

    /**
     * Save product prices.
     *
     * @param array $entityData
     * @return $this
     */
    protected function saveEntityFinish(array $entityData)
    {
        if ($entityData) {
            foreach ($entityData as $sku => $entityRows) {
                $qty = $entityRows[self::QTY];
                $minQty = $entityRows[self::MIN_SALE_QTY];
                $incQty = $entityRows[self::QTY_INCREMENTS];
                try {

                    $stockItem = $this->stockRegistry->getStockItemBySku($sku);
                    // qty update
                    $stockItem->setData('product_online', 1);
                    $stockItem->setData('qty', $entityRows[self::QTY]);
                    $stockItem->setData('is_in_stock', ($qty == 0) ? 0 : 1);
                    $stockItem->setData('manage_stock', 1);
                    // sql_qty update
                    if (!is_null($minQty) && !is_null($incQty)) {
                        $stockItem->setData('use_config_min_sale_qty', 0);
                        $stockItem->setData('use_config_enable_qty_inc', 0);
                        $stockItem->setData('use_config_qty_increments', 0);
                        $stockItem->setData('min_sale_qty', $minQty);
                        $stockItem->setData('enable_qty_increments', 1);
                        $stockItem->setData('qty_increments', $incQty);
                    }
                    $this->stockRegistry->updateStockItemBySku($sku, $stockItem);
                    $this->countItemsUpdated = $this->countItemsUpdated + 1;
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }
        return $this;
    }
}