<?php
/**
 * Copyright © 2021 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package    Trienekens_EmailAttachments
 */

namespace Trienekens\EmailAttachments\Model\Rewrite\Order\Pdf;

use Magento\Sales\Model\Order\Pdf\Invoice as CoreInvoice;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection;

class Invoice extends CoreInvoice
{
    const FIRST_COLUMN_X = 35;
    const SECOND_COLUMN_X = 400;
    const THIRD_COLUMN_X = 480;
    
    /**
     * Set font as regular
     *
     * @param \Zend_Pdf_Page $object
     * @param int $size
     * @return \Zend_Pdf_Resource_Font
     * @throws \Zend_Pdf_Exception
     */
    protected function _setFontRegular($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('app/code/Trienekens/EmailAttachments/view/web/fonts/Roboto/Roboto-Regular.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Set font as bold
     *
     * @param \Zend_Pdf_Page $object
     * @param int $size
     * @return \Zend_Pdf_Resource_Font
     * @throws \Zend_Pdf_Exception
     */
    protected function _setFontBold($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('app/code/Trienekens/EmailAttachments/view/web/fonts/Roboto/Roboto-Bold.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Set font as italic
     *
     * @param \Zend_Pdf_Page $object
     * @param int $size
     * @return \Zend_Pdf_Resource_Font
     * @throws \Zend_Pdf_Exception
     */
    protected function _setFontItalic($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('app/code/Trienekens/EmailAttachments/view/web/fonts/Roboto/Roboto-Italic.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Return PDF document
     *
     * @param array|Collection $invoices
     * @return \Zend_Pdf
     * @throws \Zend_Pdf_Exception
     */
    public function getPdf($invoices = [])
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                $this->_localeResolver->emulate($invoice->getStoreId());
                $this->_storeManager->setCurrentStore($invoice->getStoreId());
            }
            $page = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );
            /* Add document text and number */
            $this->insertDocumentNumber($page, $invoice);
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($invoice->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId()) {
                $this->_localeResolver->revert();
            }
            /* Add notification for purchase order */
            if ($order->getPayment()->getMethodInstance()->getCode() == 'purchaseorder') {
                $this->_insertNotification($page);
            }
            /* Add Footer */
            $this->_insertFooter($page);
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    /**
     * Draw header for item table
     *
     * @param \Zend_Pdf_Page $page
     * @return void
     */
    protected function _drawHeader(\Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 9);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(1));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 25);
        $this->y -= 15;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(1, 1, 1));

        //columns headers
        $lines[0][] = ['text' => __('Product'), 'feed' => self::FIRST_COLUMN_X, 'font' => 'bold'];

        $lines[0][] = ['text' => __('Hoeveelheid'), 'feed' => self::SECOND_COLUMN_X, 'font' => 'bold', 'align' => 'left'];

        $lines[0][] = ['text' => __('Prijs'), 'feed' => self::THIRD_COLUMN_X, 'font' => 'bold', 'align' => 'left'];

        $lineBlock = ['lines' => $lines, 'height' => 5];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * Insert address to pdf page
     *
     * @param \Zend_Pdf_Page $page
     * @param string|null $store
     * @return void
     */
    protected function insertAddress(&$page, $store = null)
    {
        $this->y = $this->y ? $this->y : 815;
        $top = 815;

        /* Add store URL in bold */
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontBold($page, 10);
        $page->drawText(__('Superhandig.nl'), self::SECOND_COLUMN_X, $top - 10, 'UTF-8');

        /* Add address data */
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontRegular($page, 10);
        $page->setLineWidth(0);
        $top -= 30;
        $values = explode(
            "\n",
            $this->_scopeConfig->getValue(
                'sales/identity/address',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            )
        );
        foreach ($values as $value) {
            if ($value !== '') {
                $value = preg_replace('/<br[^>]*>/i', "\n", $value);
                foreach ($this->string->split($value, 45, true, true) as $_value) {
                    if (substr( $_value, 0, 2 ) === "I:") $top -= 10;
                    $page->drawText(
                        trim(strip_tags($_value)),
                        self::SECOND_COLUMN_X,
                        $top,
                        'UTF-8'
                    );
                    $top -= 12;
                }
            }
        }
        $this->y = $this->y > $top ? $top : $this->y;
    }

    /**
     * Insert order to pdf page.
     *
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order $obj
     * @param bool $putOrderId
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        if ($obj instanceof \Magento\Sales\Model\Order) {
            $shipment = null;
            $order = $obj;
        } elseif ($obj instanceof \Magento\Sales\Model\Order\Shipment) {
            $shipment = $obj;
            $order = $shipment->getOrder();
        }

        $this->y = $this->y ? $this->y : 815;

        $this->y -= 30;
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontBold($page, 16);
        $page->drawText(__('FACTUUR'), self::FIRST_COLUMN_X, $this->y - 15, 'UTF-8');

        /* Billing Address */
        $billingAddress = $this->_formatAddress($this->addressRenderer->format($order->getBillingAddress(), 'pdf'));

        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontRegular($page, 10);
        $this->y -= 40;
        $addressesStartY = $this->y;

        foreach ($billingAddress as $value) {
            if ($value !== '') {
                $text = [];
                foreach ($this->string->split($value, 45, true, true) as $_value) {
                    $text[] = $_value;
                }
                foreach ($text as $part) {
                    $page->drawText(strip_tags(ltrim($part)), self::FIRST_COLUMN_X, $this->y, 'UTF-8');
                    $this->y -= 15;
                }
            }
        }

        /* Add PO number if exist */
        $poNumber = $order->getPayment()->getPoNumber();
        if ($poNumber) {
            $page->drawText( "Referentie: " . $poNumber, self::FIRST_COLUMN_X, $this->y, 'UTF-8');
            $this->y -= 15;
        }

        /* Invoice / Order Information */
        $addressesEndY = $this->y;
        $this->y = $addressesStartY;
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->setDocHeaderCoordinates([25, $this->y, 570, $this->y - 55]);
        $this->_setFontRegular($page, 10);

        if ($putOrderId) {
            $page->drawText(__('Bestelnummer: ') . $order->getRealOrderId(), self::SECOND_COLUMN_X, $this->y -= 30, 'UTF-8');
            $this->y +=15;
        }

        $this->y -=30;
        $page->drawText(
            __('Besteldatum: ') .
            $this->_localeDate->formatDate(
                $order->getCreatedAt(),
                \IntlDateFormatter::MEDIUM,
                false
            ),
            self::SECOND_COLUMN_X,
            $this->y,
            'UTF-8'
        );

        $addressesEndY = min($addressesEndY, $this->y);
        $this->y = $addressesEndY;

        /* Add padding, init */
        $this->y -= 10;
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 15;
    }

