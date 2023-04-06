<?php
/**
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_OrderProcess
 */

namespace Trienekens\OrderProcess\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Order Process Class
 * @package Trienekens\OrderProcess\Helper
 */
class Data extends AbstractHelper
{

    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;

    const EXPORT_FOLDER = __DIR__ . '/Export/';
    const TEMPLATE_FOLDER = __DIR__ . '/Templates/';
    const FILE_PREFIX = 'SH';

    const BUYER_PARTY_ID_CONFIG_PATH = 'trienekens_orderprocess/general/buyer_party_id';
    const SUPPLIER_PARTY_ID_CONFIG_PATH = 'trienekens_orderprocess/general/supplier_party_id';
    const ORDER_UNIT_CONFIG_PATH = 'trienekens_orderprocess/general/order_unit';
    const LIVE_MODE_CONFIG_PATH = 'trienekens_orderprocess/general/live_mode';

    const FTP_HOST_CONFIG_PATH = 'trienekens_orderprocess/ftp/host';
    const FTP_USERNAME_CONFIG_PATH = 'trienekens_orderprocess/ftp/username';
    const FTP_PASSWORD_CONFIG_PATH = 'trienekens_orderprocess/ftp/password';
    const FTP_UPLOAD_FOLDER_CONFIG_PATH = 'trienekens_orderprocess/ftp/upload_folder';

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var EncryptorInterface
     */
    protected $_encrypt;

