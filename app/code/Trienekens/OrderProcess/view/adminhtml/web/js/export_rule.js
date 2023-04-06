/**
 * Copyright © 2019 Collinsharper Inc. All rights reserved.
 *
 * @author Rudie Wang <rwang@collinsharper.com>
 * @package Collinsharper_CMSports
 * @date 2019.9
 */

/**
 * JS file that used on CMSports Event Management Page
 */
require([
    'jquery',
    'Magento_Ui/js/modal/confirm'
], function($, confirmation) {
    'use strict';

    $(document).ready(function(){
        /**
         * On check / uncheck export rule
         */
        $('body')
            .on('change', '.export_rule', function(event){
                var link = $(this).closest('.data-row').find('.action-menu-item');
                var href = link.attr('href');

                if (this.checked) {
                    if (typeof href  !== "undefined") {
                        href = href.replace(/export_rule\/0/g, "export_rule/1");
                    }
                } else {
                    if (typeof href  !== "undefined") {
                        href = href.replace(/export_rule\/1/g, "export_rule/0");
                    }
                }

                link.attr('href', href);
            })
            .on('click', '.export-link', function(event){
                var href = $(this).attr('href');
                event.preventDefault();
                confirmation({
                    title: 'Export Order',
                    content: 'Are you sure you want to export a order?',
                    actions: {
                        confirm: function(){
                            window.location.href = href;
                        },
                        cancel: function(){
                            return false;
                        }
                    }
                });
            });
    });

});
