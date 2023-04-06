/**
 * @category  Trienekens
 * @package   Trienekens_OrderProcess
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 */

define([
        'Magento_Ui/js/grid/columns/select',
        'mage/translate'
    ], function (Column, $t) {
        'use strict';

        return Column.extend({
            defaults: {
                bodyTmpl: 'ui/grid/cells/html'
            },
            getLabel: function (record) {
                if (!record.process_status) { //not sure why it doesn't exist
                    return '';
                }

                var columnVal = record.process_status[0];

                if (columnVal === '1') {
                    return '<span class="grid-severity-notice"><span>' + $t('Process Succeed') + '</span></span>';
                } else if (columnVal === '2') {
                    return '<span  class="grid-severity-minor"><span>' + $t('Process Failed') + '</span></span>';
                } else if (columnVal === '-1') {
                    return '<span  class="grid-severity-critical"><span>' + $t('Process Removed') + '</span></span>';
                } else {
                    return '<span class="grid-severity-notice" style="background:#fffbbb; color:#f38a5e; border-color: #f38a5e"><span>' + $t('Pending') + '</span></span>';
                }
            }
        });
    }
);

