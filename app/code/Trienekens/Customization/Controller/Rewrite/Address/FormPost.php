<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_Customization
 */

namespace Trienekens\Customization\Controller\Rewrite\Address;

class FormPost extends \Magento\Customer\Controller\Address\FormPost
{

    /**
     * Extract address from request
     *
     * @return \Magento\Customer\Api\Data\AddressInterface
     * @throws \Exception
     */
    protected function _extractAddress()
    {
        $existingAddressData = $this->getExistingAddressData();

        /** @var \Magento\Customer\Model\Metadata\Form $addressForm */
        $addressForm = $this->_formFactory->create(
            'customer_address',
            'customer_address_edit',
            $existingAddressData
        );
        $addressData = $addressForm->extractData($this->getRequest());

        // Trienekens: clean vat id
        $countryId = $addressData['country_id'];
        if ( isset($addressData['vat_id'])
            && $addressData['vat_id']
            && ($countryId == 'NL' || $countryId == 'BE') ) {
            $vatId = preg_replace('/[^A-Za-z0-9\-]/', '', $addressData['vat_id']);
            if (substr($vatId, 0, 2) != $countryId) {
                $vatId = $countryId . $vatId;
                $addressData['vat_id'] = $vatId;
            }
        }
        // Trienekens: end

        $attributeValues = $addressForm->compactData($addressData);

        $this->updateRegionData($attributeValues);

        $addressDataObject = $this->addressDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $addressDataObject,
            array_merge($existingAddressData, $attributeValues),
            \Magento\Customer\Api\Data\AddressInterface::class
        );
        $addressDataObject->setCustomerId($this->_getSession()->getCustomerId())
            ->setIsDefaultBilling(
                $this->getRequest()->getParam(
                    'default_billing',
                    isset($existingAddressData['default_billing']) ? $existingAddressData['default_billing'] : false
                )
            )
            ->setIsDefaultShipping(
                $this->getRequest()->getParam(
                    'default_shipping',
                    isset($existingAddressData['default_shipping']) ? $existingAddressData['default_shipping'] : false
                )
            );

        return $addressDataObject;
    }
}