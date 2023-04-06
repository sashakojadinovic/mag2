<?php
/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package    Trienekens_EmailAttachments
 */

namespace Trienekens\EmailAttachments\Model\Rewrite\Order\Pdf\Items\Invoice;

use Magento\Sales\Model\Order\Pdf\Items\Invoice\DefaultInvoice as CoreDefaultInvoice;

class DefaultInvoice extends CoreDefaultInvoice
{

    const FIRST_COLUMN_X = 35;
    const SECOND_COLUMN_X = 400;
    const THIRD_COLUMN_X = 480;

    /**
     * Draw item line
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function draw()
    {
        $order = $this->getOrder();
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];

        // draw Product name
        $lines[0] = [
            [
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                'text' => $this->string->split(html_entity_decode($item->getName()), 120, true, true),
                'feed' => self::FIRST_COLUMN_X
            ]
        ];

        // draw SKU
        $lines[1][] = [
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            'text' => $this->string->split('EAN: ' . html_entity_decode($this->getSku($item)), 120),
            'feed' => self::FIRST_COLUMN_X
        ];

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => self::SECOND_COLUMN_X, 'align' => 'left'];

        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
        $feedPrice = self::THIRD_COLUMN_X;
        foreach ($prices as $priceData) {
            if (isset($priceData['label'])) {
                // draw Price label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'left'];
                $i++;
            }
            // draw Price
            $lines[$i][] = [
                'text' => $priceData['price'],
                'feed' => $feedPrice,
                'font' => 'bold',
                'align' => 'left',
            ];
            $i++;
        }

        // custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => self::FIRST_COLUMN_X,
                ];

                // Checking whether option value is not null
                if ($option['value'] !== null) {
                    if (isset($option['print_value'])) {
                        $printValue = $option['print_value'];
                    } else {
                        $printValue = $this->filterManager->stripTags($option['value']);
                    }
                    $values = explode(', ', $printValue);
                    foreach ($values as $value) {
                        $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                    }
                }
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $this->setPage($page);
    }
}