    /**
     * @param Context $context
     * @param TimezoneInterface $timezone
     * @param OrderRepositoryInterface $orderRepository
     * @param EncryptorInterface $encrypt
     */
    public function __construct(
        Context $context,
        TimezoneInterface $timezone,
        OrderRepositoryInterface $orderRepository,
        EncryptorInterface $encrypt
    ) {
        $this->timezone = $timezone;
        $this->orderRepository = $orderRepository;
        $this->_encrypt = $encrypt;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    protected function getBuyerPartyId()
    {
        return $this->scopeConfig->getValue(
            self::BUYER_PARTY_ID_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    protected function getSupplierPartyId()
    {
        return $this->scopeConfig->getValue(
            self::SUPPLIER_PARTY_ID_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    protected function getOrderUnit()
    {
        return $this->scopeConfig->getValue(
            self::ORDER_UNIT_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    protected function getFtpHost()
    {
        return $this->scopeConfig->getValue(
            self::FTP_HOST_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    protected function getFtpUsername()
    {
        return $this->scopeConfig->getValue(
            self::FTP_USERNAME_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    protected function getFtpPassword()
    {
        $hash = $this->scopeConfig->getValue(
            self::FTP_PASSWORD_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $this->_encrypt->decrypt($hash);
    }

    /**
     * @return mixed
     */
    protected function getFtpUploadPath()
    {
        return $this->scopeConfig->getValue(
            self::FTP_UPLOAD_FOLDER_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    protected function getLiveMode()
    {
        return $this->scopeConfig->getValue(
            self::LIVE_MODE_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $orderId
     * @param $exportRule
     */
    public function generateFile($orderId, $exportRule)
    {
        $base = file_get_contents(self::TEMPLATE_FOLDER . 'base.xml');

        $order = $this->getOrder($orderId);

        $output = $this->addOrderDetails($order, $base);
        $output = $this->addShippingAddress($order, $output);
        $output = $this->addOrderItems($order, $output, $exportRule);

        file_put_contents(self::EXPORT_FOLDER . self::FILE_PREFIX . $order->getIncrementId() . '.xml', $output);
    }

    /**
     * @param $id
     * @return OrderInterface
     */
    public function getOrder($id)
    {
        return $this->orderRepository->get($id);
    }

    /**
     * @param OrderInterface $order
     * @param string $output
     * @return string
     */
    protected function addOrderDetails(OrderInterface $order, string $output): string
    {
        try {
            $createdAt = $this->timezone->date(new \DateTime($order->getCreatedAt()))->format('Y-m-d');
        } catch (\Exception $e) {
            $createdAt = $order->getCreatedAt();
        }

        $output = str_replace('[ORDER_ID]', self::FILE_PREFIX . $order->getIncrementId(), $output);
        $output = str_replace('[ORDER_NAME]', $order->getIncrementId(), $output);
        $output = str_replace('[ORDER_DATE]', $createdAt, $output);
        $output = str_replace('[BUYER_PARTY_ID]', $this->getBuyerPartyId(), $output);
        $output = str_replace('[SUPPLIER_PARTY_ID]', $this->getSupplierPartyId(), $output);

        return $output;
    }

    /**
     * @param OrderInterface $order
     * @param string $output
     * @return string
     */
    protected function addShippingAddress(OrderInterface $order, string $output): string
    {
        $shippingAddress = $order->getShippingAddress();
        $street = $shippingAddress->getStreetLine(1) . ' ' . $shippingAddress->getStreetLine(2);

        $output = str_replace('[SHIPPING_NAME]', $shippingAddress->getName(), $output);
        $output = str_replace('[COMPANY_NAME]', $shippingAddress->getCompany(), $output);
        $output = str_replace('[SHIPPING_STREET]', $street, $output);
        $output = str_replace('[SHIPPING_ZIP_CODE]', $shippingAddress->getPostcode(), $output);
        $output = str_replace('[SHIPPING_CITY]', $shippingAddress->getCity(), $output);
        $output = str_replace('[SHIPPING_COUNTRY]', $shippingAddress->getCountryId(), $output);

        return $output;
    }

    /**
     * @param OrderInterface $order
     * @param string $output
     * @param bool $exportRule
     * @return string
     */
    protected function addOrderItems(OrderInterface $order, string $output, $exportRule = true): string
    {
        $orderItemsOutput = '';
        $outputTemplate = file_get_contents(self::TEMPLATE_FOLDER . 'order-item.xml');

        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() == 'configurable') continue; // check only simple product
            $itemOutput = $outputTemplate;
            $itemOutput = str_replace('[EAN]', $item->getSku(), $itemOutput);
            $itemOutput = str_replace('[QUANTITY]', (int) $item->getQtyOrdered(), $itemOutput);
            $itemOutput = str_replace('[ORDER_UNIT]', $this->getOrderUnit(), $itemOutput);

            $orderItemsOutput .= $itemOutput;
        }

        if ($order->getGrandTotal() > 99 && $exportRule) {
            $itemOutput = $outputTemplate;
            $itemOutput = str_replace('[EAN]', '8720299521704', $itemOutput);
            $itemOutput = str_replace('[QUANTITY]', 1, $itemOutput);
            $itemOutput = str_replace('[ORDER_UNIT]', $this->getOrderUnit(), $itemOutput);

            $orderItemsOutput .= $itemOutput;
        }

        return str_replace('[ORDER_ITEMS]', $orderItemsOutput, $output);
    }

    /*
     * NOTE: THIS IS UNTESTED, USE WITH CAUTION!
     * All uploads are processed each minute so test uploads may cause orders to be placed in Germany!
     */
    /**
     * @param $orderId
     * @return bool
     */
    public function uploadOrder($orderId)
    {
        try {
            $order = $this->getOrder($orderId);
            $incrementId = $order->getIncrementId();
            $file = self::EXPORT_FOLDER . self::FILE_PREFIX . $incrementId . '.xml';
            $ftp_conn = ftp_connect($this->getFtpHost()) or die("Could not connect to " . $this->getFtpHost());
            //ftp_login($ftp_conn, 'M0000531280', '8oN=Q77Zop'); // TODO: MOVE THIS TO AN .ENV FILE!
            ftp_login($ftp_conn, $this->getFtpUsername(),  $this->getFtpPassword());
            ftp_pasv($ftp_conn, true);

            // If live mode is disabled
            if ($this->getLiveMode() == 1) {
                ftp_put($ftp_conn, $this->getFtpUploadPath() . self::FILE_PREFIX . $incrementId . '.xml', $file, FTP_ASCII);
            }
            ftp_close($ftp_conn);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}