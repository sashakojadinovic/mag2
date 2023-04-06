define([
        'underscore',
        'uiElement',
        'Magento_Catalog/js/product/storage/storage-service',
        'jquery',
        'mage/storage',
        'mage/url',
    ], function (_, Element, storageService, jQuery, storage, url) {
        'use strict';

        /**
         * Gets ids from items
         *
         * @returns {Object}
         */
        return function (getIdentifiers) {
            return getIdentifiers.extend({
                getIdentifiers: function () {
                    var result = {},
                        productCurrentScope = this.data.productCurrentScope,
                        scopeId = productCurrentScope === 'store' ? window.checkout.storeId :
                            productCurrentScope === 'group' ? window.checkout.storeGroupId :
                                typeof websiteId !== 'undefined' ? window.checkout.websiteId :
                                    window.parent.checkout.websiteId;

                    _.each(this.data.items, function (item, key) {
                        result[productCurrentScope + '-' + scopeId + '-' + key] = {
                            'added_at': new Date().getTime() / 1000,
                            'product_id': key,
                            'scope_id': scopeId
                        };
                    }, this);

                    return result;
                }
            });
        }
    }
);
