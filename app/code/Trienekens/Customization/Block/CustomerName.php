<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_Customization
 */

namespace Trienekens\Customization\Block;

class CustomerName extends \Magento\Framework\View\Element\Template {
    public $_coreRegistry;
    protected $_customerSession;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getCustomerName()
    {
        if ($this->_customerSession->isLoggedIn()) {
            try {
                return $this->_customerSession->getCustomer()->getName();  // get  Full Name
            } catch (\Exception $e) {
                return __('Inloggen / Registreren');
            }
        } else {
            return __('Inloggen / Registreren');
        }
    }
}