    /**
     * Insert title and number for concrete document type
     *
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return void
     * @throws \Zend_Pdf_Exception
     */
    public function insertDocumentNumber(\Zend_Pdf_Page $page, $invoice)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontRegular($page, 10);
        $docHeader = $this->getDocHeaderCoordinates();
        $page->drawText(__('Factuurnummer: ') . $invoice->getIncrementId(), self::SECOND_COLUMN_X, $docHeader[1], 'UTF-8');

        $page->drawText(
            __('Factuurdatum: ') .
            $this->_localeDate->formatDate(
                $invoice->getCreatedAt(),
                \IntlDateFormatter::MEDIUM,
                false
            ),
            self::SECOND_COLUMN_X,
            $docHeader[1] - 15,
            'UTF-8'
        );
    }

    /**
     * Insert totals to pdf page
     *
     * @param  \Zend_Pdf_Page $page
     * @param  \Magento\Sales\Model\AbstractModel $source
     * @return \Zend_Pdf_Page
     */
    protected function insertTotals($page, $source)
    {
        $order = $source->getOrder();
        $totals = $this->_getTotalsList();
        $lineBlock = ['lines' => [], 'height' => 15];
        foreach ($totals as $total) {
            $total->setOrder($order)->setSource($source);

            if ($total->canDisplay()) {
                $total->setFontSize(10);
                foreach ($total->getTotalsForDisplay() as $totalData) {
                    $transLabel = $totalData['label'];
                    switch ($transLabel) {
                        case 'Subtotal:':
                            $transLabel = 'Subtotaal:';
                            break;
                        case 'Tax:':
                            $transLabel = 'BTW:';
                            break;
                        case 'Shipping & Handling:':
                            $transLabel = 'Verzendkosten:';
                            break;
                        case 'Grand Total:':
                            $transLabel = 'Totaal:';
                            break;
                        default:
                            break;
                    }
                    $lineBlock['lines'][] = [
                        [
                            'text' => $transLabel,
                            'feed' => 475,
                            'align' => 'right',
                            'font_size' => $totalData['font_size'],
                            'font' => 'bold',
                        ],
                        [
                            'text' => $totalData['amount'],
                            'feed' => 565,
                            'align' => 'right',
                            'font_size' => $totalData['font_size'],
                            'font' => 'bold'
                        ],
                    ];
                }
            }
        }

        /* Draw top line */
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.8));
        $page->setLineWidth(0.5);
        $page->drawLine(25, $this->y, 570, $this->y);
        $this->y -= 20;
        /* Draw start line */
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->setLineWidth(1);
        $page->drawLine(self::SECOND_COLUMN_X, $this->y, 570, $this->y);
        $this->y -= 20;
        /* Draw total data */
        $page = $this->drawLineBlocks($page, [$lineBlock]);
        /* Draw end line */
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->setLineWidth(1);
        $page->drawLine(self::SECOND_COLUMN_X, $this->y, 570, $this->y);
        return $page;
    }

    /**
     * Insert notification for purchase order to pdf page
     *
     * @param $page
     * @throws \Zend_Pdf_Exception
     */
    protected function _insertNotification($page) {
        $this->_setFontBold($page, 10);
        $this->y = 100;
        $page->drawText('Gelieve het totaalbedrag binnen 14 dagen te voldoen op onze IBAN bankrekeningnummer: ', self::FIRST_COLUMN_X, $this->y, 'UTF-8');
        $page->drawText('NL30 RABO 0324 0491 61 t.n.v. EL BRUNO E-COMMERCE o.v.v. het factuurnummer.', self::FIRST_COLUMN_X, $this->y - 15, 'UTF-8');
        $this->_setFontRegular($page, 10);
    }

    /**
     * Insert footer to pdf page
     *
     * @param $page
     * @throws \Zend_Pdf_Exception
     */
    private function _insertFooter($page) {
        $this->_setFontRegular($page, 9);

        $this->y = 60;
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->setLineWidth(0.5);
        $page->drawLine(25, $this->y, 570, $this->y);
        $this->y -= 15;

        $page->drawText('Kijk voor onze meest recente voorwaarden op:', 90, $this->y, 'UTF-8');
        $page->drawText('https://www.superhandig.nl/algemene-voorwaarden/', 277, $this->y, 'UTF-8');
        $this->_setFontRegular($page, 10);
    }

}